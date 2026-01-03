<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::resource('reports', ReportController::class)->only(['index', 'create', 'store'])->middleware(['auth', 'verified']);
Route::patch('reports/{report}/update-status', [ReportController::class, 'updateStatus'])->name('reports.update-status')->middleware(['auth', 'verified']);
Route::resource('lost-found', LostFoundController::class)->only(['index', 'create', 'store', 'update'])->middleware(['auth', 'verified']);
Route::patch('lost-found/{lostFoundItem}/update-status', [LostFoundController::class, 'updateStatus'])->name('lost-found.update-status')->middleware(['auth', 'verified']);
Route::get('/traffic', [TrafficController::class, 'index'])->name('traffic.index')->middleware(['auth', 'verified']);
Route::post('/traffic', [TrafficController::class, 'store'])->name('traffic.store')->middleware(['auth', 'verified']);
Route::patch('/gates/{gate}', [TrafficController::class, 'updateGate'])->name('gates.update')->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
});

Route::resource('reports', ReportController::class)
    ->only(['index', 'create', 'store', 'destroy']) // Tambahkan destroy
    ->middleware(['auth', 'verified']);

Route::resource('lost-found', LostFoundController::class)
    ->only(['index', 'create', 'store', 'update', 'destroy']) // Tambahkan destroy
    ->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
