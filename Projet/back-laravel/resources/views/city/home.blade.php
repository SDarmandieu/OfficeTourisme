@extends('layouts.app')

@section('content')

    <div class="container">

        <h1>{{$city->name}}</h1>

        <div class="card-deck">
            <div class="card">
                <img class="card-img-top" src="{{asset('images/smartphone-tablet.png')}}"
                     alt="un smartphone et une tablette">
                <div class="card-body">
                    <h3 class="card-title">Jeux de piste</h3>
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
                    </p>
                </div>
                <div class="card-footer">
                    <a href="{{route('gameIndex',$city->id)}}" class="d-flex align-items-center align-self-start"><i
                            class="fas fa-map-marked-alt fa-2x mr-1"></i><span class="link_">Gérer
                                        ses jeux de pistes</span></a>
                </div>
            </div>

            <div class="card">
                <img class="card-img-top" src="{{asset('images/point.png')}}" alt="un point de géolocalisation">
                <div class="card-body">
                    <h3 class="card-title">Points d'interêts</h3>
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
                    </p>
                </div>
                <div class="card-footer">
                    <a href="{{route('pointIndex',$city->id)}}" class="d-flex align-items-center align-self-start"><i
                            class="fas fa-map-marker-alt fa-2x mr-1"></i><span class="link_">Gérer
                                        ses points d'interêt</span></a>
                </div>
            </div>

            <div class="card">
                <img class="card-img-top" src="{{asset('images/pictures.png')}}"
                     alt="plusieurs icônes image stylisés superposées">
                <div class="card-body">
                    <h3 class="card-title">Fichiers</h3>
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
                </div>
                <div class="card-footer">
                    <a href="{{route('fileIndex',[$city->id,"image"])}}"
                       class="d-flex align-items-center align-self-start"><i
                            class="fas fa-images fa-2x mr-1"></i><span class="link_">Gérer
                                        ses fichiers</span></a></p>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('styles')
    <style>
        a:hover {
            text-decoration: none;
        }

        .link_:hover {
            text-decoration: underline;
        }
    </style>
@endsection
