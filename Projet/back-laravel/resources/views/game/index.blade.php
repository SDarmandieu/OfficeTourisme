@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('gameIndex',$city) }}
@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{route('gameCreate',$city->id)}}"
           class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                class="fas fa-plus-circle fa-3x mr-1"></i>Ajouter un jeu</a>
        @if(!($games->count()))
            <h2 class="text-center mt-3">Aucun jeu n'a encore été créé pour {{$city->name}}</h2>
        @else

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

                                @switch($game->published)
                                    @case(true)
                                    <span class="badge badge-success">Publié (en ligne)</span>
                                    @break

                                    @case(false)
                                    <span class="badge badge-danger">Non publié (hors ligne)</span>
                                    @break
                                @endswitch
                            </div>
                            <div class="card-body">
                                <p>Description : {{$game->desc}}</p>
                            </div>
                            <div class="card-footer d-flex flex-column">
                                <form method="POST" action="{{route('gamePublish',$game->id)}}">
                                    @csrf
                                    <button
                                        class="d-flex align-items-center align-self-start btn btn-link pl-0 pb-0"><i
                                            class="fas fa-globe fa-2x mr-1"></i><span
                                            class="link_">Mettre le jeu en ligne</span></button>
                                </form>

                                <a href="{{route('gameHome',$game->id)}}"
                                   class="d-flex align-items-center mt-2 align-self-start"><i
                                        class="fas fa-home fa-2x mr-1"></i><span
                                        class="link_">Accéder
                                    au contenu du jeu de piste</span></a>
                                <a href="{{route('gameEdit',$game->id)}}"
                                   class="d-flex align-items-center mt-2 align-self-start"><i
                                        class="fas fa-edit fa-2x mr-1"></i><span
                                        class="link_">Modifier les informations</span></a>
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
                @endforeach
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    {{-- script pour adapter le contenu du modal de suppression au jeu choisi --}}
    <script>
        $(document).ready(() => {
            $('#destroyModal').on('show.bs.modal', function (event) {
                let {name, id} = $(event.relatedTarget).data('game')
                let modal = $(this)
                modal.find('.modal-body span').text(name)
                modal.find('.modal-title').text(`Supprimer ${name}`)
                modal.find('.modal-footer form').attr('action', `/game/destroy/${id}`)
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
