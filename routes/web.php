<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\FormBuilderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogsheetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Logsheet Monitoring Mesin Produksi
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/switch-role', [HomeController::class, 'switchRole'])->name('switch-role');

// Logsheet Card Navigation & Input (Teknisi)
Route::prefix('logsheet')->name('logsheet.')->group(function () {
    Route::get('/', [LogsheetController::class, 'index'])->name('index');
    Route::get('/category/{category}', [LogsheetController::class, 'buildings'])->name('buildings');
    Route::get('/category/{category}/building/{building}', [LogsheetController::class, 'machines'])->name('machines');
    Route::get('/machine/{machine}/create', [LogsheetController::class, 'create'])->name('create');
    Route::post('/machine/{machine}', [LogsheetController::class, 'store'])->name('store');
    Route::get('/{logsheet}', [LogsheetController::class, 'show'])->name('show');
});

// Approval Berjenjang (Supervisor & Manager)
Route::prefix('approval')->name('approval.')->group(function () {
    Route::get('/', [ApprovalController::class, 'index'])->name('index');
    Route::get('/{logsheet}', [ApprovalController::class, 'review'])->name('review');
    Route::post('/{logsheet}/approve', [ApprovalController::class, 'approve'])->name('approve');
    Route::post('/{logsheet}/reject', [ApprovalController::class, 'reject'])->name('reject');
});

// Form Builder Dinamis (Admin & SPV)
Route::prefix('form-builder')->name('form-builder.')->group(function () {
    Route::get('/', [FormBuilderController::class, 'index'])->name('index');
    Route::get('/machine/{machine}', [FormBuilderController::class, 'edit'])->name('edit');
    Route::post('/machine/{machine}/parameter', [FormBuilderController::class, 'storeParameter'])->name('parameter.store');
    Route::put('/parameter/{parameter}', [FormBuilderController::class, 'updateParameter'])->name('parameter.update');
    Route::delete('/parameter/{parameter}', [FormBuilderController::class, 'destroyParameter'])->name('parameter.destroy');
});

// Laporan & Rekap Histori
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
    Route::get('/{logsheet}/print', [LaporanController::class, 'print'])->name('print');
});
