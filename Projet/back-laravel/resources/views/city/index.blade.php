@extends('layouts.app')

@section('content')

    <a href="{{route('cityCreate')}}" class="btn btn-primary">Ajouter une ville</a>

    @foreach($cities as $city)
        <div>Ville : {{$city->name}} latitude : {{$city->lat}}</div>
        <form action="{{route('cityDestroy',$city->id)}}" method="POST">
            @method('DELETE')
            @csrf
            <button class="btn btn-danger">Supprimer</button>
        </form>
        <a href="{{route('cityEdit',$city->id)}}" class="btn btn-success">Modifier</a>
    @endforeach

@endsection
