<?php

use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RegencyController;
use App\Http\Controllers\ZoningController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ZoningController::class, 'index']);
Route::prefix('province')->name('province.')->group(function () {
    Route::get('/', [ProvinceController::class, 'index'])->name('index');
    Route::get('/province-population-report', [ProvinceController::class, 'populationReport'])->name('population-report');
    Route::post('/', [ProvinceController::class, 'store'])->name('store');
    Route::get('/{id?}', [ProvinceController::class, 'show'])->name('show');
    Route::patch('/{id?}', [ProvinceController::class, 'update'])->name('update');
    Route::delete('/{id?}', [ProvinceController::class, 'destroy'])->name('destroy');
});
Route::prefix('regency')->name('regency.')->group(function () {
    Route::get('/', [RegencyController::class, 'index'])->name('index');
    Route::get('/regency-population-report', [RegencyController::class, 'populationReport'])->name('population-report');
    Route::post('/', [RegencyController::class, 'store'])->name('store');
    Route::get('/{id?}', [RegencyController::class, 'show'])->name('show');
    Route::patch('/{id?}', [RegencyController::class, 'update'])->name('update');
    Route::delete('/{id?}', [RegencyController::class, 'destroy'])->name('destroy');
});
