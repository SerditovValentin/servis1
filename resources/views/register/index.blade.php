@extends('layouts.base')

@section('page.title', 'Страница регистрации')

@section('content')
<section>

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 offset-md-3">

                <div class="card">

                    <div class="card-body">

                        <h4 class="m-0">
                            Регистрация
                        </h4>

                    </div>

                    <div class="card-body">
                        <form action="{{ route('register.store')}}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="mb-3">
                                <label class="required">Имя</label>
                                <input type="name" name="name" value="{{old('name')}}" class="form-control">
                                <x-error name="name"/>
                            </div>
                            <div class="mb-3">
                                <label class="">Email</label>
                                <input type="email" name="email" value="{{old('email')}}" class="form-control">
                                <x-error name="email"/>
                            </div>
                            <div class="mb-3">
                                <label class="required">Телефон</label>
                                <input type="phone" name="phone" value="{{old('phone')}}" class="form-control">
                                <x-error name="phone"/>
                            </div>
                            <div class="mb-3">
                                <label class="required">Пароль</label>
                                <input type="password" name="password" value="{{old('password')}}" class="form-control">
                                <x-error name="password"/>
                            </div>
                            <div class="mb-3">
                                <label class="required">Повторите пароль</label>
                                <input type="password" name="password_confirmation" class="form-control">
                                <x-error name="password_confirmation"/>
                            </div>
                            <button type="submit" class="btn btn-primery">
                                Зарегестрироваться
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
@endsection