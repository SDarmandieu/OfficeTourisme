@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">Création d'un jeu de piste pour {{$city->name}}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('gameStore',$city->id) }}">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Nom du jeu</label>

                        <div class="col-md-6">
                            <input type="text" id="name" class="form-control" name="name" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="desc" class="col-md-4 col-form-label text-md-right">Description</label>

                        <div class="col-md-6">
                            <textarea id="desc" class="form-control" name="desc" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="age" class="col-md-4 col-form-label text-md-right">Tranche d'âge cible</label>
                        <select name="age" class="form-control col-md-6" id="age">
                            <option selected disabled hidden>Choisissez la tranche d'âge cible</option>
                            <option value="7/9 ans">7/9 ans</option>
                            <option value="9/11 ans">9/11 ans</option>
                            <option value="11/13 ans">11/13 ans</option>
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
