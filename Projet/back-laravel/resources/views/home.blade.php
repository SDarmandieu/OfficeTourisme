@extends('layouts.app')

{{--@section('breadcrumb')--}}
{{--{{ Breadcrumbs::render('home') }}--}}
{{--@endsection--}}

@section('content')
    <div class="jumbotron mt-n4 border-top">
        <h1 class="display-4">Interface administrateur - Jeux de piste</h1>
        <p class="lead">Vous êtes sur le site reservé aux administrateurs permettant de créer des jeux de piste pour
            l'application mobile/tablette associée.</p>
        <hr class="my-4">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A aspernatur deleniti, dolores doloribus ducimus
            eius harum hic illo incidunt libero minus modi, molestiae mollitia nam porro qui quis, ut voluptates!</p>

        @auth
            <a href="{{route('cityIndex')}}" class="btn btn-primary btn-lg">Accéder à l'interface admin</a></div>
    @else
        <a href="{{route('login')}}" class="btn btn-primary btn-lg">Se connecter</a></div>
    @endauth

@endsection
