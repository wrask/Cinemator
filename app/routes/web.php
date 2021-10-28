<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\MoviesController@index')->name('movies.index');
Route::get('/movies/{movies}', 'App\Http\Controllers\MoviesController@show')->name('movies.show');
