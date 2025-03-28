<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Приходная накладная</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        .signature-container {
            position: fixed;
            bottom: 20px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* Выравниваем элементы по центру вертикально */
            padding: 0 20px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            display: inline-block;
            /* Делаем линию подписи inline-элементом */
            margin-left: 10px;
            /* Добавляем отступ между ФИО и линией подписи */
        }

        .responsible {
            display: inline-block;
            /* Делаем ФИО inline-элементом */
        }
    </style>
</head>

<body>
    <div class="header">Приходная накладная</div>
    
    <p><strong>№</strong> {{ $id_order }} от {{ $order_date }}</p>
    <p><strong>Организация:</strong> ООО "Сервисный центр"</p>
    <p><strong>Дата:</strong> {{ now()->format('d.m.Y') }}</p>
    <p><strong>Поставщик:</strong> {{ $supplier->name }}</p>
    <table>
        <thead>
            <tr>
                <th>Наименование</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderedParts as $part)
            <tr>
                <td>{{ $part->warehouse->name }}</td>
                <td>{{ $part->quantity }}</td>
                <td>{{ $part->warehouse->price }} ₽</td>
                <td>{{ $part->quantity * $part->warehouse->price }} ₽</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Общая сумма:</strong> {{ $totalAmount }} ₽</p>

    
    <div class="signature-container">

        <div class="container">
            <strong>Принял:</strong>
            <div class="signature-line"></div>
            <span class="responsible">
                (Подпись)
                {{ $userPosition }}
                {{ auth()->user()->surname }}
                {{ mb_substr(auth()->user()->name, 0, 1) }}.
                {{ mb_substr(auth()->user()->patronymic, 0, 1) }}.
            </span>
        </div>
        <br>
        <div class="container">
            <strong>Сдал:</strong>
            <div class="signature-line"></div>
            <span class="responsible">
                (Подпись)
                {{ $userPosition }}
                {{ auth()->user()->surname }}
                {{ mb_substr(auth()->user()->name, 0, 1) }}.
                {{ mb_substr(auth()->user()->patronymic, 0, 1) }}.
            </span>
        </div>

    </div>
</body>

</html>