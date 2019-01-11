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

Route::view('/login', 'auth.login');

Auth::routes();

/**
 * City crud routes
 */
Route::get('/city', 'CityController@index')->name('cityIndex')->middleware('auth');

Route::view('/city/create', 'city.create')->name('cityCreate')->middleware('auth');
Route::post('/city/store', 'CityController@store')->name('cityStore')->middleware('auth');

Route::delete('/city/destroy/{id}', 'CityController@destroy')->name('cityDestroy')->middleware('auth');

Route::get('/city/edit/{id}', 'CityController@edit')->name('cityEdit')->middleware('auth');
Route::put('/city/update/{id}', 'CityController@update')->name('cityUpdate')->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home');

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

Route::get('/city/{city_id}/point/edit/{point_id}','PointController@edit')->name('pointEdit')->middleware('auth');
Route::put('/city/{city_id}/point/update/{point_id}','PointController@update')->name('pointUpdate')->middleware('auth');

Route::delete('/city/{city_id}/point/destroy/{point_id}','PointController@destroy')->name('pointDestroy')->middleware('auth');

/**
 * Image crud route
 */
Route::get('/city/{city_id}/image','ImageController@index')->name('imageIndex')->middleware('auth');

Route::get('/city/{city_id}/image/create','ImageController@create')->name('imageCreate')->middleware('auth');
Route::post('/city/{city_id}/image/store','ImageController@store')->name('imageStore')->middleware('auth');

Route::get('/city/{city_id}/image/edit/{image_id}','ImageController@edit')->name('imageEdit')->middleware('auth');
Route::put('/city/{city_id}/image/update/{image_id}','ImageController@update')->name('imageUpdate')->middleware('auth');

Route::delete('/city/{city_id}/image/destroy/{image_id}','ImageController@destroy')->name('imageDestroy')->middleware('auth');

/**
 * Game crud route
 */
Route::get('/city/{city_id}/game','GameController@index')->name('gameIndex')->middleware('auth');

Route::get('/city/{city_id}/game/create','GameController@create')->name('gameCreate')->middleware('auth');
Route::post('/city/{city_id}/game/store','GameController@store')->name('gameStore')->middleware('auth');

Route::get('/city/{city_id}/game/edit/{game_id}','GameController@edit')->name('gameEdit')->middleware('auth');
Route::put('/city/{city_id}/game/update/{game_id}','GameController@update')->name('gameUpdate')->middleware('auth');

Route::delete('/city/{city_id}/game/destroy/{game_id}','GameController@destroy')->name('gameDestroy')->middleware('auth');

Route::get('/city/{city_id}/game/{game_id}','GameController@home')->name('gameHome')->middleware('auth');

/**
 * Game/Point association
 */
Route::get('/city/{city_id}/game/{game_id}/point/{point_id}','GamePointController@index')->name('gamePointIndex')->middleware('auth');

Route::post('/city/{city_id}/game/{game_id}/point/attach/{point_id}','GamePointController@attach')->name('gamePointAttach')->middleware('auth');
Route::delete('/city/{city_id}/game/{game_id}/point/detach/{point_id}','GamePointController@detach')->name('gamePointDetach')->middleware('auth');


/**
 * Question crud
 */
