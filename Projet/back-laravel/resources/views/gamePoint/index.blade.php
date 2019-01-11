@extends('layouts.app')
{{--{{dd($question->images->first()->path)}}--}}
@section('content')
    <div class="container-fluid row">
        <div class="col border-right">
            @if(!$question)
                <h2 class="text-center mt-3">Aucune question n'a encore été créée pour {{$city->name}}</h2>
                <a href="{{route('questionCreate',[$city->id,$game->id,$point->id])}}"
                   class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                        class="fas fa-plus-circle fa-3x mr-1"></i>Créer la question</a>
            @else
                <div>{{$question->content}}</div>
                <div>{{$question->expe}}</div>
                <img src="{{asset('storage/'.$question->images->first()->path)}}">
            @endif
        </div>
        <div class="col">
            @if(!$question)
                <h2 class="text-center mt-3">Veuillez d'abord créer la question avant de rédiger ses réponses.</h2>
            @else
                <div>Toto</div>
            @endif
        </div>
    </div>
@endsection

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
