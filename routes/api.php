<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DomicileController;



Route::prefix('domicile')->name('api.domicile.')->group(function () {
    Route::get('/provinces', [DomicileController::class, 'provinces'])->name('provinces');
    Route::get('/regencies', [DomicileController::class, 'regencies'])->name('regencies');
    Route::get('/districts', [DomicileController::class, 'districts'])->name('districts');
    Route::get('/villages', [DomicileController::class, 'villages'])->name('villages');
    Route::get('/village-detail', [DomicileController::class,'village_detail'])->name('village-detail');
});
