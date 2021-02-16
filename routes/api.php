<?php

use App\Http\Controllers\ChannelsController;
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

Route::middleware(['api', 'guest', 'throttle:10,1'])->prefix('channels')->name('channels.')->group(function () {
    Route::get('/', [ChannelsController::class, 'index'])->name('list');
    Route::get('/random', [ChannelsController::class, 'random'])->name('random');
});
