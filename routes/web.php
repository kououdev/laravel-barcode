<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeController;

Route::get('/', function () {
    return view('welcome');
});

// Barcode routes
Route::get('/barcode-code128', [BarcodeController::class, 'index'])->name('barcode.index');
Route::post('/barcode-code128', [BarcodeController::class, 'generateCode128'])->name('barcode.generate');
