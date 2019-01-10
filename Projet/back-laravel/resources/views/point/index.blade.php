@extends('layouts.app')

@section('content')
    <a href="{{route('pointCreate',$city->id)}}"
       class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
            class="fas fa-plus-circle fa-3x mr-1"></i>Ajouter un point d'interêt</a>
    <div class="row container-fluid mt-3">
        <div class="col">
            @if(!($points->count()))
                <h2 class="text-center mt-3">Aucun point d'interêt n'a encore été crée pour {{$city->name}}</h2>
            @else
                <h2 class="text-center">Liste des points d'interêts de {{$city->name}}</h2>

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
                            <td>{{$point->desc}}</td>
                            <td>{{number_format($point->lat,3,'.','')}} / {{number_format($point->lon,3,'.','')}}</td>
                            <td>
                                <a href="{{route('pointEdit',[$city->id,$point->id])}}"
                                   class="d-flex align-items-center align-self-start"><i
                                        class="fas fa-edit fa-2x mr-1"></i><span class="link_">Modifier</span></a>
                                <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start show"
                                        type="button" data-point="{{$point}}">
                                    <i class="fas fa-map-marker-alt mr-1 fa-2x"></i></i><span class="link_">Voir sur la carte</span>
                                </button>
                                <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                        type="button"
                                        data-toggle="modal" data-point='{{$point}}'
                                        data-target="#destroyModal">
                                    <i class="fas fa-trash fa-2x mr-1"></i><span class="link_">Supprimer le point</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif


        </div>

        <div class="col">
            <div id="mapid"></div>
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
                    Attention , vous êtes sur le point de supprimer le point d'interêt <span></span> et tout le contenu
                    qui en
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
            var map = L.map('mapid').setView([{{$city->lat}},{{$city->lon}}], 15);

            L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png", {
                attribution: '<a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            @foreach($points as $point)
            L
                .marker([
                    {{$point->lat}},
                    {{$point->lon}}])
                .addTo(map)
                .bindPopup(
                    `<p>Description : {{$point->desc}}</p>
                    <p>Latitude : ${({{$point->lat}}).toFixed(3)}</p>
                    <p>Longitude : ${({{$point->lon}}).toFixed(3)}</p>`
                )
            @endforeach
            /**
             *  to adapt the content of destroy modal
             */
            $('#destroyModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget) // bouton qui déclenche le modal
                let recipient = button.data('point') // récupère le data-point attribute
                let modal = $(this)
                modal.find('.modal-body span').text(recipient.desc)
                modal.find('.modal-title').text(`Supprimer ${recipient.desc}`)
                modal.find('.modal-footer form').attr('action', `/city/{{$city->id}}/point/destroy/${recipient.id}`)
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
