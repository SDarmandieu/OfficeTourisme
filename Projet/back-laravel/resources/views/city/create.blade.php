@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('cityCreate') }}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Création de ville</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('cityStore') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Nom de la ville</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" required autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="latitude" class="col-md-4 col-form-label text-md-right">Latitude</label>

                                <div class="col-md-6">
                                    <input id="latitude" type="text" class="form-control" name="latitude" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="longitude" class="col-md-4 col-form-label text-md-right">Longitude</label>

                                <div class="col-md-6">
                                    <input id="longitude" type="text" class="form-control" name="longitude" required>
                                </div>
                            </div>

                            <p><i class="fas fa-info-circle"></i> Ces coordonnées servent à placer le point de repère principal des jeux de piste. L'idéal
                                est de le positionner sur l'office de Tourisme.
                            </p>
                            <p><i class="fas fa-info-circle"></i> Cliquer sur la carte pour remplir automatiquement les champs.</p>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Créer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="mapid"></div>
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
    </style>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"
            integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="
            crossorigin=""></script>
    <script>
        window.addEventListener('load', function () {
            var map = L.map('mapid').setView([43.1167, 0.73], 12);

            L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png", {
                attribution: '<a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let marker = false

            /**
             * it places or update marker onclick on the map
             * @param lat
             * @param lng
             * @returns {boolean}
             */
            const updateMarker = (lat, lng) => {
                if (!marker) {
                    marker = L.marker([lat, lng]).addTo(map);
                } else {
                    marker.setLatLng([lat, lng])
                    return false
                }
            }

            map.on('click', function (e) {
                let latitude = e.latlng.lat.toString().substring(0, 15);
                let longitude = e.latlng.lng.toString().substring(0, 15);
                $('#latitude').val(latitude);
                $('#longitude').val(longitude);
                updateMarker(latitude, longitude);
            });

            /**
             * to handle the marker when inputs are changed manually
             *
             * @returns {boolean}
             */
            const updateMarkerByInputs = () => updateMarker( $('#latitude').val() , $('#longitude').val())

            $('#latitude').on('input', updateMarkerByInputs);
            $('#longitude').on('input', updateMarkerByInputs);
        }, false)
    </script>
@endpush
