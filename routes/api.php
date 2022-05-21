<?php

use App\Http\Controllers\ReservationController as ReservationController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\TripController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/reserve', [ReservationController::class, 'store'])->name('reserve');
Route::post('/trips', [TripController::class, 'index'])->name('trips');
Route::post('/seats/{trip_id}', [SeatController::class, 'index'])->name('seats');
