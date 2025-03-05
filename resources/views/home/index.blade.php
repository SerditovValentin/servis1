@extends('layouts.base')

@section('content')

<!-- <div class="hero-section" style="background-image: url('/img/fon.png'); background-size: cover; background-position: center; height: 100vh;"> -->
  <div class="hero-overlay" style="background-color: rgba(0, 0, 0, 0.5); height: 100%; display: flex; justify-content: center; align-items: center;">
      <div class="hero-content text-center text-white">
          <h1 class="display-3 mb-4">Добро пожаловать в наш сервисный центр по ремонту бытовой техники</h1>
          <p class="lead mb-4">Мы предоставляем быстрый и надежный ремонт бытовой техники в вашем городе.</p>
          <p class="lead mb-4">Наши опытные мастера быстро устранят любую неисправность и вернут вашу технику в рабочее состояние.</p>
          <a href="{{ route('cart.view') }}" class="btn btn-lg btn-light me-2">Оформить заказа</a>
      </div>
  </div>
<!-- </div> -->


@endsection