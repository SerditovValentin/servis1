<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page.title', 'Сервисный центр')</title>
    <link rel="stylesheet" href="css/styl.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

    <div class="d-flex flex-column justify-content-between min-vh-100">

        @include('master.header')


        <main class="flex-grow-1 py-3">
            <div class="container-xxl">
                <p>Cервисный инженер</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Клиент</th>
                            <th>Описание проблемы</th>
                            <th>Устройство</th>
                            <th>Статус</th>
                            <th>Желаемое время визита</th>
                            <th>Адрес</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->client->surname }} {{ $order->client->name }}</td>
                            <td>{{ $order->issue_description }}</td>
                            <td>{{ $order->appliance->type_equipment->type }}</td>
                            <td>{{ $order->status->status }}</td>
                            <td>{{ $order->preferred_visit_time }}</td>
                            <td>{{ $order->address }}</td>
                            <td>
                                @if ($order->status->status === 'Зарегистрирована')
                                <form action="{{ route('orders.take', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Взять заказ</button>
                                </form>
                                @elseif ($order->status->status === 'Ремонт')
                                @php
                                $repair = \App\Models\Repair::where('id_repair_requests', $order->id)->first();
                                @endphp
                                <span>
                                    В работе у
                                    {{ $repair->employee->surname . ' ' . $repair->employee->name . ' ' . $repair->employee->patronymic }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>