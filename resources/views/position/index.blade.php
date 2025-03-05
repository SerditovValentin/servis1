@extends('layouts.base')

@section('page.title', 'Меню')

@section('content')
<h1 class="text-center">
    Меню ресторана
</h1>

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
                        <img src="data:image/jpeg;base64,{{ base64_encode($position->photo) }}" class="card-img-top" alt="Фоточка">
                        <div class="card-body">
                            <h5>{{$position->name}}</h5>
                            <p>Цена: {{$position->sale_price}}</p>
                            <p>Вес: {{$position->weight}}</p>
                        </div>
                        <div class="card-footer">
                            <form action="{{ route('cart.add', ['id' => $position->id]) }}" method="POST" class="add-to-cart-form">
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
@endsection

@section('scripts')
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

@endsection