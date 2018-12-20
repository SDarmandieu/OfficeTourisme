@extends('layouts.app')

@section('content')

    <a href="{{route('cityCreate')}}" class="btn btn-primary">Ajouter une ville</a>

    @foreach($cities as $city)
        <div>Ville : {{$city->name}} latitude : {{$city->lat}}</div>
        <a href="{{route('cityDestroy',$city->id)}}" class="btn btn-danger">Supprimer</a>
    @endforeach

@endsection
