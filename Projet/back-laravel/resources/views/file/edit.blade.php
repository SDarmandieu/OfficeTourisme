@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('fileEdit',$file) }}
@endsection

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">Modifier le fichier de {{$file->city->name}}</div>

            <div class="card-body">
                @switch($file->type)
                    @case('image')
                    <img class="card-img-top my-2 w-50 mx-auto d-block"
                         src="{{asset('storage/'.$file->path)}}"
                         alt="{{$file->alt}}">
                    @break

                    @case('video')
                    <video class="mx-auto d-block mb-2" src="{{asset('storage/'.$file->path)}}" controls></video>
                    @break

                    @case('audio')
                    <audio class="mx-auto d-block mb-2" src="{{asset('storage/'.$file->path)}}" controls></audio>
                    @break

                @endswitch
                <form method="POST" action="{{ route('fileUpdate',$file->id) }}" enctype='multipart/form-data'>
                    @method('PUT')
                    @csrf

                    <div class="form-group row">
                        <label for="alt" class="col-md-4 col-form-label text-md-right">Alternative textuelle</label>

                        <div class="col-md-6">
                            <textarea id="alt" class="form-control" name="alt" required>{{$file->alt}}</textarea>
                        </div>
                    </div>

                    @if($file->type == 'image')
                        <div class="form-group row">
                            <label for="imagetype" class="col-md-4 col-form-label text-md-right">Type d'image</label>
                            <select name="imagetype" class="form-control col-md-6" id="imagetype" required>

                                @foreach($imagetypes as $imagetype)
                                    <option
                                        value="{{$imagetype->id}}"
                                    @if ($file->imagetype->id == $imagetype->id)
                                        {{'selected'}}
                                        @endif>{{$imagetype->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <p><i class="fas fa-info-circle"></i>
                        Le fichier en lui-même ne peut pas être modifié/retouché ici.
                        Uniquement @if($file->type == "image")son type et @endif son
                        alternative textuelle.
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
