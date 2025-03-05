<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Position;
use App\Models\State;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class DirectorController extends Controller
{
    public function index()
    {
        return view('director.index');
    }

    public function showReportsPage()
    {
        return view('director.reports');
    }

    public function generateReport(Request $request)
{
    $startDate = $request->input('start_date') ? $request->input('start_date') . ' 00:00:00' : '1900-01-01 00:00:00';
    $endDate = $request->input('end_date') ? $request->input('end_date') . ' 23:59:59' : now();

    $states = State::whereIn('state', ['Выдан', 'Доставлен'])->pluck('id');

    $ordersQuery = Order::with('orderItems.position')
                        ->whereIn('id_state', $states)
                        ->whereBetween('date_and_time', [$startDate, $endDate]);

    $orders = $ordersQuery->orderBy('date_and_time', 'desc')->get();

    $totalOrders = $orders->count();
    $totalItems = $orders->flatMap(function ($order) {
        return $order->orderItems;
    })->count();
    $totalAmount = $orders->sum('total_amount');
    $totalDeliveries = $orders->where('delivery', 1)->count();

    $reportData = [
        'orders' => $orders->map(function ($order) {
            $items = $order->orderItems->map(function ($item) {
                return $item->position->name . ' (x' . $item->quantity . ')';
            })->join(', ');

            return [
                'order_id' => $order->id,
                'order_items' => $items,
                'total_amount' => $order->total_amount,
                'delivery' => $order->delivery ? '+' : '-',
            ];
        }),
        'total_orders' => $totalOrders,
        'total_items' => $totalItems,
        'total_amount' => $totalAmount,
        'total_deliveries' => $totalDeliveries,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'generated_at' => now()->format('Y-m-d H:i:s') // Текущая дата и время
    ];

    return view('director.reports', ['reportData' => $reportData]);
}

public function downloadPdf(Request $request)
{
    $startDate = $request->input('start_date') ? $request->input('start_date') . ' 00:00:00' : '1900-01-01 00:00:00';
    $endDate = $request->input('end_date') ? $request->input('end_date') . ' 23:59:59' : now();

    $states = State::whereIn('state', ['Выдан', 'Доставлен'])->pluck('id');

    $ordersQuery = Order::with('orderItems.position')
                        ->whereIn('id_state', $states)
                        ->whereBetween('date_and_time', [$startDate, $endDate]);

    $orders = $ordersQuery->orderBy('date_and_time', 'desc')->get();

    $totalOrders = $orders->count();
    $totalItems = $orders->flatMap(function ($order) {
        return $order->orderItems;
    })->count();
    $totalAmount = $orders->sum('total_amount');
    $totalDeliveries = $orders->where('delivery', 1)->count();

    $reportData = [
        'orders' => $orders->map(function ($order) {
            $items = $order->orderItems->map(function ($item) {
                return $item->position->name . ' (x' . $item->quantity . ')';
            })->join(', ');

            return [
                'order_id' => $order->id,
                'order_items' => $items,
                'total_amount' => $order->total_amount,
                'delivery' => $order->delivery ? '+' : '-',
            ];
        }),
        'total_orders' => $totalOrders,
        'total_items' => $totalItems,
        'total_amount' => $totalAmount,
        'total_deliveries' => $totalDeliveries,
        'start_date' => $startDate,
        'end_date' => $endDate,
        'generated_at' => now()->format('Y-m-d H:i:s') // Текущая дата и время
    ];

    return $this->generatePdf($reportData);
}

    private function generatePdf($reportData)
    {
        $pdf = Pdf::loadView('director.reports_pdf', ['reportData' => $reportData])
                  ->setOption('defaultFont', 'dejavusans');
        return $pdf->download('report.pdf');
    }


    public function showPopularItemsPage()
    {
        return view('director.popular_items');
    }

    public function generatePopularItemsReport(Request $request)
{
    // Установка времени начала и конца периода
    $startDate = $request->input('start_date') ? $request->input('start_date') . ' 00:00:00' : '1900-01-01 00:00:00';
    $endDate = $request->input('end_date') ? $request->input('end_date') . ' 23:59:59' : now();

    // Получение популярных позиций меню
    $items = OrderItem::select(
                'positions.name as position_name', 
                'id_positions', 
                DB::raw('SUM(quantity) as total_quantity'), 
                DB::raw('SUM(quantity * positions.sale_price) as total_amount')
            )
            ->join('positions', 'order_items.id_positions', '=', 'positions.id')
            ->join('orders', 'order_items.id_orders', '=', 'orders.id')
            ->join('state', 'orders.id_state', '=', 'state.id')
            ->whereIn('state.state', ['Выдан', 'Доставлен'])
            ->whereBetween('orders.date_and_time', [$startDate, $endDate])
            ->groupBy('id_positions', 'positions.name')
            ->orderBy('total_quantity', 'desc')
            ->get();

    $reportData = $items->map(function ($item) {
        return [
            'position_name' => $item->position_name,
            'total_quantity' => $item->total_quantity,
            'total_amount' => $item->total_amount,
        ];
    });

    // Получение текущей даты и времени
    $generatedAt = now();

    return view('director.popular_items', [
        'reportData' => $reportData,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'generatedAt' => $generatedAt,
    ]);
}

public function downloadPopularItemsPdf(Request $request)
{
    $startDate = $request->input('start_date') ? $request->input('start_date') . ' 00:00:00' : '1900-01-01 00:00:00';
    $endDate = $request->input('end_date') ? $request->input('end_date') . ' 23:59:59' : now();

    $items = OrderItem::select(
                'positions.name as position_name', 
                'id_positions', 
                DB::raw('SUM(quantity) as total_quantity'), 
                DB::raw('SUM(quantity * positions.sale_price) as total_amount')
            )
            ->join('positions', 'order_items.id_positions', '=', 'positions.id')
            ->join('orders', 'order_items.id_orders', '=', 'orders.id')
            ->join('state', 'orders.id_state', '=', 'state.id')
            ->whereIn('state.state', ['Выдан', 'Доставлен'])
            ->whereBetween('orders.date_and_time', [$startDate, $endDate])
            ->groupBy('id_positions', 'positions.name')
            ->orderBy('total_quantity', 'desc')
            ->get();

    $reportData = $items->map(function ($item) {
        return [
            'position_name' => $item->position_name,
            'total_quantity' => $item->total_quantity,
            'total_amount' => $item->total_amount,
        ];
    });

    $generatedAt = now();

    return $this->generatePopularItemsPdf($reportData, $startDate, $endDate, $generatedAt);
}

private function generatePopularItemsPdf($reportData, $startDate, $endDate, $generatedAt)
{
    $pdf = Pdf::loadView('director.popular_items_pdf', [
            'reportData' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => $generatedAt,
        ])
        ->setOption('defaultFont', 'dejavusans');
    return $pdf->download('popular_items_report.pdf');
}
}
