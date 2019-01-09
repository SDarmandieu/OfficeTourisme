@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <a href="{{route('gameCreate',$city->id)}}"
           class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                class="fas fa-plus-circle fa-3x mr-1"></i>Ajouter un jeu</a>

        <div class="card-deck row">
            @foreach($games as $game)
                <div class="col-sm col-md-6 col-lg-4">
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            {{$game->name}}
                            @switch($game->age)
                                @case("7/9 ans")
                                <span class="badge badge-success">7/9 ans</span>
                                @break

                                @case("9/11 ans")
                                <span class="badge badge-primary">9/11 ans</span>
                                @break

                                @default
                                <span class="badge badge-dark">11/13 ans</span>
                            @endswitch
                        </div>
                        <div class="card-body">
                            {{$game->desc}}
                        </div>
                        <div class="card-footer d-flex flex-column">

                            <a href="{{route('gameHome',[$city->id,$game->id])}}"
                               class="d-flex align-items-center align-self-start"><i class="fas fa-home fa-2x mr-1"></i><span
                                    class="link_">Accéder
                                    au contenu du jeu de piste</span></a>
                            <a href="{{route('gameEdit',[$city->id,$game->id])}}"
                               class="d-flex align-items-center mt-2 align-self-start"><i
                                    class="fas fa-edit fa-2x mr-1"></i><span class="link_">Modifier les informations</span></a>
                            <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                    type="button"
                                    data-toggle="modal" data-game='{{$game}}'
                                    data-target="#destroyModal">
                                <i class="fas fa-trash fa-2x mr-1"></i><span class="link_">Supprimer le jeu</span>
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
    </div>

@endsection

@push('scripts')
    {{-- script pour adapter le contenu du modal de suppression au jeu choisi --}}
    <script>
        $(document).ready(() => {
            $('#destroyModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget) // bouton qui déclenche le modal
                let recipient = button.data('game') // récupère le data-game attribute
                let modal = $(this)
                modal.find('.modal-body span').text(recipient.name)
                modal.find('.modal-title').text(`Supprimer ${recipient.name}`)
                modal.find('.modal-footer form').attr('action', `/city/{{$city->id}}/game/destroy/${recipient.id}`)
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
