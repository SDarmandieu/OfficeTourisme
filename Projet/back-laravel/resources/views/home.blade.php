@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Interface administrateur de gestion des jeux de piste</div>
                @guest
                <div class="card-body">
                    <a href="/login" class="btn btn-primary">Se connecter</a>
                </div>
                @else
                <div>Toto</div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
