@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">Modifier l'image de {{$city->name}}</div>

            <div class="card-body">
                <img class="card-img-top my-2 w-50 mx-auto d-block" src="{{asset('storage/images/'.$image->filename)}}"
                     alt="{{$image->alt}}">
                <form method="POST" action="{{ route('imageUpdate',[$city->id,$image->id]) }}" enctype='multipart/form-data'>
                    @method('PUT')
                    @csrf

                    <div class="form-group row">
                        <label for="alt" class="col-md-4 col-form-label text-md-right">Alternative textuelle</label>

                        <div class="col-md-6">
                            <textarea id="alt" class="form-control" name="alt" required>{{$image->alt}}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="type" class="col-md-4 col-form-label text-md-right">Type d'image</label>
                        <select name="type" class="form-control col-md-6" id="type">

                            @foreach($imagetypes as $imagetype)
                                <option
                                    value="{{$imagetype->id}}"
                                @if ($image->imagetype->id == $imagetype->id)
                                    {{'selected'}}
                                    @endif>{{$imagetype->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <p><i class="fas fa-info-circle"></i>
                        L'image en elle-même ne peut pas être modifiée/retouchée ici. Uniquement son type et son alternative textuelle.
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
