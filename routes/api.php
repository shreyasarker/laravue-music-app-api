<?php

use App\Http\Controllers\API\SongController;
use App\Http\Controllers\API\UserController;
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
        Route::post('songs', 'store');
        Route::delete('songs', 'destroy');
    });
});

