<?php

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Accueil', route('home'));
});

Breadcrumbs::for('login', function ($trail) {
    $trail->push('Connection', route('login'));
});

/**
 * city breadcrumbs
 */
Breadcrumbs::for('cityIndex', function ($trail) {
    $trail->parent('home');
    $trail->push('Villes', route('cityIndex'));
});

Breadcrumbs::for('cityCreate', function ($trail) {
    $trail->parent('cityIndex');
    $trail->push('Création', route('cityCreate'));
});

Breadcrumbs::for('cityEdit', function ($trail, $city) {
    $trail->parent('cityIndex');
    $trail->push('Modification '.$city->name, route('cityEdit', $city->id));
});

Breadcrumbs::for('cityHome', function ($trail, $city) {
    $trail->parent('cityIndex');
    $trail->push($city->name, route('cityHome', $city->id));
});

/**
 * point breadcrumbs
 */
Breadcrumbs::for('pointIndex', function ($trail, $city) {
    $trail->parent('cityHome',$city);
    $trail->push('Points d\'interêt', route('pointIndex', $city->id));
});

Breadcrumbs::for('pointCreate', function ($trail, $city) {
    $trail->parent('pointIndex',$city);
    $trail->push('Création', route('pointCreate', $city->id));
});

Breadcrumbs::for('pointEdit', function ($trail, $point) {
    $trail->parent('pointIndex',$point->city);
    $trail->push('Modification', route('pointEdit', $point->id));
});

/**
 * game breadcrumbs
 */
Breadcrumbs::for('gameIndex', function ($trail, $city) {
    $trail->parent('cityHome',$city);
    $trail->push('Jeux de piste', route('gameIndex', $city->id));
});

Breadcrumbs::for('gameCreate', function ($trail, $city) {
    $trail->parent('gameIndex',$city);
    $trail->push('Création', route('gameCreate', $city->id));
});

Breadcrumbs::for('gameEdit', function ($trail, $game) {
    $trail->parent('gameIndex',$game->city);
    $trail->push('Modifier', route('gameEdit', $game->id));
});

Breadcrumbs::for('gameHome', function ($trail, $game) {
    $trail->parent('gameIndex',$game->city);
    $trail->push($game->name, route('gameHome', $game->id));
});

/**
 * file breadcrumbs
 */
Breadcrumbs::for('fileIndex', function ($trail, $city) {
    $trail->parent('cityHome',$city);
    $trail->push('Fichiers', route('fileIndex', [$city->id,"image"]));
});

Breadcrumbs::for('fileCreate', function ($trail, $city) {
    $trail->parent('fileIndex',$city);
    $trail->push('Création', route('fileCreate', $city->id));
});

Breadcrumbs::for('fileEdit', function ($trail, $file) {
    $trail->parent('fileIndex',$file->city);
    $trail->push('Modification', route('fileEdit', $file->id));
});

/**
 * gamepoint breadcrumbs
 */
Breadcrumbs::for('gamePointIndex', function ($trail, $game , $point) {
    $trail->parent('gameHome',$game);
    $trail->push($point->desc, route('gamePointIndex',[$game->id,$point->id]));
});

/**
 * question breadcrumbs
 */
Breadcrumbs::for('questionCreate', function ($trail, $game , $point) {
    $trail->parent('gamePointIndex',$game , $point);
    $trail->push('Création de question', route('questionCreate', [$game->id,$point->id]));
});

Breadcrumbs::for('questionEdit', function ($trail, $question) {
    $trail->parent('gamePointIndex',$question->game , $question->point);
    $trail->push('Modification de question', route('questionEdit', $question->id));
});

/**
 * answer breadcrumbs
 */
Breadcrumbs::for('answerCreate', function ($trail, $question) {
    $trail->parent('gamePointIndex',$question->game , $question->point);
    $trail->push('Création de réponse', route('answerCreate', $question->id));
});

Breadcrumbs::for('answerEdit', function ($trail, $answer) {
    $trail->parent('gamePointIndex',$answer->question->game , $answer->question->point);
    $trail->push('Modification de réponse', route('answerEdit', $answer->id));
});

/**
 * user breadcrumbs
 */
Breadcrumbs::for('userIndex', function ($trail) {
    $trail->parent('home');
    $trail->push('Administrateurs', route('userIndex'));
});

Breadcrumbs::for('userCreate', function ($trail) {
    $trail->parent('userIndex');
    $trail->push('Création', route('userCreate'));
});

Breadcrumbs::for('userEdit', function ($trail, $user) {
    $trail->parent('userIndex');
    $trail->push('Modification '.$user->name, route('userEdit', $user->id));
});
