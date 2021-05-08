<?php

use App\Http\Controllers\ChannelsController;
use App\Http\Controllers\TagsController;
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

Route::middleware(['api', 'guest', 'throttle:20,1'])->prefix('channels')->name('channels.')->group(function () {
    Route::get('/', [ChannelsController::class, 'index'])->name('list');
    Route::get('/random', [ChannelsController::class, 'random'])->name('random');
});

Route::middleware(['api', 'guest', 'throttle:20,1'])->prefix('tags')->name('tags.')->group(function () {
    Route::get('/', [TagsController::class, 'index'])->name('list');
});
