@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('gameEdit',$game) }}
@endsection

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">Modification d'un jeu de piste pour {{$game->city->name}}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('gameUpdate',$game->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Nom du jeu</label>

                        <div class="col-md-6">
                            <input type="text" id="name" class="form-control" name="name" value="{{$game->name}}"
                                   required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="desc" class="form-control" name="desc" required>{{$game->desc}}</textarea>
                        </div>
                    </div>

                    <div class="image-selector row">
                        <p class="col-md-4 col-form-label text-md-right">Icône principale de la carte</p>
                        <div class="col-md-6">
                            <input
                                id="default"
                                type="radio"
                                name="icon"
                                value="{{null}}"
                            @if ($current_icon === null)
                                {{'checked'}}
                                @endif>
                            <label
                                for="default"
                                class="icon-style"
                                style="background-image:url({{asset('images/default-image.png')}});"></label>
                            @foreach($icons as $icon)
                                <input
                                    id="icon{{$icon->id}}"
                                    type="radio" name="icon"
                                    value="{{$icon->id}}"
                                @if ($current_icon!==null && $icon->id == $current_icon->id)
                                    {{'checked'}}
                                    @endif>
                                <label
                                    for="icon{{$icon->id}}"
                                    class="icon-style"
                                    style="background-image:url({{asset('storage/'.$icon->path)}});"></label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="age" class="col-md-4 col-form-label text-md-right">Tranche d'âge cible</label>
                        <select name="age" class="form-control col-md-6" id="age">
                            <option value="7/9 ans"
                            @if ($game->age == "7/9 ans")
                                {{'selected'}}
                                @endif>
                                7/9 ans
                            </option>
                            <option value="9/11 ans"
                            @if ($game->age == "9/11 ans")
                                {{'selected'}}
                                @endif>
                                9/11 ans
                            </option>
                            <option value="11/13 ans"
                            @if ($game->age == "11/13 ans")
                                {{'selected'}}
                                @endif>
                                11/13 ans
                            </option>
                        </select>
                    </div>

                    <p><i class="fas fa-info-circle"></i>
                        La description du jeu est affichée dans l'application , (ainsi que son nom) : elle doit être concise , décrire le jeu et donner envie.
                    </p>
                    <p><i class="fas fa-info-circle"></i>
                        La tranche d'âge est à titre indicative , et est affichée dans l'appli
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

        .image-selector input:active + .icon-style {
            opacity: .9;
        }

        .image-selector input:checked + .icon-style {
            -webkit-filter: none;
            filter: none;
            outline: solid #227DC7;
        }

        .icon-style {
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

        .icon-style:hover {
            -webkit-filter: brightness(1.2) grayscale(.5) opacity(.9);
            filter: brightness(1.2) grayscale(.5) opacity(.9);
        }
    </style>
@endsection
