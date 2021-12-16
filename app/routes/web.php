<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\MoviesController@index')->name('movies.index');
Route::get('/movies/{movie_id}', 'App\Http\Controllers\MoviesController@show')->name('movies.show')->where('movie_id', '[0-9]+');

Route::get('/tv', 'App\Http\Controllers\TvController@index')->name('tv.index');
Route::get('/tv/{tv_id}', 'App\Http\Controllers\TvController@show')->name('tv.show')->where('tv_id', '[0-9]+');

Route::get('/actors', 'App\Http\Controllers\ActorsController@index')->name('actors.index');
Route::get('/actors/page/{page?}', 'App\Http\Controllers\ActorsController@index');

Route::get('/actors/{actor_id}', 'App\Http\Controllers\ActorsController@show')->name('actors.show')->where('actor_id', '[0-9]+');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
