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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID заявки</th>
                            <th>Описание проблемы</th>
                            <th>Устройство</th>
                            <th>Статус</th>
                            <th>Желаемое время визита</th>
                            <th>Адрес</th>
                            <th>Дата и время принятия</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myOrders as $order)
                        <tr>
                            <td>{{ $order->repairRequest->id }}</td>
                            <td>{{ $order->repairRequest->issue_description }}</td>
                            <td>{{ $order->repairRequest->appliance->type_equipment->type }}</td>
                            <td>{{ $order->repairRequest->status->status }}</td>
                            <td>{{ $order->repairRequest->preferred_visit_time }}</td>
                            <td>{{ $order->repairRequest->address }}</td>
                            <td>{{ $order->repair_date_time }}</td>
                            <td>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#partsModal"
                                    data-repair-id="{{ $order->id }}">
                                    Получить запчасти
                                </button>
                                <form action="{{ route('repair.complete', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Завершить</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Модальное окно выбора запчастей -->
            <div class="modal fade" id="partsModal" tabindex="-1" aria-labelledby="partsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="partsModalLabel">Выбор запчастей</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                        </div>
                        <form action="{{ route('repair.parts.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_repair" id="id_repair">

                            <div class="modal-body">
                                <table class="table table-bordered" id="parts_table">
                                    <thead>
                                        <tr>
                                            <th>Наименование запчасти</th>
                                            <th>Количество</th>
                                            <th>Действие</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="order-row">
                                            <td>
                                                <select name="id_warehouse[]" class="form-control part-select" required>
                                                    <option value="">Выберите запчасть</option>
                                                    @foreach($warehouseParts as $part)
                                                    <option value="{{ $part->id }}">{{ $part->name }} ({{ $part->supplier->name }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" name="quantity[]" class="form-control" min="1" required></td>
                                            <td><button type="button" class="btn btn-danger remove-row">Удалить</button></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <button type="button" id="add_row" class="btn btn-primary">+</button>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Сохранить</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function setRepairId(id) {
            document.getElementById('id_repair').value = id;
        }

        // Добавление новой строки
        document.getElementById('add_row').addEventListener('click', function() {
            var newRow = document.querySelector('.order-row').cloneNode(true);
            document.querySelector('#parts_table tbody').appendChild(newRow);
        });

        // Удаление строки
        document.querySelector('#parts_table').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });

        document.querySelectorAll('.btn-warning').forEach(button => {
            button.addEventListener('click', function() {
                let repairId = this.getAttribute('data-repair-id');
                document.getElementById('id_repair').value = repairId;
            });
        });
    </script>

</body>

</html>