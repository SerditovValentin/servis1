<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page.title', 'Ресторан "Полумесяц')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.css">

    <link rel="stylesheet" href="/css/style.css">

</head>
<body>

    <div class="d-flex flex-column justify-content-between min-vh-100">

        <header class="py-3 border-bottom">
            <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
                <div class="container text-center">
                    <a class="navbar-brand" href="{{ route('waiter.index') }}"><img src="\img\Logo.png" width="50" height="50" alt="Logo"></a>
                    <ul class="navbar-nav ms-auto list-group-horizontal">                
                        <li class="nav-item">
                            <a class="nav-link {{Route::is('cart.view') ? 'active' : ''}}" aria-current="page" href="{{ route('cartwaiter.view') }}"><img src="\img\korz.png" width="25" height="25" alt="Корзина">(<span style="color: black" id="cart-count">{{ array_sum(array_column(session('cart', []), 'quantity')) }}</span>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{Route::is('waiter.orders') ? 'active' : ''}}" aria-current="page" href="{{ route('waiter.orders') }}">Заказы</a>
                        </li>
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
                <h1>Заказы на столики</h1>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="table-responsive"></div>
                    <table data-toggle="table" class="no-padding text-center">
                        <thead>
                            <tr>
                                <th class="table-column">№<br>столика</th>
                                <th>Блюдо/<br>кол-во</th>
                                <th class="status-column">Статус</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @foreach ($order->orderItems as $orderItem)
                                    <tr>
                                        <td class="table-column">{{ $order->table_number }}</td>
                                        <td>{{ $orderItem->position->name }} / {{ $orderItem->quantity }} шт.</td>
                                        <td class="status-column">{{ $orderItem->state->state }}</td>
                                        <td>
                                            @if ($orderItem->state->state == 'Готов к выдаче')
                                                <form action="{{ route('waiter.markAsServed', $orderItem->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" onclick="return proverka();" class="btn btn-success">Выдано</button>
                                                </form>
                                            @endif
                                            @if ($orderItem->state->state == 'В очереди на приготовление')
                                                <form action="{{ route('waiter.markAsServed', $orderItem->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" onclick="return proverka();" class="btn btn-danger">Отмена</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
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