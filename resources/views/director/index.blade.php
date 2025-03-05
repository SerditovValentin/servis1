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

    <div class="d-flex flex-column justify-content-between">

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
        

        <main>
            <div class="container mt-4 d-flex flex-column align-items-center">
                <!-- Кнопка для перехода к отчету о заказах -->
                <a href="{{ route('director.reports') }}" class="btn btn-primary btn-lg mb-3">
                    Отчет о заказах
                </a>
            
                <!-- Кнопка для перехода к отчету о популярных позициях меню -->
                <a href="{{ route('director.showPopularItemsPage') }}" class="btn btn-primary btn-lg">
                    Отчет о популярных позициях меню
                </a>
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