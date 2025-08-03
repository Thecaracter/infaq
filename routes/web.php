<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

Route::middleware(['auth', 'check.role:admin,tu'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'check.role:admin'])->group(function () {
    // Tahun Ajaran
    Route::get('/admin/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('admin.tahun-ajaran.index');
    Route::post('/admin/tahun-ajaran', [TahunAjaranController::class, 'store'])->name('admin.tahun-ajaran.store');
    Route::get('/admin/tahun-ajaran/{tahunAjaran}', [TahunAjaranController::class, 'show'])->name('admin.tahun-ajaran.show');
    Route::put('/admin/tahun-ajaran/{tahunAjaran}', [TahunAjaranController::class, 'update'])->name('admin.tahun-ajaran.update');
    Route::delete('/admin/tahun-ajaran/{tahunAjaran}', [TahunAjaranController::class, 'destroy'])->name('admin.tahun-ajaran.destroy');
    Route::post('/admin/tahun-ajaran/{tahunAjaran}/toggle-active', [TahunAjaranController::class, 'toggleActive'])->name('admin.tahun-ajaran.toggle-active');

    // Kelas
    Route::get('/admin/kelas', [KelasController::class, 'index'])->name('admin.kelas.index');
    Route::post('/admin/kelas', [KelasController::class, 'store'])->name('admin.kelas.store');
    Route::get('/admin/kelas/{kelas}', [KelasController::class, 'show'])->name('admin.kelas.show');
    Route::put('/admin/kelas/{kelas}', [KelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/admin/kelas/{kelas}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');
    Route::post('/admin/kelas/{kelas}/toggle-active', [KelasController::class, 'toggleActive'])->name('admin.kelas.toggle-active');

    // Siswa
    Route::get('/admin/siswa', [SiswaController::class, 'index'])->name('admin.siswa.index');
    Route::post('/admin/siswa', [SiswaController::class, 'store'])->name('admin.siswa.store');
    Route::get('/admin/siswa/{siswa}', [SiswaController::class, 'show'])->name('admin.siswa.show');
    Route::put('/admin/siswa/{siswa}', [SiswaController::class, 'update'])->name('admin.siswa.update');
    Route::delete('/admin/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('admin.siswa.destroy');
    Route::post('/admin/siswa/{siswa}/toggle-active', [SiswaController::class, 'toggleActive'])->name('admin.siswa.toggle-active');

    // User Management
    Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::post('/admin/user', [UserController::class, 'store'])->name('admin.user.store');
    Route::get('/admin/user/{user}', [UserController::class, 'show'])->name('admin.user.show');
    Route::put('/admin/user/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/user/{user}', [UserController::class, 'destroy'])->name('admin.user.destroy');
    Route::post('/admin/user/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('admin.user.toggle-active');
    Route::post('/admin/user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.user.reset-password');
});