<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\State;
use App\Models\OrderItem;

use Illuminate\Http\Request;

class ChefController extends Controller
{
    public function index()
    {
        $states = State::whereIn('state', ['В очереди на приготовление', 'Приготовление'])->pluck('id');
        
        $orders = Order::with(['orderItems.position', 'orderItems.state', 'state'])
                        ->whereHas('orderItems', function($query) use ($states) {
                            $query->whereIn('id_state', $states);
                        })
                        ->get();

        return view('chef.index', compact('orders'));
    }

    public function startCooking($orderItemId)
    {
        $state = State::where('state', 'Приготовление')->firstOrFail();

        $orderItem = OrderItem::findOrFail($orderItemId);
        $orderItem->id_state = $state->id;
        $orderItem->save();

        $this->updateOrderState($orderItem->id_orders);

        return redirect()->route('chef.index')->with('success', 'Приготовление начато');
    }

    public function finishCooking($orderItemId)
    {
        $state = State::where('state', 'Готов к выдаче')->firstOrFail();

        $orderItem = OrderItem::findOrFail($orderItemId);
        $orderItem->id_state = $state->id;
        $orderItem->save();

        $this->updateOrderState($orderItem->id_orders);

        return redirect()->route('chef.index')->with('success', 'Приготовление завершено');
    }

    private function updateOrderState($orderId)
    {
        $inProgressState = State::where('state', 'Приготовление')->firstOrFail();
        $orderReadyState = State::where('state', 'Готов к выдаче')->firstOrFail();
        $orderDeliveryReadyState = State::where('state', 'Готов к доставке')->firstOrFail();

        $order = Order::findOrFail($orderId);
        $orderItems = OrderItem::where('id_orders', $orderId)->get();

        if ($orderItems->contains('id_state', $inProgressState->id)) {
            // Если хотя бы одна позиция в состоянии "Приготовление", статус заказа также меняется на "Приготовление"
            $order->id_state = $inProgressState->id;
        } elseif ($orderItems->every('id_state', $orderReadyState->id)) {
            // Если все позиции готовы, меняем статус заказа
            if ($order->delivery == 1) {
                // Если заказ для доставки, меняем статус на "Готов к доставке"
                $order->id_state = $orderDeliveryReadyState->id;
            } else {
                // Иначе статус "Готов к выдаче"
                $order->id_state = $orderReadyState->id;
            }
        }

        $order->save();
    }
}
