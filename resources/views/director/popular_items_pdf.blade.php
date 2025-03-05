<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Отчет о популярных позициях меню</title>
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
    </style>
</head>
<body>
    <h1>Отчет о популярных позициях меню</h1>
    <p>Период: {{ $startDate }} - {{ $endDate }}</p>
    <p>Дата и время создания: {{ $generatedAt }}</p>
    <table>
        <thead>
            <tr>
                <th>Название позиции</th>
                <th>Количество</th>
                <th>Общая сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $item)
                <tr>
                    <td>{{ $item['position_name'] }}</td>
                    <td>{{ $item['total_quantity'] }}</td>
                    <td>{{ $item['total_amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
