<header class="py-3 border-bottom">
    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
        <div class="container text-center">
            <a class="navbar-brand" href="{{ route('master_receiver') }}"><img src="/img/Logo.png" width="50" height="50" alt="Logo"></a>

            <ul class="navbar-nav me-auto mb-s mb-mb-0">
                <li class="nav-item">
                    <a class="nav-link {{Route::is('master_receiver.zakaz') ? 'active' : ''}}" aria-current="page"
                        href="{{ route('master_receiver.zakaz') }}">Оформление заказа</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link {{Route::is('stkeeper.orders') ? 'active' : ''}}" aria-current="page"
                        href="{{ route('stkeeper.orders') }}">Заказы</a>
                </li> -->
            </ul>

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