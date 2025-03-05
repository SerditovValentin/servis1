<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page.title', 'Ресторан "Полумесяц')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="/css/style.css">

</head>
<body>

    <div class="d-flex flex-column justify-content-between min-vh-100">

        <header class="py-3 border-bottom">
            <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
                <div class="container text-center">
                    <a class="navbar-brand" href="{{ route('director') }}">
                        <img src="\img\Logo.png" width="50" height="50" alt="Logo">
                    </a>

                    <ul class="navbar-nav ms-auto list-group-horizontal">
                        <li class="nav-item">

                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                            
                            <button type="button" class="btn btn-light" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Выход
                            </button>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        

        <main class="flex-grow-1 py-3">
            <div class="container">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <a type="button" class="btn btn-outline-secondary" aria-current="page" style="color:black; margin-bottom: 1em;" href="{{ route('director') }}">Назад</a>

                <h1>Создать отчет о заказах</h1>
                
                <form action="{{ route('director.generateReport') }}" method="GET">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="start_date">С:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" style="width: 200px;" value="{{ request('start_date', '2021-01-01') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="end_date">По:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" style="width: 200px;" value="{{ request('end_date', now()->toDateString()) }}">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Создать отчет</button>
                    </div>
                </form>
                <p>Период: {{ $reportData['start_date'] ?? ' ' }} - {{ $reportData['end_date'] ?? ' ' }}</p>
                <p>Дата и время создания: {{ $reportData['generated_at'] ?? ' ' }}</p>
                @if(isset($reportData) && count($reportData) > 0)
                    <div class="mb-3">
                        <a href="{{ route('director.report.download', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-primary">Скачать PDF</a>
                    </div>
                    <br>
                    <div class="summary mt-3">
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
                    <div class="table-responsive no-padding">
                        <table id="ordersTable" class="text-center table-striped">
                            <thead>
                                <tr>
                                    <th>№ заказа</th>
                                    <th>Состав заказа</th>
                                    <th>Общая сумма заказа</th>
                                    <th>Доставка</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reportData['orders'] as $index => $order)
                                    <tr>
                                        <td>{{ $order['order_id'] }}</td>
                                        <td>{{ $order['order_items'] }}</td>
                                        <td>{{ $order['total_amount'] }} ₽</td>
                                        <td>{{ $order['delivery'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </main>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/ru.json'
                }
            });
        });
    </script>
</body>
</html>