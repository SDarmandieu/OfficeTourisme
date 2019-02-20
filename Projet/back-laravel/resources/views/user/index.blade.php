@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('userIndex') }}

@endsection

@section('content')
    <a href="{{route('userCreate')}}"
       class="d-flex align-items-center justify-content-center btn btn-outline-primary mx-auto"><i
            class="fas fa-plus-circle fa-3x mr-1"></i>Créer un administrateur</a>
    <div class="container-fluid mt-3">

        <h2 class="text-center">Liste des administrateurs</h2>

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Email</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <a href="{{route('userEdit',$user->id)}}"
                           class="d-flex align-items-center align-self-start"><i
                                class="fas fa-edit fa-2x mr-1"></i><span class="link_">Modifier</span></a>
                        @if($user->id !== Auth::user()->id)
                            <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                    type="button"
                                    data-toggle="modal" data-point='{{$user}}'
                                    data-target="#destroyModal">
                                <i class="fas fa-trash fa-2x mr-1"></i><span
                                    class="link_">Supprimer cet admin</span>
                            </button>
                        @else
                            <button class="btn btn-link d-flex align-items-center p-0 mt-2 align-self-start"
                                    disabled>
                                <i class="fas fa-trash fa-2x mr-1"></i><span
                                    class="link_">C'est ton compte</span>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{$users->links()}}
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
                    Attention , vous êtes sur le point de supprimer l'administrateur <span></span>. Veuillez confirmer votre choix.
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

@push('scripts')
    <script>
        window.addEventListener('load', function () {
            /**
             *  to adapt the content of destroy modal
             */
            $('#destroyModal').on('show.bs.modal', function (event) {
                let {name, id} = $(event.relatedTarget).data('point')
                let modal = $(this)
                modal.find('.modal-body span').text(name)
                modal.find('.modal-title').text(`Supprimer ${name}`)
                modal.find('.modal-footer form').attr('action', `/user/destroy/${id}`)
            })
        }, false)
    </script>
@endpush
