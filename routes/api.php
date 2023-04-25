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



Route::middleware('auth:sanctum')->group(function(){
    Route::controller(UserController::class)->group(function(){
        Route::get('auth-users', 'getAuthUser');
        Route::put('users', 'update');
    });

    Route::controller(SongController::class)->group(function(){
        Route::get('songs', 'index');
        Route::post('songs', 'store');
        Route::delete('songs/{id}', 'destroy');
    });

    Route::controller(VideoController::class)->group(function(){
        Route::get('videos', 'index');
        Route::post('videos', 'store');
        Route::delete('videos/{id}', 'destroy');
    });

    Route::controller(PostController::class)->group(function(){
        Route::get('posts', 'index');
        Route::get('posts-by-user-id', 'postsByUserId');
        Route::get('posts/{id}', 'show');
        Route::post('posts', 'store');
        Route::put('posts/{id}', 'update');
        Route::delete('posts/{id}', 'destroy');
    });

});

