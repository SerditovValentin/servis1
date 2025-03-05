<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>списание просроченных продуктов</title>
    <link rel="stylesheet" href="css/styl.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.css">


    <link rel="stylesheet" href="/css/style.css">
    
</head>
    <body>
        <div class="d-flex flex-column justify-content-between min-vh-100">

            <header class="py-3 border-bottom">
                <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
                    <div class="container text-center">
                        <a class="navbar-brand" href="{{ route('stkeeper') }}"><img src="/img/Logo.png" width="50" height="50" alt="Logo"></a>
    
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
                <div class="container-xxl">
                    <a type="button" class="btn btn-outline-secondary" aria-current="page" style="color:black; margin-bottom: 1em;" href="{{ route('stkeeper') }}">Вернуться назад</a>
                    <h5>Просроченные продукты</h5>
                    <table data-toggle="table">
                        <thead>
                            <tr>
                                <th class="text-center">Действия</th>
                                @foreach ($columns as $column)
                                    <th class="text-center">{{ $column }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>
                                        <div class="text-center">
                                            <form action="{{ route('stkeeper.writeOff', ['table' => $table, 'id' => $row->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger">Списать</button>
                                            </form>
                                        </div>
                                    </td>
                                    @foreach ($columns as $column)
                                        <td>
                                            <div class="text-center">
                                                @if (strpos($column, 'id_') === 0)
                                                    <a style="color:black" href="{{ url('/stkeeper/' . substr($column, 3)) }}">
                                                        {{ $row->$column }}
                                                    </a>
                                                @else
                                                    {{ $row->$column }}
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </main>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.js"></script>

        
    </body>

</html>
