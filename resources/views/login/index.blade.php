@extends('layouts.base')

@section('page.title', 'Страница входа')

@section('content')

<section>

    <div class="container">

        <div class="row">

            <div class="col-12 col-md-6 offset-md-3">

                <div class="card" style="margin-top: 1rem;">

                    <div class="card-body">

                        <h4 class="m-0">
                            Вход
                        </h4>
                        <p>Данная страница предназначена только для сотрудников!</p>

                    </div>

                    <div class="card-body">
                        <form action="{{ route('login.store')}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="required">Телефон</label>
                                <input type="phone" name="phone" value="{{old('phone')}}" class="form-control" autofocus>
                                <x-error name="phone"/>
                            </div>
                            <div class="mb-3">
                                <label class="required">Пароль</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            @error('message')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-primery">Войти</button>
                        </form>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection