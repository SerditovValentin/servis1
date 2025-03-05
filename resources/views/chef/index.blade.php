<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page.title', 'Ресторан "Полумесяц')</title>
    <link rel="stylesheet" href="css/styl.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.css">

    <link rel="stylesheet" href="/css/style.css">

</head>
<body>

    <div class="d-flex flex-column justify-content-between min-vh-100">

        <header class="py-3 border-bottom">
            <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
                <div class="container text-center">
                    <a class="navbar-brand" href="{{ route('chef.index') }}"><img src="\img\Logo.png" width="50" height="50" alt="Logo"></a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <ul class="navbar-nav ms-auto mb-s mb-mb-0">

                            <li class="nav-item">

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                
                                <button type="button" class="btn btn-light" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Выход
                                </button>
                            </li>
                            
                        </ul>

                    </div>
                </div>
            </nav>
        </header>
        

        <main class="flex-grow-1 py-3">
            <div class="container">
                <h1>Позиции для приготовления</h1>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <table data-toggle="table" class="text-center">
                    <thead>
                        <tr>
                            <th>ID заказа</th>
                            <th>Статус заказа</th>
                            <th>Название позиции</th>
                            <th>Количество</th>
                            <th>Статус позиции</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @foreach ($order->orderItems as $orderItem)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->state->state }}</td>
                                    <td>{{ $orderItem->position->name }}</td>
                                    <td>{{ $orderItem->quantity }}</td>
                                    <td>{{ $orderItem->state->state }}</td>
                                    <td>
                                        @if ($orderItem->state->state == 'В очереди на приготовление')
                                            <form action="{{ route('chef.startCooking', $orderItem->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" onclick="return proverka();" class="btn btn-primary">Начать приготовление</button>
                                            </form>
                                        @elseif ($orderItem->state->state == 'Приготовление')
                                            <form action="{{ route('chef.finishCooking', $orderItem->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" onclick="return proverka();" class="btn btn-success">Готов</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Вывод комментария -->
                            @if ($order->comment)
                                <tr>
                                    <td colspan="6" class="text-left">
                                        <strong>Комментарий:</strong> {{ $order->comment }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.js"></script>
    <script>
        function proverka() {
            if (confirm("Вы действительно хотите подтвердить это дейтсвие?")) {
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>
</html>