<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DataExportController;
use App\Http\Controllers\AnnouncementController;

Route::get('/', function () {
    // ログインしている場合 → dashboard へ
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // 未ログインの場合 → ログインページへ
    return redirect()->route('login');
});

Route::get('/debug/headers', function (\Illuminate\Http\Request $request) {
    return response()->json($request->headers->all());
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');

Route::resource('users', UserController::class);
Route::resource('customers', CustomerController::class)->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::get('/reservations', [ReservationController::class, 'view'])->name('reservations.view');
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/staffs', [StaffController::class, 'index'])->name('staffs.index');
    Route::post('/staffs', [StaffController::class, 'store'])->name('staffs.store');
    Route::get('/staffs/create', [StaffController::class, 'create'])->name('staffs.create');
    Route::get('/staffs/{user}', [StaffController::class, 'show'])->name('staffs.show');
    Route::put('/staffs/{user}', [StaffController::class, 'update'])->name('staffs.update');
    Route::get('/staffs/{user}/edit', [StaffController::class, 'edit'])->name('staffs.edit');
    Route::delete('/staffs/{user}', [StaffController::class, 'destroy'])->name('staffs.destroy');
    Route::get('/data', [DataExportController::class, 'index'])->name('data.index');
    Route::get('/data/export/{type}/{format}', [DataExportController::class, 'export'])->name('data.export');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
});

Route::prefix('api')->group(function() {
    Route::get('reservations', [ReservationController::class, 'apiIndex']);
    Route::post('reservations', [ReservationController::class, 'apiStore']);
    Route::put('reservations/{id}', [ReservationController::class, 'apiUpdate']);
    Route::delete('reservations/{id}', [ReservationController::class, 'apiDestroy']);
});

require __DIR__.'/auth.php';
