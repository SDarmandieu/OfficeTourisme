@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">Modification d'une réponse</div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('answerUpdate',$answer->id) }}">
                    @method('PUT')
                    @csrf

                    <div class="form-group row">
                        <label for="answer" class="col-md-4 col-form-label text-md-right">Réponse</label>

                        <div class="col-md-6">
                            <input type="text" id="answer" class="form-control" name="answer"
                                   value="{{$answer->content}}" required>
                        </div>
                    </div>

                    <div class="image-selector row">
                        <p class="col-md-4 col-form-label text-md-right">Image de la réponse</p>
                        <div class="col-md-6">
                            <input
                                id="default"
                                type="radio"
                                name="image"
                                value="{{null}}"
                            @if ($answer->image_id == null)
                                {{'checked'}}
                                @endif>
                            <label
                                for="default"
                                class="image-style"
                                style="background-image:url({{asset('images/default-image.png')}});"></label>
                            @foreach($images as $image)
                                <input
                                    id="image{{$image->id}}"
                                    type="radio"
                                    name="image"
                                    value="{{$image->id}}"
                                    @if ($image->id == $answer->image_id)
                                    {{'checked'}}
                                    @endif>
                                <label
                                    for="image{{$image->id}}"
                                    class="image-style"
                                    style="background-image:url({{asset('storage/'.$image->path)}});"></label>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">
                        <p class="col-md-4 col-form-label text-md-right">Est-ce la bonne réponse ?</p>
                        <div class="col-md-6 my-auto">
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    id="true"
                                    type="radio"
                                    name="valid"
                                    value="{{true}}"
                                @if ($answer->valid)
                                    {{'checked'}}
                                    @endif>
                                <label class="form-check-label" for="true">Oui</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    id="false"
                                    type="radio"
                                    name="valid"
                                    value="{{false}}"
                                @if (!($answer->valid))
                                    {{'checked'}}
                                    @endif>
                                <label class="form-check-label" for="false">Non</label>
                            </div>
                        </div>
                    </div>

                    <p><i class="fas fa-info-circle"></i>
                        blablabla random help
                    </p>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Modifier
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .image-selector input {
            margin: 0;
            padding: 0;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .image-selector input:active + .image-style {
            opacity: .9;
        }

        .image-selector input:checked + .image-style {
            -webkit-filter: none;
            filter: none;
            outline: solid #227DC7;
        }

        .image-style {
            cursor: pointer;
            background-size: contain;
            background-repeat: no-repeat;
            display: inline-block;
            width: 100px;
            height: 70px;
            -webkit-transition: all 100ms ease-in;
            -moz-transition: all 100ms ease-in;
            transition: all 100ms ease-in;
            -webkit-filter: brightness(1.8) grayscale(1) opacity(.7);
            filter: brightness(1.8) grayscale(1) opacity(.7);
        }

        .image-style:hover {
            -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
            filter: brightness(1.2) grayscale(.5) opacity(.9);
        }
    </style>
@endsection
