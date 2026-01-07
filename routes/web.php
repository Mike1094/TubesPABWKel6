<?php
// use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\ReportController;
// use App\Http\Controllers\LostFoundController;
// use App\Http\Controllers\TrafficController;
// use App\Http\Controllers\AdminUserController;
// use Illuminate\Support\Facades\Route;

// /*
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider and all of them will
// | be assigned to the "web" middleware group. Make something great!
// |
// */

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', [DashboardController::class, 'index'])
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// // --- BAGIAN LAPORAN KERUSAKAN (REPORTS) ---
// // Menambahkan 'destroy' agar bisa menghapus laporan
// Route::resource('reports', ReportController::class)
//     ->only(['index', 'create', 'store', 'destroy'])
//     ->middleware(['auth', 'verified']);

// Route::patch('reports/{report}/update-status', [ReportController::class, 'updateStatus'])
//     ->name('reports.update-status')
//     ->middleware(['auth', 'verified']);


// // --- BAGIAN BARANG HILANG (LOST FOUND) ---
// // Menambahkan 'destroy' agar bisa menghapus barang
// Route::resource('lost-found', LostFoundController::class)
//     ->only(['index', 'create', 'store', 'update', 'destroy'])
//     ->middleware(['auth', 'verified']);

// Route::patch('lost-found/{lostFoundItem}/update-status', [LostFoundController::class, 'updateStatus'])
//     ->name('lost-found.update-status')
//     ->middleware(['auth', 'verified']);


// // --- BAGIAN TRAFFIC & GATES ---
// Route::get('/traffic', [TrafficController::class, 'index'])
//     ->name('traffic.index')
//     ->middleware(['auth', 'verified']);

// Route::post('/traffic', [TrafficController::class, 'store'])
//     ->name('traffic.store')
//     ->middleware(['auth', 'verified']);

// Route::patch('/gates/{gate}', [TrafficController::class, 'updateGate'])
//     ->name('gates.update')
//     ->middleware(['auth', 'verified']);


// // --- BAGIAN PROFILE USER ---
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


// // --- BAGIAN ADMIN USER MANAGEMENT ---
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
//     Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
//     Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
// });

// require __DIR__.'/auth.php';

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\AdminUserController; // Pastikan controller ini ada
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ROUTE AUTH (Login diperlukan)
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard Utama (Semua role bisa akses, controller yang memilah tampilan)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. FITUR BERSAMA (Laporan & Lost Found)
    // Bisa diakses Admin, Satpam, Civitas, Warga.
    // Kita gunakan middleware 'auth' saja, logika pembatasan edit/delete ada di Controller/Policy.
    Route::resource('reports', ReportController::class);
    Route::resource('lost-found', LostFoundController::class);

    // 4. KHUSUS ADMIN (Manajemen User)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', AdminUserController::class);
        // Validasi laporan juga biasanya di sini atau via method khusus
        Route::patch('/reports/{report}/validate', [ReportController::class, 'validateReport'])->name('reports.validate');
    });

    // 5. KHUSUS SATPAM & ADMIN (Manajemen Gate/Traffic)
    Route::middleware('role:admin,satpam')->group(function () {
        Route::resource('traffic', TrafficController::class);
        // Update status gate
        Route::patch('/gates/{gate}/status', [GateController::class, 'updateStatus'])->name('gates.update-status');
    });
});

require __DIR__.'/auth.php';
