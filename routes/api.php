<?php

use App\Http\Controllers\ReservationController;

Route::get('/reservations', [ReservationController::class, 'index']);
Route::post('/reservations', [ReservationController::class, 'apiStore']);
Route::put('/reservations/{id}', [ReservationController::class, 'apiUpdate']);
#Route::delete('/reservations/{id}', [ReservationController::class, 'apiDestroy']);
Route::delete('/api/reservations/{id}', [ReservationController::class, 'destroy']);
