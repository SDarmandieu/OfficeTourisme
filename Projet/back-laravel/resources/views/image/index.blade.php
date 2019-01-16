@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <a href="{{route('imageCreate',$city->id)}}"
           class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                class="fas fa-plus-circle fa-3x mr-1"></i>Ajouter une image</a>
        @if(!($images->count()))
            <h2 class="text-center mt-3">Aucune image n'a encore été créée pour {{$city->name}}</div>
        @else
            <div class="card-deck row">
                @foreach($images as $image)
                    <div class="col-sm col-md-6 col-lg-3">
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="card-text d-flex flex-column">
                                {{$image->filename}}
                            </span>
                                @switch($image->imagetype->title)
                                    @case("logo")
                                    <span class="badge badge-primary">Logo/Sigle</span>
                                    @break

                                    @case("icon")
                                    <span class="badge badge-dark">Icône de jeu</span>
                                    @break

                                    @default
                                    <span class="badge badge-success">Question/Réponse</span>
                                @endswitch
                            </div>
                            <img class="card-img-top my-2" src="{{asset('storage/'.$image->path)}}"
                                 alt="{{$image->alt}}">
                            <div class="card-footer d-flex flex-column">
                                <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                        type="button"
                                        data-toggle="modal" data-image='{{$image}}'
                                        data-target="#showModal">
                                    <i class="fas fa-search fa-2x mr-1"></i><span
                                        class="link_">Voir l'image en grand</span>
                                </button>
                                <a href="{{route('imageEdit',$image->id)}}"
                                   class="d-flex align-items-center mt-2 align-self-start"><i
                                        class="fas fa-edit fa-2x mr-1"></i><span
                                        class="link_">Modifier l'image</span></a>
                                @if(!($image->games()->count()))
                                    <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                            type="button"
                                            data-toggle="modal" data-image='{{$image}}'
                                            data-target="#destroyModal">
                                        <i class="fas fa-trash fa-2x mr-1"></i><span
                                            class="link_">Supprimer l'image</span>
                                    </button>
                                @else
                                    <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                            type="button" disabled>
                                        <i class="fas fa-trash fa-2x mr-1"></i><span
                                            class="link_">Image utilisée par une ressource.</span>
                                    </button>
                                @endif
                            </div>
                        </div>
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
                                    <img class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @endsection

        @push('scripts')
            {{-- script pour adapter le contenu des modals à l'image choisie --}}
            <script>
                $(document).ready(() => {
                    $('#destroyModal').on('show.bs.modal', function (event) {
                        let button = $(event.relatedTarget) // bouton qui déclenche le modal
                        let recipient = button.data('image') // récupère le data-image attribute
                        let modal = $(this)
                        modal.find('.modal-body span').text(recipient.filename)
                        modal.find('.modal-title').text(`Supprimer ${recipient.filename}`)
                        modal.find('.modal-footer form').attr('action', `/image/destroy/${recipient.id}`)
                    })

                    $('#showModal').on('show.bs.modal', function (event) {
                        let button = $(event.relatedTarget) // bouton qui déclenche le modal
                        let recipient = button.data('image') // récupère le data-image attribute
                        let modal = $(this)
                        let url = window.location.href.split`/`
                        let domain = `${url[0]}//${url[2]}`
                        modal.find('.modal-body img').attr('src', `${domain}/storage/${recipient.path}`)
                        modal.find('.modal-body img').attr('alt', recipient.alt)
                        modal.find('.modal-title').text(recipient.filename)
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
