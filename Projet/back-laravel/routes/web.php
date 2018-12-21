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
Route::get('/','HomeController@index')->name('home');

Route::view('/login','auth.login');

Auth::routes();

/**
 * City crud routes
 */
Route::get('/city', 'CityController@index')->name('cityIndex')->middleware('auth');

Route::view('/city/create' , 'city.create')->name('cityCreate')->middleware('auth');
Route::post('/city/store' , 'CityController@store')->name('cityStore')->middleware('auth');

Route::delete('/city/destroy/{id}' , 'CityController@destroy')->name('cityDestroy')->middleware('auth');

Route::get('/city/edit/{id}','CityController@edit')->name('cityEdit')->middleware('auth');
Route::put('/city/update/{id}','CityController@update')->name('cityUpdate')->middleware('auth');
