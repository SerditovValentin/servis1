@extends('layouts.base')

@section('content')
    <div class="container">
        <h1>Галерея</h1>
        <div class="row">
            @foreach ($images as $image)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="{{ asset($image) }}" class="card-img-top img-thumbnail img-fixed-size" alt="Изображение" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal{{ $loop->index }}">
                    </div>
                </div>

                <!-- Модальное окно для просмотра изображения -->
                <div class="modal fade" id="imageModal{{ $loop->index }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $loop->index }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <img src="{{ asset($image) }}" class="img-fluid w-100 h-100 modal-image" alt="Изображение">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection