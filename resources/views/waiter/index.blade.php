<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page.title', 'Ресторан "Полумесяц"')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.6/dist/bootstrap-table.min.css">

    <link rel="stylesheet" href="/css/style.css">

</head>
<body>

    <div class="d-flex flex-column justify-content-between min-vh-100">

        <header class="py-3 border-bottom">
            <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
                <div class="container text-center">
                    <a class="navbar-brand" href="{{ route('waiter.index') }}"><img src="/img/Logo.png" width="50" height="50" alt="Logo"></a>

                    <ul class="navbar-nav ms-auto list-group-horizontal">                
                        <li class="nav-item">
                            <a class="nav-link {{Route::is('cartwaiter.view') ? 'active' : ''}}" aria-current="page" href="{{ route('cartwaiter.view') }}"><img src="/img/korz.png" width="25" height="25" alt="Корзина">(<span style="color: black" id="cart-count">{{ array_sum(array_column(session('cart', []), 'quantity')) }}</span>)</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{Route::is('waiter.orders') ? 'active' : ''}}" aria-current="page" href="{{ route('waiter.orders') }}">Заказы</a>
                        </li>
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
            <h4 class="text-center">Меню</h4>
            @if(empty($positions))
                <h4 class="text-center">Нет ни одной позиции</h4>
            @else 
                <div class="container text-center">
                    <div class="row">
                        @foreach($positions as $position)
                            <div class="col-12 col-md-4 mb-4">
                                <div class="card h-100">
                                    <div id="alert-{{ $position->id }}" class="alert alert-success d-none">
                                        <p>Товар добавлен в корзину</p>
                                    </div>
                                    <div class="card-body">
                                        <h5>{{$position->name}}</h5>
                                        <p>Цена: {{$position->sale_price}}</p>
                                        <p>Вес: {{$position->weight}}</p>
                                    </div>
                                    <div class="card-footer">
                                        <form action="{{ route('cartwaiter.add', ['id' => $position->id]) }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">В корзину</button>
                                        </form>                         
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Обработчик для всех форм добавления в корзину
            document.querySelectorAll('.add-to-cart-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const actionUrl = form.action;
                    const formData = new FormData(form);
    
                    fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const alertBox = document.getElementById(`alert-${data.added_position_id}`);
                            alertBox.classList.remove('d-none');
                            setTimeout(() => {
                                alertBox.classList.add('d-none');
                            }, 3000);
                            updateCartCount(data.cartCount);
                        }
                    });
                });
            });
    
            function updateCartCount(count) {
                const cartCountElements = document.querySelectorAll('#cart-count');
                cartCountElements.forEach(cartCountElement => {
                    if (cartCountElement) {
                        cartCountElement.innerText = count;
                    }
                });
            }
        });
    </script>
</body>
</html>