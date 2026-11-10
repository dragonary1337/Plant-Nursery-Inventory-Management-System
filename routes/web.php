<?php

use App\Http\Controllers\PlantReportController;
use App\Http\Controllers\PlantsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/plants');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => redirect()->route('plants.index'))->name('dashboard');

    Route::get('/plants/reports', [PlantReportController::class, 'index'])->name('plants.reports.index');
    Route::get('/plants/reports/pdf', [PlantReportController::class, 'pdf'])->name('plants.reports.pdf');
    Route::get('/plants/reports/excel', [PlantReportController::class, 'excel'])->name('plants.reports.excel');
    Route::resource('plants', PlantsController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
