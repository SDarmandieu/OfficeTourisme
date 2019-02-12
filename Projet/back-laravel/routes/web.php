<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'HomeController@index')->name('home');

Route::view('/login', 'auth.login')->name('login');

Auth::routes();

/**
 * User routes
 */
Route::get('/user/show/{id}','UserController@show')->name('userShow')->middleware('auth');

Route::view('/user/create','user.create')->name('userCreate')->middleware('auth');
Route::post('/user/store','UserController@store')->name('userStore')->middleware('auth');

Route::delete('/user/destroy/{id}','UserController@destroy')->name('userDestroy')->middleware('auth');

Route::get('/user/edit/{id}','UserController@edit')->name('userEdit')->middleware('auth');
Route::put('/user/update/{id}','UserController@update')->name('userUpdate')->middleware('auth');


/**
 * City crud routes
 */
Route::get('/city', 'CityController@index')->name('cityIndex')->middleware('auth');

Route::view('/city/create', 'city.create')->name('cityCreate')->middleware('auth');
Route::post('/city/store', 'CityController@store')->name('cityStore')->middleware('auth');

Route::delete('/city/destroy/{id}', 'CityController@destroy')->name('cityDestroy')->middleware('auth');

Route::get('/city/edit/{id}', 'CityController@edit')->name('cityEdit')->middleware('auth');
Route::put('/city/update/{id}', 'CityController@update')->name('cityUpdate')->middleware('auth');

/**
 * Routes to city homepage
 */
Route::get('/city/{id}','CityController@home')->name('cityHome')->middleware('auth');

/**
 * Point crud route
 */
Route::get('/city/{city_id}/point','PointController@index')->name('pointIndex')->middleware('auth');

Route::get('/city/{city_id}/point/create','PointController@create')->name('pointCreate')->middleware('auth');
Route::post('/city/{city_id}/point/store','PointController@store')->name('pointStore')->middleware('auth');

Route::get('/point/edit/{point_id}','PointController@edit')->name('pointEdit')->middleware('auth');
Route::put('/point/update/{point_id}','PointController@update')->name('pointUpdate')->middleware('auth');

Route::delete('/point/destroy/{point_id}','PointController@destroy')->name('pointDestroy')->middleware('auth');

/**
 * Game crud route
 */
Route::get('/city/{city_id}/game','GameController@index')->name('gameIndex')->middleware('auth');

Route::get('/city/{city_id}/game/create','GameController@create')->name('gameCreate')->middleware('auth');
Route::post('/city/{city_id}/game/store','GameController@store')->name('gameStore')->middleware('auth');

Route::get('/game/edit/{game_id}','GameController@edit')->name('gameEdit')->middleware('auth');
Route::put('/game/update/{game_id}','GameController@update')->name('gameUpdate')->middleware('auth');

Route::delete('/game/destroy/{game_id}','GameController@destroy')->name('gameDestroy')->middleware('auth');

Route::get('/game/{game_id}','GameController@home')->name('gameHome')->middleware('auth');

/**
 * Game/Point association
 */
Route::get('/game/{game_id}/point/{point_id}','GamePointController@index')->name('gamePointIndex')->middleware('auth');

Route::post('/game/{game_id}/point/attach/{point_id}','GamePointController@attach')->name('gamePointAttach')->middleware('auth');
Route::delete('/game/{game_id}/point/detach/{point_id}','GamePointController@detach')->name('gamePointDetach')->middleware('auth');


/**
 * Question crud
 */
Route::get('/game/{game_id}/point/{point_id}/question/create','QuestionController@create')->name('questionCreate')->middleware('auth');
Route::post('/game/{game_id}/point/{point_id}/question/store','QuestionController@store')->name('questionStore')->middleware('auth');

Route::get('/question/edit/{question_id}','QuestionController@edit')->name('questionEdit')->middleware('auth');
Route::put('/question/update/{question_id}','QuestionController@update')->name('questionUpdate')->middleware('auth');

Route::delete('/question/destroy/{question_id}','QuestionController@destroy')->name('questionDestroy')->middleware('auth');

/**
 * Answer crud
 */
Route::get('/question/{question_id}/answer/create','AnswerController@create')->name('answerCreate')->middleware('auth');
Route::post('/question/{question_id}/answer/store','AnswerController@store')->name('answerStore')->middleware('auth');

Route::get('/answer/edit/{answer_id}','AnswerController@edit')->name('answerEdit')->middleware('auth');
Route::put('/answer/update/{answer_id}','AnswerController@update')->name('answerUpdate')->middleware('auth');

Route::delete('/answer/destroy/{answer_id}','AnswerController@destroy')->name('answerDestroy')->middleware('auth');

/**
 * File crud route
 */
Route::get('/city/{city_id}/files/{type}','FileController@index')->name('fileIndex')->middleware('auth');

Route::get('/city/{city_id}/file/create','FileController@create')->name('fileCreate')->middleware('auth');
Route::post('/city/{city_id}/file/store','FileController@store')->name('fileStore')->middleware('auth');

Route::get('/file/edit/{file_id}','FileController@edit')->name('fileEdit')->middleware('auth');
Route::put('/file/update/{file_id}','FileController@update')->name('fileUpdate')->middleware('auth');

Route::delete('/file/destroy/{file_id}','FileController@destroy')->name('fileDestroy')->middleware('auth');


