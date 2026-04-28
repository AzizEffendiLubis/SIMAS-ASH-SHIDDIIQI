<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Guest ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/',               fn() => redirect()->route('login'));
    Route::get('/login',          [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',         [AuthController::class, 'login'])->name('login.post');
    Route::get('/register',       [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',      [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password',[AuthController::class, 'forgotPassword'])->name('password.request');
});

// ── Auth ──────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('menu:dashboard')
        ->name('dashboard');

    // ── Daftar Aset ───────────────────────────────────────────────────────────
    Route::middleware('menu:daftar_aset')->group(function () {
        Route::get('/assets',               [AssetController::class, 'index'])->name('assets.index');
        Route::get('/assets/create',        [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets',              [AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{asset}',       [AssetController::class, 'show'])->name('assets.show');
        Route::get('/assets/{asset}/edit',  [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/assets/{asset}',       [AssetController::class, 'update'])->name('assets.update');
        Route::delete('/assets/{asset}',    [AssetController::class, 'destroy'])->name('assets.destroy');
    });

    // ── Perbaikan Aset ────────────────────────────────────────────────────────
    Route::middleware('menu:perbaikan_aset')->group(function () {
        Route::get('/repairs',              [RepairController::class, 'index'])->name('repairs.index');
        Route::get('/repairs/create',       [RepairController::class, 'create'])->name('repairs.create');
        Route::post('/repairs',             [RepairController::class, 'store'])->name('repairs.store');
        Route::get('/repairs/{repair}',     [RepairController::class, 'show'])->name('repairs.show');
        Route::get('/repairs/{repair}/edit',[RepairController::class, 'edit'])->name('repairs.edit');
        Route::put('/repairs/{repair}',     [RepairController::class, 'update'])->name('repairs.update');
        Route::delete('/repairs/{repair}',  [RepairController::class, 'destroy'])->name('repairs.destroy');
    });

    // ── Pengadaan Aset (Admin Unit — buat pengajuan) ──────────────────────────
    Route::middleware('menu:pengadaan_aset')->group(function () {
        Route::get('/procurements',              [ProcurementController::class, 'index'])->name('procurements.index');
        Route::get('/procurements/create',       [ProcurementController::class, 'create'])->name('procurements.create');
        Route::post('/procurements',             [ProcurementController::class, 'store'])->name('procurements.store');
        Route::get('/procurements/{procurement}',[ProcurementController::class, 'show'])->name('procurements.show');
        Route::delete('/procurements/{procurement}',[ProcurementController::class, 'destroy'])->name('procurements.destroy');
    });

    // ── Persetujuan Pengadaan (Super Admin & Kepala Yayasan) ──────────────────
    // Route BERBEDA dari procurements — halaman & logic khusus approval
    Route::middleware('menu:persetujuan_pengadaan')->group(function () {
        Route::get('/approvals',                      [ProcurementController::class, 'approvalIndex'])->name('approvals.index');
        Route::get('/approvals/{procurement}',        [ProcurementController::class, 'show'])->name('approvals.show');
        Route::post('/approvals/{procurement}/action',[ProcurementController::class, 'approve'])->name('approvals.approve');
    });

    // ── Manajemen Pengguna (Super Admin) ──────────────────────────────────────
    Route::middleware('menu:manajemen_pengguna')->group(function () {
        Route::get('/users',            [UserController::class, 'index'])->name('users.index');
        Route::post('/users',           [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',     [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',  [UserController::class, 'destroy'])->name('users.destroy');
    });
});
