<?php

use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\SongController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('users/{id}', [UserController::class, 'getUserById']);
Route::get('songs/{userId}', [SongController::class, 'getSongsByUserId']);
Route::get('videos/{userId}', [VideoController::class, 'getVideosByUserId']);
Route::get('posts/{userId}', [PostController::class, 'getPostsByUserId']);
Route::get('posts-by-id/{id}', [PostController::class, 'show']);
Route::get('posts', 'index');

Route::middleware('auth:sanctum')->group(function(){
    Route::controller(UserController::class)->group(function(){
        Route::get('auth-users', 'getAuthUser');
        Route::put('users', 'update');
    });

    Route::controller(SongController::class)->group(function(){
        Route::post('songs', 'store');
        Route::delete('songs/{id}', 'destroy');
    });

    Route::controller(VideoController::class)->group(function(){
        Route::post('videos', 'store');
        Route::delete('videos/{id}', 'destroy');
    });

    Route::controller(PostController::class)->group(function(){
        Route::post('posts', 'store');
        Route::put('posts/{id}', 'update');
        Route::delete('posts/{id}', 'destroy');
    });

});

