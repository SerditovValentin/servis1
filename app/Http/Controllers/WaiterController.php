<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\State;
use App\Models\Ingredient;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WaiterController extends Controller
{   

    public function index(): View
    {
        // Текущая дата
        $today = Carbon::today();

        // Подзапрос для нахождения валидных ингредиентов
        $validIngredientIds = DB::table('warehouse')
            ->where('expiration_date', '>', $today)
            ->where('value', '>', 0)
            ->pluck('id_name_of_ingredient');

         // Подзапрос для нахождения позиций, где все ингредиенты валидны
         $positions = DB::table('positions')
         ->join('ingredient', 'positions.id', '=', 'ingredient.id_positions')
         ->select('positions.id', 'positions.name', 'positions.sale_price', 'positions.weight')
         ->whereIn('ingredient.id_name_of_ingredient', $validIngredientIds)
         ->groupBy('positions.id', 'positions.name', 'positions.sale_price', 'positions.weight')
         ->havingRaw('COUNT(DISTINCT ingredient.id_name_of_ingredient) = (
             SELECT COUNT(DISTINCT i.id_name_of_ingredient)
             FROM ingredient i
             WHERE i.id_positions = positions.id
         )')
         ->get();
        return view('waiter.index', compact('positions'));
    }

    
    public function orders()
{
    $readyStates = State::whereIn('state', ['В очереди на приготовление', 'Приготовление', 'Готов к выдаче'])->pluck('id');

    $orders = Order::with(['orderItems.position', 'orderItems.state'])
                    ->where('delivery', 0)
                    ->whereHas('orderItems', function($query) use ($readyStates) {
                        $query->whereIn('id_state', $readyStates);
                    })
                    ->get();

    return view('waiter.orders', compact('orders'));
}


public function markAsServed($orderItemId)
{
    // Получение статуса "Выдан"
    $servedState = State::where('state', 'Выдан')->firstOrFail();

    // Получение статуса "Отменён"
    $cancelledState = State::where('state', 'Отменён')->first();
    if (!$cancelledState) {
        return redirect()->route('waiter.orders')->with('error', 'Статус "Отменён" не найден');
    }

    // Нахождение элемента заказа
    $orderItem = OrderItem::findOrFail($orderItemId);

    // Проверка текущего состояния и обновление
    if ($orderItem->state->state == 'В очереди на приготовление') {
        // Возврат ингредиентов на склад
        return $this->cancelOrderItem($orderItem, $cancelledState);
    } elseif ($orderItem->state->state == 'Готов к выдаче') {
        // Обновление статуса на "Выдан"
        $orderItem->id_state = $servedState->id;
        $orderItem->save();

        // Обновление статуса заказа, если все позиции выданы
        $this->updateOrderState($orderItem->id_orders);

        return redirect()->route('waiter.orders')->with('success', 'Блюдо выдано');
    } else {
        return redirect()->route('waiter.orders')->with('error', 'Неверный статус для обработки');
    }
}

private function cancelOrderItem($orderItem, $cancelledState)
{
    $positionId = $orderItem->id_positions;
    $quantity = $orderItem->quantity;

    // Получение ингредиентов
    $ingredients = Ingredient::where('id_positions', $positionId)->get();

    DB::beginTransaction();

    try {
        foreach ($ingredients as $ingredient) {
            $ingredientName = $ingredient->id_name_of_ingredient;
            $ingredientUnit = $ingredient->id_unit_of_measurement;
            $requiredQuantity = $ingredient->value * $quantity;

            // Найти все записи на складе
            $warehouseItems = Warehouse::where('id_name_of_ingredient', $ingredientName)
                ->where('id_unit_of_measurement', $ingredientUnit)
                ->get();

            foreach ($warehouseItems as $warehouse) {
                $warehouse->value += $requiredQuantity;
                $warehouse->save();
                $requiredQuantity = 0;
            }

            // Если после возврата остались недостающие ингредиенты, добавить их в склад
            if ($requiredQuantity > 0) {
                Warehouse::create([
                    'id_name_of_ingredient' => $ingredientName,
                    'id_unit_of_measurement' => $ingredientUnit,
                    'value' => $requiredQuantity,
                    'expiration_date' => null
                ]);
            }
        }

        // Обновление статуса позиции на "Отменён"
        $orderItem->id_state = $cancelledState->id;
        $orderItem->save();

        // Пересчет общей стоимости заказа
        $this->updateOrderTotalAmount($orderItem->id_orders);

        // Обновление статуса заказа, если все позиции отменены
        $this->updateOrderState($orderItem->id_orders);

        DB::commit();

        return redirect()->route('waiter.orders')->with('success', 'Позиция заказа отменена и ингредиенты возвращены на склад');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('waiter.orders')->with('error', 'Ошибка при отмене позиции заказа: ' . $e->getMessage());
    }
}

private function updateOrderTotalAmount($orderId)
{
    $order = Order::findOrFail($orderId);

    // Получаем оставшиеся позиции в заказе
    $remainingOrderItems = OrderItem::where('id_orders', $orderId)
        ->whereHas('state', function ($query) {
            $query->where('state', '!=', 'Отменён');
        })
        ->get();

    $totalAmount = 0;
    foreach ($remainingOrderItems as $item) {
        // Получаем цену продажи позиции
        $price = Position::findOrFail($item->id_positions)->sale_price;
        $totalAmount += $price * $item->quantity;
    }

    // Обновление общей стоимости заказа
    $order->total_amount = $totalAmount;
    $order->save();
}

    private function updateOrderState($orderId)
    {
        $servedState = State::where('state', 'Выдан')->firstOrFail();
        $orderItems = OrderItem::where('id_orders', $orderId)->get();

        if ($orderItems->every('id_state', $servedState->id)) {
            Order::where('id', $orderId)->update(['id_state' => $servedState->id]);
        }
    }
}
