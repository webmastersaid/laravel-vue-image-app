<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::namespace('App\Http\Controllers')->group(function () {
    Route::get('/', IndexController::class)->name('index');
    Route::controller(PostController::class)->prefix('posts')->group(function () {
        Route::get('/', 'index')->name('post.index');
    });
});
