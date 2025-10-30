<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeController;

Route::get('/', function () {
    return view('welcome');
});

// Barcode routes
Route::get('/barcode-code128', [BarcodeController::class, 'index'])->name('barcode.index');
Route::post('/barcode-code128', [BarcodeController::class, 'generateCode128'])->name('barcode.generate');
Route::post('/barcode-code128/download', [BarcodeController::class, 'downloadPng'])->name('barcode.download');
Route::post('/barcode-code128/save', [BarcodeController::class, 'saveAndDownload'])->name('barcode.save');

// Enhanced barcode download route
Route::get('/barcode-download/{code}', function ($code) {
    $barcode = new \Milon\Barcode\DNS1D();
    $pngData = base64_decode($barcode->getBarcodePNG($code, 'C128', 2, 60));

    // Save to public/barcodes folder
    $filename = 'barcode_' . preg_replace('/[^a-zA-Z0-9]/', '_', $code) . '_' . date('Y-m-d_H-i-s');

    // Save as PNG
    file_put_contents(public_path('barcodes/' . $filename . '.png'), $pngData);

    // Convert and save as JPG
    $image = imagecreatefromstring($pngData);
    if ($image !== false) {
        imagejpeg($image, public_path('barcodes/' . $filename . '.jpg'), 90);
        imagedestroy($image);
    }

    return response($pngData)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '.png"');
})->name('barcode.download.direct');
