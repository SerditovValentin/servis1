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

        @include('stkeeper.header')


        <main class="flex-grow-1 py-3">
            <div class="container-xxl">
                <div class="container">
                    <h1 class="text-center">Оформление заказа</h1>

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <form action="{{ route('stkeeper.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="id_supplier" class="form-label">Поставщик</label>
                            <select name="id_supplier" id="id_supplier" class="form-control" required>
                                <option value="">Выберите поставщика</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Срок поставки</label>
                            <input type="date" name="delivery_date" id="delivery_date" class="form-control" required style="width: 200px;">
                        </div>


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
                                        <select name="id_part[]" class="form-control part-select" required>
                                            <option value="">Выберите запчасть</option>
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantity[]" class="form-control" min="1" required></td>
                                    <td><button type="button" class="btn btn-danger remove-row">Удалить</button></td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" id="add_row" class="btn btn-primary">+</button>
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </form>
                </div>
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        let partsData = @json($parts);

        document.getElementById('id_supplier').addEventListener('change', function() {
            let supplierId = this.value;
            document.querySelectorAll('.part-select').forEach(select => updateParts(select, supplierId));
        });

        document.getElementById('add_row').addEventListener('click', function() {
            let table = document.getElementById('parts_table').querySelector('tbody');
            let row = document.querySelector('.order-row').cloneNode(true);
            row.querySelectorAll('input').forEach(input => input.value = '');
            let select = row.querySelector('.part-select');
            select.innerHTML = '<option value="">Выберите запчасть</option>';
            updateParts(select, document.getElementById('id_supplier').value);
            table.appendChild(row);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                if (document.querySelectorAll('.order-row').length > 1) {
                    e.target.closest('tr').remove();
                }
            }
        });

        function updateParts(select, supplierId) {
            select.innerHTML = '<option value="">Выберите запчасть</option>';
            if (supplierId) {
                partsData.filter(part => part.id_supplier == supplierId).forEach(part => {
                    let option = document.createElement('option');
                    option.value = part.id;
                    option.textContent = `${part.name} (${part.price} руб.)`;
                    select.appendChild(option);
                });
            }
        }
    </script>
</body>

</html>