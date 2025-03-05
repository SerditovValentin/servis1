<header class="py-3 border-bottom">
    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
        <div class="container text-center">
            <a class="navbar-brand" href="{{ route('employee') }}"><img src="\img\Logo.png" width="50" height="50"
                    alt="Logo"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-s mb-mb-0">
                    <li class="nav-item">
                        <a class="nav-link {{Route::is('registerEmployee') ? 'active' : ''}}" aria-current="page"
                            href="{{ route('registerEmployee') }}">Регистрация сотрудника</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{Route::is('password.reset') ? 'active' : ''}}" aria-current="page"
                            href="{{ route('password.reset') }}">Сброс пароля сотрудника</a>
                    </li>

                </ul>

                <ul class="navbar-nav ms-auto mb-s mb-mb-0">

                    <li class="nav-item">

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                        <button type="button" class="btn btn-light"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Выход
                        </button>
                    </li>

                </ul>

            </div>
        </div>
    </nav>
</header>