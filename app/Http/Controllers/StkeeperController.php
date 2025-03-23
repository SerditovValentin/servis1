<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\OrderedParts;
use App\Models\Status;
use Carbon\Carbon;

class StKeeperController extends Controller
{

    public function index()
    {
        return view('stkeeper.index');
    }

    public function zakaz()
    {
        $suppliers = Supplier::all();
        $orders = Order::all();
        $parts = Warehouse::select('id', 'name', 'price', 'id_supplier')->get();

        return view('stkeeper.zakaz', compact('suppliers', 'orders', 'parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id',
            'id_part' => 'required|array|min:1',
            'id_part.*' => 'exists:warehouse,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
        ]);

        $total_amount = 0;

        foreach ($request->id_part as $index => $partId) {
            $part = Warehouse::findOrFail($partId);
            $total_amount += $part->price * $request->quantity[$index];
        }

        $status_id = Status::where('status', 'В процессе')->value('id');

        $order = Order::create([
            'id_supplier' => $request->id_supplier,
            'total_amount' => $total_amount,
            'order_date' => Carbon::now(),
            'delivery_date' => $request->delivery_date,
            'id_status' => $status_id,
        ]);

        foreach ($request->id_part as $index => $partId) {
            OrderedParts::create([
                'id_order' => $order->id,
                'id_warehouse' => $partId,
                'quantity' => $request->quantity[$index],
            ]);
        }

        return redirect()->route('stkeeper.zakaz')->with('success', 'Заказ успешно создан.');
    }

    public function ordersList()
    {
        $orders = Order::with('supplier', 'status')->get();
        return view('stkeeper.orders_list', compact('orders'));
    }

    public function cancelOrder(Order $order)
    {
        $status_id = Status::where('status', 'Отменен')->value('id');
        if ($status_id) {
            $order->update(['id_status' => $status_id]);
        }

        return redirect()->route('stkeeper.orders')->with('success', 'Заказ отменён.');
    }

    public function orderDetails($id)
    {
        $orderDetails = OrderedParts::where('id_order', $id)
            ->join('warehouse', 'ordered_parts.id_warehouse', '=', 'warehouse.id')
            ->select('warehouse.name as part_name', 'ordered_parts.quantity')
            ->get();
        return response()->json($orderDetails);
    }

    public function markDelivered($id)
    {
        $order = Order::findOrFail($id);
        $statusDelivered = Status::where('status', 'Доставлен')->value('id');

        if ($order->id_status !== $statusDelivered) {
            $orderedParts = OrderedParts::where('id_order', $id)->get();

            foreach ($orderedParts as $part) {
                $warehousePart = Warehouse::find($part->id_warehouse);
                if ($warehousePart) {
                    $warehousePart->stock_quantity += $part->quantity;
                    $warehousePart->save();
                }
            }

            $order->id_status = $statusDelivered;
            $order->save();
        }

        return redirect()->route('stkeeper.orders')->with('success', 'Заказ отмечен как доставленный.');
    }

    public function downloadInvoice($id)
    {
        $order = Order::findOrFail($id);
        $orderedParts = OrderedParts::where('id_order', $id)->get();

        $pdf = PDF::loadView('stkeeper.invoice', [
            'order' => $order,
            'supplier' => $order->supplier,
            'orderedParts' => $orderedParts,
            'totalAmount' => $order->total_amount,
        ]);

        return $pdf->download('invoice_order_' . $order->id . '.pdf');
    }



    public function warehouse()
    {
        // Получаем все товары на складе
        $warehouses = Warehouse::all();

        // Группируем товары по имени и суммируем количество
        $warehousesGrouped = $warehouses->groupBy('name')->map(function ($group) {
            return [
                'name' => $group->first()->name,  // Название товара
                'stock_quantity' => $group->sum('stock_quantity'),  // Сумма всех количеств
            ];
        });

        return view('stkeeper.warehouse', compact('warehousesGrouped'));
    }
}
