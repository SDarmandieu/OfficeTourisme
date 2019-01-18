@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('questionCreate',$game,$point) }}
@endsection

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">Création de la question de {{$game->name}}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('questionStore',[$game->id,$point->id]) }}">
                    @csrf

                    <div class="form-group row">
                        <label for="question" class="col-md-4 col-form-label text-md-right">Question</label>

                        <div class="col-md-6">
                            <input type="text" id="question" class="form-control" name="question" required>
                        </div>
                    </div>

                    <div class="image-selector row">
                        <p class="col-md-4 col-form-label text-md-right">Image de la question</p>
                        <div class="col-md-6">
                            <input id="default" type="radio" name="file" value="{{null}}">
                            <label
                                for="default"
                                class="image-style"
                                style="background-image:url({{asset('images/default-image.png')}});"></label>
                            @foreach($files as $file)
                                <input id="image{{$file->id}}" type="radio" name="file" value="{{$file->id}}">
                                <label
                                    for="image{{$file->id}}"
                                    class="image-style"
                                    style="background-image:url({{asset('storage/'.$file->path)}});"></label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="expe" class="col-md-4 col-form-label text-md-right">Expérience à gagner</label>
                        <select name="expe" class="form-control col-md-6" id="expe">
                            <option selected disabled hidden>Choisissez l'expérience à gagner</option>
                            <option value="16">16 points</option>
                            <option value="32">32 points</option>
                            <option value="64">64 points</option>
                        </select>
                    </div>

                    <p><i class="fas fa-info-circle"></i>
                        blablabla random help
                    </p>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Créer
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
