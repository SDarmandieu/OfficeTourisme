@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('fileIndex',$city) }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg col-sm text-center my-auto">
                {{$city->name}}
            </div>
            <div class="col-lg col-sm text-center my-auto">
                <ul class="nav nav-pills nav-fill justify-content-center border rounded border-primary">
                    <li class="nav-item">
                        <a id="image" class="nav-link" href="{{route('fileIndex',[$city->id,"image"])}}">Images</a>
                    </li>
                    <li class="nav-item">
                        <a id="video" class="nav-link" href="{{route('fileIndex',[$city->id,"video"])}}">Videos</a>
                    </li>
                    <li class="nav-item">
                        <a id="audio" class="nav-link" href="{{route('fileIndex',[$city->id,"audio"])}}">Audio</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg col-sm text-center">
                <a href="{{route('fileCreate',$city->id)}}"
                   class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                        class="fas fa-plus-circle fa-3x mr-1"></i>Ajouter un fichier</a>
            </div>
        </div>
        <hr>
        <div class="card-deck row">
            @foreach($files as $file)
                <div class="col-sm col-md-6 col-lg-3">
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="card-text d-flex flex-column">
                                {{$file->filename}}
                            </span>
                            @if($file->type == 'image')
                                @switch($file->imagetype->title)
                                    @case("logo")
                                    <span class="badge badge-primary">Logo/Sigle</span>
                                    @break

                                    @case("icon")
                                    <span class="badge badge-dark">Icône de jeu</span>
                                    @break

                                    @default
                                    <span class="badge badge-success">Question/Réponse</span>
                                @endswitch
                            @endif
                        </div>
                        @switch($file->type)
                            @case('image')
                            <img class="card-img-top my-2" src="{{asset('storage/'.$file->path)}}"
                                 alt="{{$file->alt}}">
                            @break

                            @default
                            <p class="text-center my-auto">Description : {{$file->alt}}</p>
                        @endswitch
                        <div class="card-footer d-flex flex-column">
                            <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                    type="button"
                                    data-toggle="modal" data-file='{{$file}}'
                                    data-target="#showModal">
                                <i class="fas fa-search fa-2x mr-1"></i><span
                                    class="link_">Visualiser le fichier</span>
                            </button>
                            <a href="{{route('fileEdit',$file->id)}}"
                               class="d-flex align-items-center mt-2 align-self-start"><i
                                    class="fas fa-edit fa-2x mr-1"></i><span
                                    class="link_">Modifier le fichier</span></a>
                            @if(!($file->games()->count()))
                                <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                        type="button"
                                        data-toggle="modal" data-file='{{$file}}'
                                        data-target="#destroyModal">
                                    <i class="fas fa-trash fa-2x mr-1"></i><span
                                        class="link_">Supprimer le fichier</span>
                                </button>
                            @else
                                <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                        type="button" disabled>
                                    <i class="fas fa-trash fa-2x mr-1"></i><span
                                        class="link_">Fichier utilisé par une ressource.</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Modal destroy-->
        <div class="modal fade" id="destroyModal" tabindex="-1" role="dialog"
             aria-labelledby="destroyModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="destroyModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Attention , vous êtes sur le point de supprimer <span></span> et tout le contenu qui
                        en
                        dépend. Veuillez confirmer votre choix.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler
                        </button>
                        <form method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal show-->
        <div class="modal fade" id="showModal" tabindex="-1" role="dialog"
             aria-labelledby="showModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- script pour adapter le contenu des modals à l'file choisie --}}
    <script>
        $(document).ready(() => {
            $('#destroyModal').on('show.bs.modal', function (event) {
                let {id, filename} = $(event.relatedTarget).data('file')
                let modal = $(this)
                modal.find('.modal-body span').text(filename)
                modal.find('.modal-title').text(`Supprimer ${filename}`)
                modal.find('.modal-footer form').attr('action', `/file/destroy/${id}`)
            })

            $('#showModal').on('show.bs.modal', function (event) {
                let {path, type, filename, alt} = $(event.relatedTarget).data('file')
                let modal = $(this)

                console.log(path)
                modal.find('.modal-title').text(filename)

                let src = `/storage/${path}`

                switch (type) {
                    case "video" :
                        let video = `<video class="myMedia" src="${src}" controls></video>`
                        modal.find('.modal-body').html(video)
                        break

                    case "image" :
                        let image = `<img class="img-fluid" src="${src}" alt="${alt}">`
                        modal.find('.modal-body').html(image)
                        break

                    default :
                        let audio = `<audio class="myMedia" src="${src}" controls></audio>`
                        modal.find('.modal-body').html(audio)
                }
                /**
                 * coupe le media à la fermeture du modal
                 */
            }).on('hidden.bs.modal', function () {
                let src = $("#showModal .myMedia").attr('src');
                $("#showModal .myMedia").attr("src", src)
            })

            /**
             * Rend active la pill selon l'url
             */
            $(".nav-link").each(
                function () {
                    if ($(this).attr('id') === window.location.pathname.split`/`[4]) {
                        $(this).addClass('active')
                    }
                })
        })
    </script>
@endpush

@section('styles')
    <style>
        a:hover {
            text-decoration: none;
        }

        .btn-link:hover {
            text-decoration: none;
        }

        .link_:hover {
            text-decoration: underline;
        }

        .btn-outline-primary {
            width: 250px;
        }

        .card-img-top {
            height: 10em;
            object-fit: contain;
        }
    </style>
@endsection
