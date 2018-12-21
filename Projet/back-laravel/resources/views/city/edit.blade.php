@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Modification de la ville {{$city->name}}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('cityUpdate',$city->id) }}">
                            @method('PUT')
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Nom de la ville</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{$city->name}}" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="latitude" class="col-md-4 col-form-label text-md-right">Latitude</label>

                                <div class="col-md-6">
                                    <input id="latitude" type="text" class="form-control" name="latitude" value="{{$city->lat}}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="longitude" class="col-md-4 col-form-label text-md-right">Longitude</label>

                                <div class="col-md-6">
                                    <input id="longitude" type="text" class="form-control" name="longitude" value="{{$city->lon}}" required>
                                </div>
                            </div>

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
        </div>
    </div>

@endsection
