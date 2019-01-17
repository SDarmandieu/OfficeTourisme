@extends('layouts.app')

@section('content')

    <div class="container">
        @if($errors)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show col-xs-12 col-md-6 mx-auto text-center"
                     role="alert">
                    {{$error}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        @endif
        <div class="card">
            <div class="card-header">Création de fichier pour {{$city->name}}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('fileStore',$city->id) }}" enctype='multipart/form-data'>
                    @csrf

                    <div class="form-group row">
                        <label for="file" class="col-md-4 col-form-label text-md-right">Fichier</label>

                        <div class="col-md-6">
                            <input id="file" class="btn" type="file" class="form-control" name="file"
                                   required autofocus>
                            <p class="text-danger" hidden><i class="fas fa-exclamation-triangle mr-1"></i>Ce fichier est trop volumineux (15Mo maximum)</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="alt" class="col-md-4 col-form-label text-md-right">Alternative textuelle</label>

                        <div class="col-md-6">
                            <textarea id="alt" class="form-control" name="alt" required></textarea>
                        </div>
                    </div>

                    <span id="ifImage"></span>

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

@push('scripts')
    <script>
        $(document).ready(() => {
            $('#file').bind('change',
                () => {
                    if($('#file')[0].files[0]['type'].split`/`[0]  === 'image')
                    {
                        let imageTypeInput = `<div class="form-group row">
                        <label for="imagetype" class="col-md-4 col-form-label text-md-right">Type d'image</label>
                        <select name="imagetype" class="form-control col-md-6" id="imagetype" required>

                            <option selected disabled hidden>Choisissez un type d'image</option>

                            @foreach($imagetypes as $imagetype)
                            <option value="{{$imagetype->id}}">{{$imagetype->title}}</option>
                            @endforeach
                            </select>
                        </div>`
                        $('#ifImage').html(imageTypeInput)
                    }
                    else $('#ifImage').empty()

                    if($('#file')[0].files[0]['size'] > 15000000)
                    {
                        $('.text-danger').removeAttr('hidden')
                    }
                    else $('.text-danger').attr('hidden','hidden')
                }
            )
        })
    </script>
@endpush