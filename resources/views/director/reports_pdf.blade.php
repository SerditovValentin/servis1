<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Отчет о заказах</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        .summary {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .summary-item {
            flex: 1;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <h1>Отчет о заказах</h1>

    <p>Период: {{ $reportData['start_date'] ?? ' ' }} - {{ $reportData['end_date'] ?? ' ' }}</p>
    <p>Дата и время создания: {{ $reportData['generated_at'] ?? ' ' }}</p>
    
    <div class="summary">
        <div class="summary-item">
            <strong>Всего заказов:</strong> {{ $reportData['total_orders'] }}
        </div>
        <div class="summary-item">
            <strong>Всего позиций меню:</strong> {{ $reportData['total_items'] }}
        </div>
        <div class="summary-item">
            <strong>Общая сумма:</strong> {{ $reportData['total_amount'] }} ₽
        </div>
        <div class="summary-item">
            <strong>Количество доставок:</strong> {{ $reportData['total_deliveries'] }}
        </div>
    </div>
    <br>

    <table>
        <thead>
            <tr>
                <th>№ заказа</th>
                <th>Состав заказа</th>
                <th>Общая сумма заказа</th>
                <th>Доставка</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData['orders'] as $order)
                <tr>
                    <td>{{ $order['order_id'] }}</td>
                    <td>{{ $order['order_items'] }}</td>
                    <td>{{ $order['total_amount'] }}</td>
                    <td>{{ $order['delivery'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
