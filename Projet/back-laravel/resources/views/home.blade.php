@extends('layouts.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('home') }}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div>Blablabla accueil</div>
            </div>
        </div>
    </div>
@endsection
