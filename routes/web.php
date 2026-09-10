<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormBuilderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogsheetController;
use App\Http\Controllers\MesinController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Logsheet Monitoring Mesin Produksi
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. Guest Routes (Login & Otentikasi)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ==========================================
// 2. SSO Handshake Routes (PEHA ID & PMMT)
// ==========================================
Route::get('/loginpehaid/{nik?}/{password?}', [AuthController::class, 'login_pehaid'])->name('login_pehaid');

// ==========================================
// 3. Authenticated Routes (Harus Login)
// ==========================================
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Logsheet Card Navigation & Input (Teknisi)
    Route::prefix('logsheet')->name('logsheet.')->group(function () {
        Route::get('/', [LogsheetController::class, 'index'])->name('index');
        Route::get('/category/{category}', [LogsheetController::class, 'buildings'])->name('buildings');
        Route::post('/building', [LogsheetController::class, 'storeBuilding'])->name('building.store')->middleware('role:supervisor,admin');
        Route::put('/building/{building}', [LogsheetController::class, 'updateBuilding'])->name('building.update')->middleware('role:supervisor,admin');
        Route::delete('/building/{building}', [LogsheetController::class, 'destroyBuilding'])->name('building.destroy')->middleware('role:supervisor,admin');
        Route::get('/category/{category}/building/{building}', [LogsheetController::class, 'machines'])->name('machines');
        
        // Machine CRUD Routes
        Route::post('/category/{category}/building/{building}/machine', [MesinController::class, 'store'])->name('machine.store')->middleware('role:supervisor,admin');
        Route::put('/machine/{machine}', [MesinController::class, 'update'])->name('machine.update')->middleware('role:supervisor,admin');
        Route::delete('/machine/{machine}', [MesinController::class, 'destroy'])->name('machine.destroy')->middleware('role:supervisor,admin');

        Route::get('/machine/{machine}/create', [LogsheetController::class, 'create'])->name('create');
        Route::post('/machine/{machine}', [LogsheetController::class, 'store'])->name('store');
        Route::get('/{logsheet}', [LogsheetController::class, 'show'])->name('show');
    });

    // Approval Berjenjang (Supervisor & Manager & Admin)
    Route::prefix('approval')->name('approval.')->middleware('role:supervisor,manager,admin')->group(function () {
        Route::get('/', [ApprovalController::class, 'index'])->name('index');
        Route::get('/{logsheet}', [ApprovalController::class, 'review'])->name('review');
        Route::post('/{logsheet}/approve', [ApprovalController::class, 'approve'])->name('approve');
        Route::post('/{logsheet}/reject', [ApprovalController::class, 'reject'])->name('reject');
    });

    // Form Builder Dinamis (Admin & SPV)
    Route::prefix('form-builder')->name('form-builder.')->middleware('role:supervisor,admin')->group(function () {
        Route::post('/machine/{machine}/parameter', [FormBuilderController::class, 'storeParameter'])->name('parameter.store');
        Route::put('/parameter/{parameter}', [FormBuilderController::class, 'updateParameter'])->name('parameter.update');
        Route::delete('/parameter/{parameter}', [FormBuilderController::class, 'destroyParameter'])->name('parameter.destroy');
    });

    // Laporan & Rekap Histori
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/{logsheet}/print', [LaporanController::class, 'print'])->name('print');
    });
});
