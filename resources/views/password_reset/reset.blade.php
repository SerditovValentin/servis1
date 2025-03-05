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
                                    Сброс пароля пользователя
                                </h4>

                            </div>

                            <div class="card-body">
                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="required">Телефон</label>
                                            <input type="phone_number" name="phone_number" value="{{old('phone_number')}}" class="form-control"b autofocus>
                                            <x-error name="phone_number"/>
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
                
                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-primary">
                                                Сбросить пароль
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>

