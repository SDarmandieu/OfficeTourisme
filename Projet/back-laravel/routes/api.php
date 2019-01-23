<?php

use Illuminate\Http\Request;

use App\City;
use App\Http\Resources\City as CityResource;
use App\Game;
use App\Http\Resources\Game as GameResource;
use App\Point;
use App\Http\Resources\Point as PointResource;
use App\Question;
use App\Http\Resources\Question as QuestionResource;
use App\Answer;
use App\Http\Resources\Answer as AnswerResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/database', function () {
    return [
        'cities' => CityResource::collection(City::all()),
        'games' => GameResource::collection(Game::all()),
        'points' => PointResource::collection(Point::all()),
        'questions' => QuestionResource::collection(Question::all()),
        'answers' => AnswerResource::collection(Answer::all()),
    ];
});

