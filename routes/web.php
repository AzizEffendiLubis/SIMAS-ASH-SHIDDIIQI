<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterDataController; 
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    // Redirect root ke halaman login
    Route::get('/', fn() => redirect()->route('login'));

    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // TIDAK ADA: register, password.request
});

Route::middleware('auth')->group(function () {

    // ── Logout ────────────────────────────────────────────────────────────────
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Ganti Password Wajib (login pertama / setelah reset admin) ────────────
    // Tidak dibungkus middleware menu — harus bisa diakses semua role
    // sebelum melanjutkan ke halaman manapun.
    Route::get('/password/change',  [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change.post');

    // ── Dashboard ─────────────────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('menu:dashboard')
        ->name('dashboard');

    // ── Daftar Aset ───────────────────────────────────────────────────────────
    // Enforcement hak tambah/edit ada di AssetController (canEditAset / abort 403).
    // Kepala Yayasan bisa akses index & show, tapi tidak bisa create/edit/store/update.
    Route::middleware('menu:daftar_aset')->group(function () {
        Route::get('/assets',              [AssetController::class, 'index'])->name('assets.index');
        Route::get('/assets/create',       [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets',             [AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{asset}',      [AssetController::class, 'show'])->name('assets.show');
        Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/assets/{asset}',      [AssetController::class, 'update'])->name('assets.update');

        // Route destroy ada agar tidak 404; controller selalu abort(403).
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
    });

    // ── Perbaikan Aset ────────────────────────────────────────────────────────
    // scopeForUser() di Repair model mengatur data yang terlihat per role.
    // Enforcement hak edit ada di RepairController (abort 403).
    Route::middleware('menu:perbaikan_aset')->group(function () {
        Route::get('/repairs',               [RepairController::class, 'index'])->name('repairs.index');
        Route::get('/repairs/create',        [RepairController::class, 'create'])->name('repairs.create');
        Route::post('/repairs',              [RepairController::class, 'store'])->name('repairs.store');
        Route::get('/repairs/{repair}',      [RepairController::class, 'show'])->name('repairs.show');
        Route::get('/repairs/{repair}/edit', [RepairController::class, 'edit'])->name('repairs.edit');
        Route::put('/repairs/{repair}',      [RepairController::class, 'update'])->name('repairs.update');

        // Route destroy ada agar tidak 404; controller selalu abort(403).
        Route::delete('/repairs/{repair}', [RepairController::class, 'destroy'])->name('repairs.destroy');
    });

    // ── Manajemen Pengguna ────────────────────────────────────────────────────
    // Enforcement di UserController: abort(403) jika bukan isAdminUtama().
    Route::middleware('menu:manajemen_pengguna')->group(function () {
        Route::get('/users',                [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',         [UserController::class, 'create'])->name('users.create');  // ← ditambah
        Route::post('/users',               [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',    [UserController::class, 'edit'])->name('users.edit');       // ← ditambah
        Route::put('/users/{user}',         [UserController::class, 'update'])->name('users.update');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

        // Route destroy ada agar tidak 404; controller selalu abort(403).
        // Untuk nonaktifkan: PUT /users/{user} dengan status = 'nonaktif'.
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // ── Log Aktivitas ─────────────────────────────────────────────────────────
    // Enforcement di controller: hanya Admin Utama & Kepala Yayasan.
    Route::middleware('menu:log_aktivitas')->group(function () {
        Route::get('/activity-logs',               [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // {activityLog} harus cocok dengan nama parameter di show(ActivityLog $activityLog)
        // agar Laravel route model binding bekerja dengan benar
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    });

    // ── Master Data ───────────────────────────────────────────────────────────
    // Enforcement di controller: abort(403) jika bukan isAdminUtama().
    // Satuan (units_satuan) TIDAK ada route CRUD — bersifat tetap, diisi via seeder.
    Route::middleware('menu:master_data')->group(function () {

        // Satu halaman index menampilkan semua master data sekaligus
        Route::get('/masterdata', [MasterDataController::class, 'index'])->name('masterdata.index');

        // Unit kerja
        // Catatan: kode_unit dikunci setelah ada (tidak bisa diubah via updateUnit)
        Route::post('/masterdata/units',          [MasterDataController::class, 'storeUnit'])->name('masterdata.units.store');
        Route::put('/masterdata/units/{unit}',    [MasterDataController::class, 'updateUnit'])->name('masterdata.units.update');
        Route::delete('/masterdata/units/{unit}', [MasterDataController::class, 'destroyUnit'])->name('masterdata.units.destroy');

        // Sumber dana — dinamis
        Route::post('/masterdata/funding-sources',                  [MasterDataController::class, 'storeFundingSource'])->name('masterdata.funding.store');
        Route::put('/masterdata/funding-sources/{fundingSource}',   [MasterDataController::class, 'updateFundingSource'])->name('masterdata.funding.update');
        Route::delete('/masterdata/funding-sources/{fundingSource}',[MasterDataController::class, 'destroyFundingSource'])->name('masterdata.funding.destroy');
    });
});