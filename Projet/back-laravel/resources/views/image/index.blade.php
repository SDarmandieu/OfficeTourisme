@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <a href="{{route('imageCreate',$city->id)}}"
           class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                class="fas fa-plus-circle fa-3x mr-1"></i>Ajouter une image</a>

        @if(!($images->count()))
            <div>Aucune image n'a encore été crée pour {{$city->name}}</div>
        @else
        <div class="card-deck row">
            @foreach($images as $image)
                <div class="col-sm col-md-6 col-lg-4">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3>{{$image->filename}}</h3>
                        </div>
                        <div class="card-body">
                            <p class="card-text d-flex flex-column">
                                toto
                            </p>
                        </div>
                        <div class="card-footer d-flex flex-column">
                            <a href="{{route('imageEdit',[$city->id,$image->id])}}"
                               class="d-flex align-items-center mt-2 align-self-start"><i
                                    class="fas fa-edit fa-2x mr-1"></i><span class="link_">Modifier l'image</span></a>
                            <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                    type="button"
                                    data-toggle="modal" data-whatever='{{$image}}'
                                    data-target="#destroyModal">
                                <i class="fas fa-trash fa-2x mr-1"></i><span class="link_">Supprimer l'image</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
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
                                Attention , vous êtes sur le point de supprimer <span></span> et tout le contenu qui en
                                dépend. Veuillez confirmer votre choix.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <form method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        @endsection

        @push('scripts')
            {{-- script pour adapter le contenu du modal de suppression à l'image choisie --}}
            <script>
                $(document).ready(() => {
                    $('#destroyModal').on('show.bs.modal', function (event) {
                        let button = $(event.relatedTarget) // bouton qui déclenche le modal
                        let recipient = button.data('whatever') // récupère le data-image attribute
                        let modal = $(this)
                        modal.find('.modal-body span').text(recipient.filename)
                        modal.find('.modal-title').text(`Supprimer ${recipient.filename}`)
                        modal.find('.modal-footer form').attr('action', `/city/{{$city->id}}/image/destroy/${recipient.id}`)
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
            </style>
@endsection
