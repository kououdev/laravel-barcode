<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;

class BarcodeController extends Controller
{
    public function index()
    {
        return view('barcode.index');
    }

    public function generateCode128(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255'
        ]);

        $text = $request->input('text');

        // Generate Code128 barcode
        $barcode = new DNS1D();
        $barcodeImage = $barcode->getBarcodeHTML($text, 'C128', 2, 50);

        return view('barcode.index', [
            'text' => $text,
            'barcodeImage' => $barcodeImage
        ]);
    }

    public function downloadPng(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255'
        ]);

        $text = $request->input('text');

        // Generate Code128 barcode as PNG
        $barcode = new DNS1D();
        $pngData = base64_decode($barcode->getBarcodePNG($text, 'C128', 2, 60));

        // Create filename
        $filename = 'barcode_' . preg_replace('/[^a-zA-Z0-9]/', '_', $text) . '_' . date('Y-m-d_H-i-s');

        // Save to public/barcodes folder
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
    }

    public function saveAndDownload(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'format' => 'sometimes|string|in:png,jpg,both'
        ]);

        $text = $request->input('text');
        $format = $request->input('format', 'both');

        // Generate Code128 barcode as PNG
        $barcode = new DNS1D();
        $pngData = base64_decode($barcode->getBarcodePNG($text, 'C128', 2, 60));

        // Create filename
        $filename = 'barcode_' . preg_replace('/[^a-zA-Z0-9]/', '_', $text) . '_' . date('Y-m-d_H-i-s');
        $savedFiles = [];

        // Save PNG if requested
        if (in_array($format, ['png', 'both'])) {
            file_put_contents(public_path('barcodes/' . $filename . '.png'), $pngData);
            $savedFiles[] = 'barcodes/' . $filename . '.png';
        }

        // Save JPG if requested
        if (in_array($format, ['jpg', 'both'])) {
            $image = imagecreatefromstring($pngData);
            if ($image !== false) {
                imagejpeg($image, public_path('barcodes/' . $filename . '.jpg'), 90);
                imagedestroy($image);
                $savedFiles[] = 'barcodes/' . $filename . '.jpg';
            }
        }

        // Return JSON response with save status
        return response()->json([
            'success' => true,
            'message' => 'Barcode saved successfully',
            'files' => $savedFiles,
            'download_url' => route('barcode.download.direct', ['code' => $text])
        ]);
    }
}
