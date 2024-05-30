<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ChoirController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\TabernacleController;

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
Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    //Users routes
    Route::post("/logout", [AuthController::class, 'logout']);
    Route::get('user', [UserController::class, 'show']);
    Route::put('user', [UserController::class, 'update']);

    //Resources routes
    Route::apiResource('tabernacles', TabernacleController::class);
    Route::apiResource('choirs', ChoirController::class);
    Route::apiResource('songs', SongController::class);
    Route::apiResource('comments', CommentController::class)->only(['store', 'update', 'destroy']);

    //Faorites routes
    Route::post('songs/{song}/favorite', [FavoriteController::class, 'store']);
    Route::delete('songs/{song}/favorite', [FavoriteController::class, 'destroy']);

    //Playlist routes
    Route::apiResource('playlists', PlaylistController::class);
    Route::post('playlists/{playlist}/songs', [PlaylistController::class, 'addSong']);
    Route::delete('playlists/{playlist}/songs/{song}', [PlaylistController::class, 'removeSong']);
});