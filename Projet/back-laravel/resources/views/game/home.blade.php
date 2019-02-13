@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('gameHome',$game) }}
@endsection

@section('content')
    <div class="row container-fluid mt-3">
        <div class="col">
            @if(!($game->points->count()))
                <h2 class="text-center mt-3">Aucun point d'interêt n'a encore été ajouté à {{$game->name}}</h2>
            @else
                <h2 class="text-center">Liste des points d'interêts de {{$game->name}}</h2>

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Description</th>
                        <th scope="col">Coordonnées</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($points as $point)
                        <tr>
                            <td>
                                {{$point->desc}}
                                <p>
                                    @if(!in_array($game->id,$point->questions->pluck('game_id')->toArray()))
                                        <small><i>Ce point n'a pas encore de question</i></small>
                                    @else
                                        <small><i>Ce point a une question</i></small>
                                    @endif
                                </p>
                            </td>
                            <td>{{number_format($point->lat,3,'.','')}} / {{number_format($point->lon,3,'.','')}}</td>
                            <td>
                                <a href="{{route('gamePointIndex',[$game->id,$point->id])}}"
                                   class="d-flex align-items-center align-self-start"><i
                                        class="fas fa-question mr-1 fa-2x"></i></i>
                                    <span class="link_">Gérer
                                        sa question et ses réponses</span></a>
                                <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start show"
                                        type="button" data-point="{{$point}}">
                                    <i class="fas fa-map-marker-alt mr-1 fa-2x"></i></i><span class="link_">Voir sur la carte</span>
                                </button>
                                <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                        type="button"
                                        data-toggle="modal" data-point='{{$point}}'
                                        data-target="#destroyModal">
                                    <i class="fas fa-trash fa-2x mr-1"></i><span class="link_">Retirer le point</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$points->links()}}
            @endif
        </div>

        <div class="col">
            <div id="mapid"></div>
            <p><i class="fas fa-info-circle"></i>
                Les marqueurs bleus sont les points de la ville non associés au jeu.
            </p>

            <p><i class="fas fa-info-circle"></i>
                Les marqueurs rouges sont les points de la ville déjà associés au jeu.
            </p>

            <p><i class="fas fa-info-circle"></i>
                Pour agir sur un point, il faut soit cliquer sur ce point sur la carte , soit sur une de ses actions dans la liste de gauche.
            </p>
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
                    Attention , vous êtes sur le point de retirer le point d'interêt <span></span> de ce jeu de piste et
                    tout le contenu
                    qui en
                    dépend. Veuillez confirmer votre choix.

                    Ce point ne sera cependant pas totalement supprimé pour la ville mais uniquement pour ce jeu.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <form method="POST">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-danger">Retirer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"
          integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="
          crossorigin=""/>

    <style>
        #mapid {
            height: 500px;
        }

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

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
            integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
            crossorigin=""></script>
    <script>
        window.addEventListener('load', function () {

            var map = L.map('mapid').setView([{{$game->city->lat}},{{$game->city->lon}}], 15);

            L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png", {
                attribution: '<a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let redIcon = new L.Icon({
                iconUrl: '{{asset('images/marker-icon-red.png')}}',
                shadowUrl: '{{asset('images/marker-shadow.png')}}',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            @foreach($game->city->points as $point)
            @if(in_array($point->id,$game->points->pluck('id')->toArray()))
            L
                .marker([
                        {{$point->lat}},
                        {{$point->lon}}],
                    {icon: redIcon})
                .addTo(map)
                .bindPopup(`<p>Description : {{$point->desc}}</p>
                    <p>Latitude : ${({{$point->lat}}).toFixed(3)}</p>
                    <p>Longitude : ${({{$point->lon}}).toFixed(3)}</p>
                    <a href="/game/{{$game->id}}/point/{{$point->id}}"
                                   class="btn btn-primary" style="color:white;">Gérer
                                        sa question et ses réponses</a>
                    <form method='POST' action="/game/{{$game->id}}/point/detach/{{$point->id}}">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger"></i>Retirer du jeu de piste</button>
                    </form>`)
            @else
            L
                .marker([
                    {{$point->lat}},
                    {{$point->lon}}]
                )
                .addTo(map)
                .bindPopup(`<p>Description : {{$point->desc}}</p>
                    <p>Latitude : ${({{$point->lat}}).toFixed(3)}</p>
                    <p>Longitude : ${({{$point->lon}}).toFixed(3)}</p>
                    <form method='POST' action="/game/{{$game->id}}/point/attach/{{$point->id}}">
                @csrf
                    <button class="btn btn-primary">Ajouter au jeu de piste</button>
                    </form>`)
            @endif
            @endforeach
            /**
             *  to adapt the content of destroy modal
             */
            $('#destroyModal').on('show.bs.modal', function (event) {
                let {desc,id} = $(event.relatedTarget).data('point')
                let modal = $(this)
                modal.find('.modal-body span').text(desc)
                modal.find('.modal-title').text(`Supprimer ${desc}`)
                modal.find('.modal-footer form').attr('action', `/game/{{$game->id}}/point/detach/${id}`)
            })

            /**
             * to recenter map on chosen point after click on show button
             */
            $('.show').click(function () {
                let point = $(this).data('point')
                map.setView([point.lat, point.lon], 15)
            })
        }, false)
    </script>
@endpush
