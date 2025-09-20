<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;

Route::get('/', [HomeController::class, 'index'])->name('home');
#Route::get('/', function () {
#    return view('welcome');
#});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('/users', [UserController::class, 'index'])->middleware(['auth'])->name('users.index');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::resource('users', UserController::class);
Route::resource('customers', CustomerController::class)->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
});

Route::get('/reservations', [ReservationController::class, 'view'])->name('reservations.view');
#Route::resource('reservations', ReservationController::class)->except(['index']);
Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::prefix('api')->group(function() {
    Route::get('reservations', [ReservationController::class, 'apiIndex']);
    Route::post('reservations', [ReservationController::class, 'apiStore']);
    Route::put('reservations/{id}', [ReservationController::class, 'apiUpdate']);
    Route::delete('reservations/{id}', [ReservationController::class, 'apiDestroy']);
});

require __DIR__.'/auth.php';
