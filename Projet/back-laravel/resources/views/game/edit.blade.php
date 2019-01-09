@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">Modification d'un jeu de piste pour {{$city->name}}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('gameUpdate',[$city->id,$game->id]) }}">
                    @method('PUT')
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Nom du jeu</label>

                        <div class="col-md-6">
                            <input type="text" id="name" class="form-control" name="name" value="{{$game->name}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="desc" class="form-control" name="desc" required>{{$game->desc}}</textarea>
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
