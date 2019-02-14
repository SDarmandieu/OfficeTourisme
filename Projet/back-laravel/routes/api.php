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
use App\File;
use App\Http\Resources\File as FileResource;
use App\Imagetype;
use App\Http\Resources\Imagetype as ImagetypeResource;
use Illuminate\Support\Facades\Storage;


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
        'cities' => CityResource::collection(City::whereHas('games', function ($q) {
            $q->where('published', true);
        })->get()),
        'games' => GameResource::collection(Game::where('published', true)->get()),
        'points' => PointResource::collection(Point::all()),
        'questions' => QuestionResource::collection(Question::all()),
        'answers' => AnswerResource::collection(Answer::all()),
        'files' => FileResource::collection(File::all()),
        'imagetypes' => ImagetypeResource::collection(Imagetype::all()),
    ];
});

//Route::post('/file', function (Request $request) {
//    $file = Storage::disk('public')->get($request->path);
//    return response($file, 200)->header('Content-Type', 'image/jpeg');
//});
