<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page.title', 'Ресторан "Полумесяц')</title>
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Поставщик</th>
                                    <th>Наименование запчасти</th>
                                    <th>Количество</th>
                                    <th>Срок поставки</th>
                                    <th>Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="id_supplier" class="form-control">
                                            <option value="">Выберите поставщика</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('id_supplier') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="id_part" class="form-control">
                                            <option value="">Выберите запчасть</option>
                                            @foreach($parts as $part)
                                            <option value="{{ $part->id }}" data-price="{{ $part->price }}" {{ old('id_part') == $part->id ? 'selected' : '' }}>
                                                {{ $part->name }} ({{ $part->price }} руб.)
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="quantity" value="{{ old('quantity') }}" class="form-control" min="1"></td>
                                    <td><input type="date" name="delivery_date" value="{{ old('delivery_date') }}" class="form-control"></td>
                                    <td><button type="submit" class="btn btn-success">Сохранить</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>

                </div>
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>