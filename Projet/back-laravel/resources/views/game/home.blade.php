@extends('layouts.app')

@section('content')

    <div>{{$game->name}}</div>
    <div>{{$game->city->name}}</div>

@endsection
