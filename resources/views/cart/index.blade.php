@extends('layouts.base')

@section('page.title', 'Оформление заказа')

@section('content')

<section>
    <div class="container">
        <h1 style="text-align: center;">Оформление заказа</h1>

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

        <div class="container d-flex justify-content-center mt-3">
            <div class="card" style="max-width: 900px; width: 100%;">
                <div class="card-body">
                    <form action="{{ route('cart.order') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="required">ФИО</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" maxlength="150">
                                </div>
                                <div class="mb-3">
                                    <label class="required">Телефон</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="required">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                </div>
                                <div class="mb-3" id="adres-field" style="display: none;">
                                    <label>Адрес</label>
                                    <input id="adres" name="adres" type="text" value="{{ old('adres') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <input type="checkbox" name="delivery" id="delivery" value="1" {{ old('delivery') ? 'checked' : '' }}>
                                    <label for="delivery">Выезд на дом</label>
                                </div>
                                <div class="mb-3">
                                    <input type="checkbox" name="consent" id="consent" required {{ old('consent') ? 'checked' : '' }}>
                                    <label for="consent">Согласие на обработку данных</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="required">Тип техники</label>
                                    <select name="type" class="form-control">
                                        <option value="">Выберите тип</option>
                                        @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>
                                            {{ $type->type }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Марка</label>
                                    <input type="text" name="brand" value="{{ old('brand') }}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Модель</label>
                                    <input type="text" name="model" value="{{ old('model') }}" class="form-control">
                                </div>
                                <div class="mb-3" id="master-time-field" style="display: none;">
                                    <label>Желаемое время визита мастера</label>
                                    <input id="master_time" name="master_time" type="datetime-local" value="{{ old('master_time') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Описание неисправности</label>
                                    <textarea name="comment" class="form-control" rows="8" maxlength="5000">{{ old('comment') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-secondary">Отправить заявку</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.6.0/dist/css/suggestions.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.6.0/dist/js/jquery.suggestions.min.js"></script>
<script>
    function kladr() {
        $("#adres").suggestions({
            token: "3e419a9e81c488bb1b20c4ff41bc719f629aed29",
            type: "ADDRESS",
            constraints: {
                locations: {
                    kladr_id: "1100000100000"
                },
            },
            restrict_value: true
        });
    }

    $(document).ready(function() {
        $("#delivery").on("change", function() {
            if ($(this).is(":checked")) {
                $("#adres-field, #master-time-field").slideDown();
            } else {
                $("#adres-field, #master-time-field").slideUp();
            }
        });

        $("#adres").on("focus", function() {
            kladr();
        });
    });
</script>
@endsection