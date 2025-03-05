<?php

namespace App\Http\Controllers;

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
        $parts = Warehouse::all();
        
        return view('stkeeper.zakaz', compact('suppliers', 'orders', 'parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_supplier' => 'required|exists:supplier,id',
            'id_part' => 'required|exists:warehouse,id',
            'quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date|after_or_equal:today',
        ]);

        // Получаем цену запчасти
        $part = Warehouse::findOrFail($request->id_part);
        $total_amount = $part->price * $request->quantity;

        // Получаем id статуса "В процессе"
        $status_id = Status::where('status', 'В процессе')->value('id');

        // Создаем заказ
        $order = Order::create([
            'id_supplier' => $request->id_supplier,
            'total_amount' => $total_amount,
            'order_date' => Carbon::now(),
            'delivery_date' => $request->delivery_date,
            'id_status' => $status_id,
        ]);

        // Добавляем запись в ordered_parts
        OrderedParts::create([
            'id_order' => $order->id,
            'id_warehouse' => $request->id_part,
        ]);

        return redirect()->route('stkeeper.zakaz')->with('success', 'Заказ успешно создан.');
    }
}

