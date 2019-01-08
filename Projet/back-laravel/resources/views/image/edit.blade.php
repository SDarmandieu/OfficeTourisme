@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">Modifier l'image de {{$city->name}}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('imageUpdate',[$city->id,$image->id]) }}">
                    @method('PUT')
                    @csrf

                    <div class="form-group row">
                        <label for="file" class="col-md-4 col-form-label text-md-right">Fichier</label>

                        <div class="col-md-6">
                            <input id="file" type="file" class="form-control" name="file" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="filename" class="col-md-4 col-form-label text-md-right">Nom de l'image</label>

                        <div class="col-md-6">
                            <input id="filename" type="text" class="form-control" name="filename" value="{{$image->filename}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="alt" class="col-md-4 col-form-label text-md-right">Alternative textuelle</label>

                        <div class="col-md-6">
                            <textarea id="alt" class="form-control" name="alt" required>{{$image->alt}}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="type"class="col-md-4 col-form-label text-md-right">Type d'image</label>
                        <select name="type" class="form-control col-md-6" id="type">

                            <option>Choisissez un type d'image</option>

                            @foreach($imagetypes as $imagetype)
                                <option value="{{$imagetype->id}}">{{$imagetype->title}}</option>
                            @endforeach
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
