<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('posts')->namespace('App\Http\Controllers\Post')->group(function(){
    Route::prefix('images')->namespace('Image')->group(function(){
        Route::post('/', StoreController::class);
    });
    Route::get('/all', IndexController::class);
    Route::get('/latest', ShowController::class);
    Route::post('/', StoreController::class);
    Route::patch('/{post}', UpdateController::class);
});
