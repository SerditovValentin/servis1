<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page.title', 'Сервисный центр')</title>
    <link rel="stylesheet" href="css/styl.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/96d6f2c7e7.js" crossorigin="anonymous"></script>
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.css" rel="stylesheet">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/datatables.js">
    </script>

    <link rel="stylesheet" href="/css/style.css">

</head>

<body>

    <div class="d-flex flex-column justify-content-between min-vh-100">

        @include('stkeeper.header')


        <main class="flex-grow-1 py-3">
            <div class="container-xxl">
                <div class="container mt-4">
                    <h2>Список заказов</h2>

                    <table id="orders_table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Поставщик</th>
                                <th>Общая сумма</th>
                                <th>Дата заказа</th>
                                <th>Дата поставки</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->supplier->name }}</td>
                                <td>{{ $order->total_amount }} ₽</td>
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->delivery_date }}</td>
                                <td>{{ $order->status->status }}</td>
                                <td>
                                    @if ($order->status->status !== 'Доставлен')
                                    <form action="{{ route('stkeeper.mark_delivered', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="return proverka_da();" class="btn btn-success btn-sm">Доставлен</button>
                                    </form>

                                    <a href="#" class="btn btn-primary btn-sm order-details"
                                        data-id="{{ $order->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderModal">
                                        Подробнее
                                    </a>

                                    <form action="{{ route('stkeeper.cancel_order', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" onclick="return proverka_net();" class="btn btn-danger btn-sm">Отменить</button>
                                    </form>
                                    @else
                                    <a href="#" class="btn btn-primary btn-sm order-details"
                                        data-id="{{ $order->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#orderModal">
                                        Подробнее
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

    </div>


    <!-- Модальное окно -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Детали заказа</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Название запчасти</th>
                                <th>Количество</th>
                            </tr>
                        </thead>
                        <tbody id="orderDetailsBody">
                            <!-- Данные загружаются через AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        $('#orders_table').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.21/i18n/Russian.json',
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    previous: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>'
                }
            },

            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Все"]
            ]
        });
    });
</script>

<script>
    function proverka_da() {
        if (confirm("Вы действительно хотите подтвердить получение?")) {
            return true;
        } else {
            return false;
        }
    }

    function proverka_net() {
        if (confirm("Вы действительно хотите отменить?")) {
            return true;
        } else {
            return false;
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.order-details').forEach(button => {
            button.addEventListener('click', function() {
                let orderId = this.getAttribute('data-id');

                fetch(`/index.php/stkeeper/order-details/${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        let tableBody = document.getElementById('orderDetailsBody');
                        tableBody.innerHTML = '';

                        data.forEach(item => {
                            let row = `<tr>
                                            <td>${item.part_name}</td>
                                            <td>${item.quantity}</td>
                                       </tr>`;
                            tableBody.innerHTML += row;
                        });
                    });
            });
        });
    });
</script>

</html>