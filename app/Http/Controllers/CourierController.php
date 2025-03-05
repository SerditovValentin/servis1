<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\State;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index()
    {
        $states = State::whereIn('state', ['Готов к доставке', 'В доставке'])->pluck('id');

        $orders = Order::with(['client', 'state'])
                        ->whereIn('id_state', $states)
                        ->get();

        return view('courier.index', compact('orders'));
    }

    public function startDelivery(Order $order)
    {
        $deliveryInProgressStateId = State::where('state', 'В доставке')->first()->id;

        // Обновляем статус заказа
        $order->update(['id_state' => $deliveryInProgressStateId]);

        // Обновляем статус всех позиций заказа
        OrderItem::where('id_orders', $order->id)->update(['id_state' => $deliveryInProgressStateId]);

        return redirect()->back()->with('success', 'Доставка начата');
    }

    public function deliver(Order $order)
    {
        $deliveredStateId = State::where('state', 'Доставлен')->first()->id;

        // Обновляем статус заказа
        $order->update(['id_state' => $deliveredStateId]);

        // Обновляем статус всех позиций заказа
        OrderItem::where('id_orders', $order->id)->update(['id_state' => $deliveredStateId]);

        return redirect()->back()->with('success', 'Заказ отмечен как доставленный');
    }
}
