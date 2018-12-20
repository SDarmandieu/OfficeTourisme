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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/city', 'CityController@index')->name('cityIndex')->middleware('auth');
Route::get('/city/create' , 'CityController@create')->name('cityCreate')->middleware('auth');

Route::post('/city/store' , 'CityController@store')->name('cityStore')->middleware('auth');
Route::get('/city/destroy/{id}' , 'CityController@destroy')->name('cityDestroy')->middleware('auth');
