<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page.title', 'Сервисный центр')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="/css/style.css">

</head>
<body>

    <div class="d-flex flex-column justify-content-between min-vh-100">

        @include('employee.header')

        <main class="flex-grow-1 py-3">
            <div class="container">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">

            <div class="card">

                <div class="card-body">

                    <h4 class="m-0 text-center">
                        Регистрация нового сотрудника
                    </h4>

                </div>

                <div class="card-body">
                    <form action="{{ route('registerEmployee.store')}}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="mb-3">
                            <label class="required">Фамилия</label>
                            <input type="surname" name="surname" value="{{old('surname')}}" class="form-control">
                            <x-error name="surname"/>
                        </div>
                        <div class="mb-3">
                            <label class="required">Имя</label>
                            <input type="name" name="name" value="{{old('name')}}" class="form-control">
                            <x-error name="name"/>
                        </div>
                        <div class="mb-3">
                            <label>Отчество</label>
                            <input type="patronymic" name="patronymic" value="{{old('patronymic')}}" class="form-control">
                            <x-error name="patronymic"/>
                        </div>
                        <div class="mb-3">
                            <label class="required">Телефон</label>
                            <input type="phone" name="phone" value="{{old('phone')}}" class="form-control">
                            <x-error name="phone"/>
                        </div>
                        <div class="mb-3">
                            <label class="required">Дата рождения (год.месяц.день)</label>
                            <input type="text" id="date_of_birth" name="date_of_birth" value="{{old('date_of_birth')}}" class="form-control">
                            <x-error name="date_of_birth"/>
                        </div>
                        <div class="mb-3">
                            <label class="required">Должность (роль)</label>
                            <select name="id_post" class="form-control">
                                <option value="" selected>Выберите значение</option>
                                @foreach($posts as $post)
                                    <option value="{{ $post->id }}">
                                        {{ $post->post }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error name="id_post"/>
                        </div>
                        <div class="mb-3">
                            <label class="required">Пароль</label>
                            <input type="password" name="password" value="{{old('password')}}" class="form-control">
                            <x-error name="password"/>
                        </div>
                        <div class="mb-3">
                            <label class="required">Повторите пароль</label>
                            <input type="password" name="password_confirmation" class="form-control">
                            <x-error name="password_confirmation"/>
                        </div>
                        <button type="submit" class="btn btn-success mb-3">
                            Зарегестрировать сотрудника
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>

</div>
        </main>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('#date_of_birth', {
                // Настройки Flatpickr, если нужно
                dateFormat: 'Y.m.d', // Формат даты
            });
        });
    </script>
</body>
</html>

