<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\StatisticsController;

/*
 * All route names are prefixed with 'admin.'.
 */
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::get('statistik', [DashboardController::class, 'statistik'])->name('statistik');
// Route::post('statistik', [DashboardController::class, 'statistik'])->name('statistik');

Route::group(['prefix' => 'statistik', 'as' => 'statistik.'], function () {
    Route::get('traffict', [StatisticsController::class, 'showTraffict'])->name('traffict');
    Route::post('traffict', [StatisticsController::class, 'showTraffict'])->name('traffict');

    Route::get('revenue', [StatisticsController::class, 'showRevenue'])->name('revenue');
    Route::post('revenue', [StatisticsController::class, 'showRevenue'])->name('revenue');

    Route::get('demografi', [StatisticsController::class, 'showDemografi'])->name('demografi');
    Route::post('demografi', [StatisticsController::class, 'showDemografi'])->name('demografi');

    Route::get('marketing', [StatisticsController::class, 'showMarketing'])->name('marketing');
    Route::post('marketing', [StatisticsController::class, 'showMarketing'])->name('marketing');

    Route::get('membership', [StatisticsController::class, 'showMembership'])->name('membership');
    Route::post('membership', [StatisticsController::class, 'showMembership'])->name('membership');
});