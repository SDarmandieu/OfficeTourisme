@extends('layouts.app')
@section('content')
    <div class="container-fluid row">
        <div class="col border-right">
            @if(!$question)
                <h2 class="text-center mt-3">Aucune question n'a encore été créée pour {{$game->city->name}}</h2>
                <a href="{{route('questionCreate',[$game->id,$point->id])}}"
                   class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                        class="fas fa-plus-circle fa-3x mr-1"></i>Créer la question</a>
            @else
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Question de ce point d'interêt
                    </div>
                    <div class="card-body">
                        <p>Question : {{$question->content}}</p>
                        <p>Image : <img class="img-fluid" src="{{asset('storage/'.$question->image->path)}}"></p>
                        <p>Expérience : {{$question->expe}} points</p>
                    </div>
                    <div class="card-footer d-flex flex-column">
                        <a href="{{route('questionEdit',$question->id)}}"
                           class="d-flex align-items-center mt-2 align-self-start"><i
                                class="fas fa-edit fa-2x mr-1"></i><span
                                class="link_">Modifier la question</span></a>
                        <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                type="button"
                                data-toggle="modal" data-current='{{$question}}'
                                data-target="#destroyModal">
                            <i class="fas fa-trash fa-2x mr-1"></i><span class="link_">Supprimer la question</span>
                        </button>
                    </div>
                </div>
            @endif
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
                        Attention , vous êtes sur le point de supprimer <span></span>.
                        Veuillez confirmer votre choix.
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

        <div class="col">
            @if(!$question)
                <h2 class="text-center mt-3">Veuillez d'abord créer la question avant de rédiger ses réponses.</h2>
            @else
                @if(!($question->answers->count()))
                    <h2 class="text-center mt-3">Aucune réponse n'a encore été créée pour cette question</h2>
                    <a href="{{route('answerCreate',$question->id)}}"
                       class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                            class="fas fa-plus-circle fa-3x mr-1"></i>Créer une réponse</a>
                @else
                    <a href="{{route('answerCreate',$question->id)}}"
                       class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
                            class="fas fa-plus-circle fa-3x mr-1"></i>Créer une réponse</a>
                    <h2 class="text-center mt-3">Liste des réponses à cette question</h2>

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Contenu</th>
                            <th scope="col">Bonne réponse</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($question->answers as $answer)
                            <tr>
                                <td>{{$answer->content}}</td>
                                <td>{{$answer->valid == 1 ? "Oui" : "Non"}}</td>
                                <td>
                                    <a href="{{route('answerEdit',$answer->id)}}"
                                       class="d-flex align-items-center align-self-start"><i
                                            class="fas fa-edit fa-2x mr-1"></i><span class="link_">Modifier</span></a>
                                    <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                            type="button"
                                            data-toggle="modal" data-current='{{$answer}}'
                                            data-target="#destroyModal">
                                        <i class="fas fa-trash fa-2x mr-1"></i><span
                                            class="link_">Supprimer la réponse</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    {{-- script pour adapter le contenu du modal de suppression à la question ou réponse --}}
    <script>
        $(document).ready(() => {
            $('#destroyModal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget) // bouton qui déclenche le modal
                let recipient = button.data('current') // récupère le data-question attribute
                let modal = $(this)
                console.log(recipient)
                modal.find('.modal-title').text(`Supprimer`)
                if (recipient.expe) {
                    modal.find('.modal-body span').text(`cette question et ainsi que toutes les réponses qui en dépendent`)
                    modal.find('.modal-footer form').attr('action', `/question/destroy/${recipient.id}`)
                } else {
                    modal.find('.modal-body span').text(`cette réponse`)
                    modal.find('.modal-footer form').attr('action', `/answer/destroy/${recipient.id}`)
                }
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
