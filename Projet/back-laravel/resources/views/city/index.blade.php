@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('cityIndex') }}
@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{route('cityCreate')}}"
           class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                class="fas fa-plus-circle fa-3x mr-1"></i>Ajouter une ville</a>

        @if(!($cities->count()))
            <h2 class="text-center mt-3">Aucune ville n'a encore été créée</h2>
        @else

            <div class="card-deck row">
                @foreach($cities as $city)
                    <div class="col-sm col-md-6 col-lg-4">
                        <div class="card mt-4">
                            <div class="card-header">
                                <h3>{{$city->name}}</h3>
                                <p>latitude : {{$city->lat}}</p>
                                <p>longitude : {{$city->lon}}</p>
                            </div>
                            <div class="card-body">
                                <p class="card-text d-flex flex-column">
                                    @switch($city->games->count())
                                        @case(0)
                                        Cette ville n'a aucun jeu de piste pour le moment.
                                        @break

                                        @case(1)
                                        Cette ville a 1 jeu de piste.
                                        @break

                                        @default
                                        Cette ville a {{$city->games->count()}} jeux de pistes.
                                    @endswitch
                                    <a href="{{route('gameIndex',$city->id)}}"
                                       class="d-flex align-items-center align-self-start"><i
                                            class="fas fa-map-marked-alt mr-1"></i><span class="link_">Voir
                                        ses jeux de pistes</span></a>
                                </p>

                                <p class="card-text d-flex flex-column">
                                    @switch($city->files->count())
                                        @case(0)
                                        Cette ville n'a aucun fichier associé pour le moment.
                                        @break

                                        @case(1)
                                        Cette ville a 1 fichier associé.
                                        @break

                                        @default
                                        Cette ville a {{$city->files->count()}} fichiers associés.
                                    @endswitch
                                    <a href="{{route('fileIndex',[$city->id,"image"])}}"
                                       class="d-flex align-items-center align-self-start"><i
                                            class="fas fa-images mr-1"></i><span class="link_">Voir
                                        ses fichiers</span></a></p>

                                <p class="card-text d-flex flex-column">
                                    @switch($city->points->count())
                                        @case(0)
                                        Cette ville n'a aucun point d'interêt pour le moment.
                                        @break

                                        @case(1)
                                        Cette ville a 1 point d'interêt.
                                        @break

                                        @default
                                        Cette ville a {{$city->points->count()}} points d'interêt.
                                    @endswitch

                                    <a href="{{route('pointIndex',$city->id)}}"
                                       class="d-flex align-items-center align-self-start"><i
                                            class="fas fa-map-marker-alt mr-1"></i><span class="link_">Voir
                                        ses points d'interêt</span></a></p>
                            </div>
                            <div class="card-footer d-flex flex-column">

                                <a href="{{route('cityHome',$city->id)}}"
                                   class="d-flex align-items-center align-self-start"><i
                                        class="fas fa-home fa-2x mr-1"></i><span
                                        class="link_">Accéder
                                    au contenu (jeux , lieux , points)</span></a>
                                <a href="{{route('cityEdit',$city->id)}}"
                                   class="d-flex align-items-center mt-2 align-self-start"><i
                                        class="fas fa-edit fa-2x mr-1"></i><span class="link_">Modifier le nom de la ville / ses coordonnées</span></a>
                                <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                        type="button"
                                        data-toggle="modal" data-city='{{$city}}'
                                        data-target="#destroyModal">
                                    <i class="fas fa-trash fa-2x mr-1"></i><span class="link_">Supprimer la ville</span>
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
    {{-- script pour adapter le contenu du modal de suppression à la ville choisie --}}
    <script>
        $(document).ready(() => {
            $('#destroyModal').on('show.bs.modal', function (event) {
                let {name,id} = $(event.relatedTarget).data('city')
                let modal = $(this)
                modal.find('.modal-body span').text(name)
                modal.find('.modal-title').text(`Supprimer ${name}`)
                modal.find('.modal-footer form').attr('action', `/city/destroy/${id}`)
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
