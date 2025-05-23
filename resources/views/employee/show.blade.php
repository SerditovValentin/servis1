<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Таблица {{$table}}</title>
    <link rel="stylesheet" href="css/styl.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.css">


    <link rel="stylesheet" href="/css/style.css">
    
</head>
    <body>
        <div class="d-flex flex-column justify-content-between min-vh-100">
            
            @include('employee.header')

            <main class="flex-grow-1 py-3">
                        <div class="container-xxl">
                            <a type="button" class="btn btn-outline-secondary" aria-current="page" style="color:black; margin-bottom: 1em;" href="{{ route('employee', ['table' => $table]) }}">Вернуться к списку таблиц</a>
                            <h5>Таблица: {{ $table }}</h5>
                            <a href="{{ route('employee.create', $table) }}" class="btn btn-success mb-3">Добавить запись</a>
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
                                                <a class="nav-link " style="color:black" href="{{ route('employee.edit', ['table' => $table, 'id' => $row->id]) }}">Изменить</a>
                                                <form action="{{ route('employee.destroy', ['table' => $table, 'id' => $row->id]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return proverka();" class="btn btn-danger">Удалить</button>
                                                </form>
                                                </div>
                                            </td>
                                            @foreach ($columns as $column)
                                                <td>
                                                    <div class="text-center">
                                                        @if (strpos($column, 'id_') === 0)
                                                            <a style="color:black" href="{{ url('/employee/' . preg_replace('/\d+$/', '', substr($column, 3))) }}">
                                                                {{ $row->$column }}
                                                            </a> 
                                                        @else
                                                            @if ($columnTypes[$column] == 'blob')
                                                                <img src="data:image/jpeg;base64,{{ base64_encode($row->$column) }}" class="card-img-top" alt="Фоточка">
                                                            @else
                                                                {{ $row->$column }}
                                                            @endif
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
        <script>
            function proverka() {
                if (confirm("Вы действительно хотите удалить?")) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>

        
    </body>

</html>
