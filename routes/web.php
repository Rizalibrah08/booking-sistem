<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\PeminjamanController as GuruPeminjamanController;
use Illuminate\Support\Facades\Route;

// ══════════════════════════════════════════════════════════════════
// Guest Routes
// ══════════════════════════════════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get('/',      fn () => redirect()->route('login'));
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ══════════════════════════════════════════════════════════════════
// Admin & Kepsek Routes (prefix: /admin)
// ══════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin,kepsek'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Asset CRUD
        Route::resource('assets', AssetController::class)
            ->except(['show']);

        // Peminjaman Management
        Route::get('/peminjamans', [AdminPeminjamanController::class, 'index'])
            ->name('peminjamans.index');

        Route::get('/peminjamans/create', [AdminPeminjamanController::class, 'create'])
            ->name('peminjamans.create');

        Route::post('/peminjamans', [AdminPeminjamanController::class, 'store'])
            ->name('peminjamans.store');

        Route::get('/peminjamans/{peminjaman}', [AdminPeminjamanController::class, 'show'])
            ->name('peminjamans.show');

        Route::put('/peminjamans/{peminjaman}/cancel', [AdminPeminjamanController::class, 'cancel'])
            ->name('peminjamans.cancel');
    });

// ══════════════════════════════════════════════════════════════════
// Guru & Staf Routes
// ══════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:guru,staf'])
    ->name('guru.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])
            ->name('dashboard');

        // Peminjaman
        Route::get('/peminjamans', [GuruPeminjamanController::class, 'index'])
            ->name('peminjamans.index');

        Route::get('/peminjamans/create', [GuruPeminjamanController::class, 'create'])
            ->name('peminjamans.create');

        Route::post('/peminjamans', [GuruPeminjamanController::class, 'store'])
            ->name('peminjamans.store');

        Route::get('/peminjamans/{peminjaman}', [GuruPeminjamanController::class, 'show'])
            ->name('peminjamans.show');
    });
