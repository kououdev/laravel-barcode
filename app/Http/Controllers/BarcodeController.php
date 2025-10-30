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
}
