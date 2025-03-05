<header class="py-1 border-bottom">
    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top">
        <div class="container text-center">
            <a class="navbar-brand" href="{{ route('home') }}"><img src="\img\Logo.png" width="50" height="50" alt="Logo"></a>
    
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <ul class="navbar-nav ms-auto d-md-none">
                <li class="nav-item">
                    <a class="nav-link {{Route::is('login') ? 'active' : ''}}" aria-current="page" href="{{ route('login') }}">Вход</a>
                </li>
            </ul>
    
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{Route::is('cart.view') ? 'active' : ''}}" aria-current="page" href="{{ route('cart.view') }}">Оформить заказ</a>
                    </li>                            
                    <li class="nav-item">
                        <a class="nav-link {{Route::is('about_restauran') ? 'active' : ''}}" aria-current="page" href="{{ route('about_restauran') }}">О сервисном центре</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{Route::is('contacts') ? 'active' : ''}}" aria-current="page" href="{{ route('contacts') }}">Контакты</a>
                    </li>                           
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-none d-md-flex">                   
                    <li class="nav-item">
                        <a class="nav-link {{Route::is('login') ? 'active' : ''}}" aria-current="page" href="{{ route('login') }}">Вход</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>