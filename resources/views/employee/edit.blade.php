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
                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <a type="button" class="btn btn-outline-secondary" aria-current="page" style="color:black; margin-bottom: 1em;" href="{{ route('employee.show', ['table' => $table]) }}">Вернуться к таблице</a>
                            <h5>Редактирование в таблице: {{ $table }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('employee.update', ['table' => $table, 'id' => $row->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                @foreach ($columns as $column)
                                    <div class="mb-3">
                                        <label for="{{ $column }}">
                                            {{ $column }} @if(isset($columnComments[$column])) ({{ $columnComments[$column] }}) @endif
                                        </label>
                                        @if (strpos($column, 'id_') === 0)
                                            <select name="{{ $column }}" id="{{ $column }}" class="form-control">
                                                @foreach ($relatedData[$column] as $relatedRow)
                                                    <option value="{{ $relatedRow['id'] }}" {{ $row->$column == $relatedRow['id'] ? 'selected' : '' }}>
                                                        {{ $relatedRow['display'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($columnTypes[$column] == 'blob')
                                            <input type="file" name="{{ $column }}" class="form-control">
                                            @if (!empty($row->$column))
                                                <img src="data:image/jpeg;base64,{{ base64_encode($row->$column) }}" width="300" class="img-fluid img-thumbnail" alt="Фоточка">
                                            @endif
                                        @elseif ($columnTypes[$column] == 'date')
                                            <input type="date" name="{{ $column }}" id="{{ $column }}" value="{{ old($column, $row->$column) }}" class="form-control">
                                        @else
                                            <input type="text" name="{{ $column }}" id="{{ $column }}" value="{{ old($column, $row->$column) }}" class="form-control">
                                        @endif
            
                                        <!-- Вывод сообщения об ошибке -->
                                        @error($column)
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-outline-success">Обновить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.js"></script>

        
    </body>

</html>
