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
                    <a class="navbar-brand" href="{{ route('waiter.index') }}">
                        <img src="/img/Logo.png" width="50" height="50" alt="Logo">
                    </a>

                    <ul class="navbar-nav ms-auto list-group-horizontal">                
                        <li class="nav-item">
                            <a class="nav-link {{Route::is('cart.view') ? 'active' : ''}}" aria-current="page" href="{{ route('cartwaiter.view') }}">
                                <img src="/img/korz.png" width="25" height="25" alt="Корзина">
                                (<span style="color: black" id="cart-count">{{ array_sum(array_column(session('cart', []), 'quantity')) }}</span>)
                            </a>
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
            <div class="container">
                <h1>Корзина</h1>
            
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                    @if(count($cart) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="text-center">
                                        <th>Назв.</th>
                                        <th>Количество</th>
                                        <th>Итого</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach($cart as $id => $item)
                                        <tr>
                                            <td>{{ $item['name'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary update-cart" data-id="{{ $id }}" data-action="decrease">-</button>
                                                <span id="quantity-{{ $id }}">{{ $item['quantity'] }}</span>
                                                <button class="btn btn-sm btn-primary update-cart" data-id="{{ $id }}" data-action="increase">+</button>
                                                
                                            </td>
                                            <td id="item-total-{{ $id }}">{{ $item['sale_price'] * $item['quantity'] }}</td>
                                            <td>
                                                <form action="{{ route('cartwaiter.remove', ['id' => $id]) }}" method="GET">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach                                
                                </tbody>                            
                            </table>
                        </div>
                        <div class="row align-items-center" style="margin: 10px">
                            <div class="col"><h5>Общая сумма:</h5></div>
                            <div class="col"><h5 style="text-align: right" id="totalAmount">{{ $totalAmount }}</h5></div>
                            <div class="col-1"><h5>₽</h5></div>
                        </div>
                        <div class="container d-flex justify-content-center mt-5">
                            <div class="card" style="max-width: 500px; width: 100%;">
                                <div class="card-body">
                                    <form action="{{ route('cartwaiter.order') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="required">Номер столика</label>
                                            <input type="table_number" name="table_number" value="{{ old('table_number') }}" class="form-control" autofocus>
                                            <x-error name="table_number"/>
                                        </div>
                                        <div class="mb-3">
                                            <label>Комментарий</label>
                                            <textarea name="comment" class="form-control" rows="5" maxlength="5000">{{ old('comment') }}</textarea>
                                            <x-error name="comment"/>
                                        </div>
                                        @foreach($cart as $id => $item)
                                            <input type="hidden" name="positions[{{ $id }}][id]" value="{{ $id }}">
                                            <input type="hidden" name="positions[{{ $id }}][quantity]" value="{{ $item['quantity'] }}">
                                        @endforeach
                                        @error('message')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        <button type="submit" class="btn btn-secondary">Оформить заказ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                                        
                    @else
                        <p>Корзина пуста.</p>
                    @endif 

            </div>
        </main>

    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.6.0/dist/css/suggestions.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.6.0/dist/js/jquery.suggestions.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Обработчик для кнопок обновления корзины
        document.querySelectorAll('.update-cart').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const action = this.dataset.action;
                const quantitySpan = document.getElementById(`quantity-${id}`);
                let quantity = parseInt(quantitySpan.innerText);

                // Увеличение или уменьшение количества товаров
                if (action === 'increase' && quantity < 25) {
                    quantity++;
                } else if (action === 'decrease' && quantity > 1) {
                    quantity--;
                }

                // Отправка запроса на обновление корзины
                fetch('{{ route('cartwaiter.updateAjax') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id, quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Обновление количества товара
                        quantitySpan.innerText = quantity;
                        document.getElementById(`item-total-${id}`).innerText = data.itemTotal;
                        document.getElementById('totalAmount').innerText = data.totalAmount;
                        updateCartCount(data.cartCount);

                        // Обновление скрытых полей формы
                        const hiddenQuantityInput = document.querySelector(`input[name="positions[${id}][quantity]"]`);
                        if (hiddenQuantityInput) {
                            hiddenQuantityInput.value = quantity;
                        }
                    }
                });
            });
        });

        // Обновление количества товаров в корзине
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