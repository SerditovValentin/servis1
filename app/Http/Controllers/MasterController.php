<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepairParts;
use App\Models\RepairRequest;
use App\Models\Repair;
use App\Models\OrderedParts;
use App\Models\Status;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    public function index()
    {
        $orders = RepairRequest::whereHas('status', function ($query) {
            $query->whereIn('status', ['Зарегистрирована', 'Ремонт']);
        })->get();

        return view('master.index', compact('orders'));
    }

    // Взятие заказа в ремонт
    public function takeOrder($id)
    {
        $order = RepairRequest::findOrFail($id);

        // Получаем id статуса "Ремонт"
        $repairStatusId = Status::where('status', 'Ремонт')->value('id');

        // Обновляем статус заявки на "Ремонт"
        $order->update(['id_status' => $repairStatusId]);

        // Добавляем запись в таблицу repair
        Repair::create([
            'id_repair_requests' => $order->id,
            'id_employee' => Auth::id(), // ID текущего пользователя
            'repair_details' => '',
            'repair_date_time' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Заказ принят в ремонт');
    }

    // Стрнарица Мои заказы
    public function myOrders()
    {
        $userId = Auth::id();

        // Получаем заказы текущего пользователя
        $myOrders = Repair::where('id_employee', $userId)->with('repairRequest')->get();

        // Получаем запчасти со связью с поставщиком
        $warehouseParts = Warehouse::with('supplier')->get();

        return view('master.my_orders', compact('myOrders', 'warehouseParts'));
    }

    public function store(Request $request)
    {
        $status = Status::where('status', 'Создан')->first();
        
        foreach ($request->id_warehouse as $warehouseId) {
            RepairParts::create([
                'id_repair' => $request->id_repair,
                'id_warehouse' => $warehouseId,
                'id_status' => $status->id
            ]);
        }

        return redirect()->back()->with('success', 'Запчасти добавлены');
    }

    public function completeRepair($id)
    {
        $repair = Repair::findOrFail($id);

        $status = Status::where('status', 'Завершен')->first();
        $repair->id_status = $status->id;
        $repair->save();

        return redirect()->back()->with('success', 'Ремонт завершен');
    }
}
