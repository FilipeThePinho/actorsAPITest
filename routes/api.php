<?php

use \App\Http\Controllers\ActorController as ActorControllerAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::resource('genres', GenreController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

//Movies
Route::resource('movies', MovieController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

//Actors
Route::get('actors/{uuid}/starred_movies', [ActorControllerAlias::class, 'starred_movies'])->name('actors.starred_movies');
Route::get('actors/{uuid}/genre_list', [ActorControllerAlias::class, 'genre_list'])->name('actors.genre_list'); //EDD-00005.2
Route::get('actors/{uuid}/favourite_genre', [ActorControllerAlias::class, 'favourite_genre'])->name('actors.favourite_genre'); //EDD-00005.1
Route::resource('actors', ActorController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

//Roles
Route::resource('roles', RoleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
