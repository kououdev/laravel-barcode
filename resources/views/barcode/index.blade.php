<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Code128 Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .barcode-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .form-container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-container">
                    <h1 class="text-center mb-4">Barcode Code128 Generator</h1>

                    <form action="{{ route('barcode.generate') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="text" class="form-label">Text untuk Barcode:</label>
                            <textarea class="form-control @error('text') is-invalid @enderror" id="text" name="text" rows="3"
                                placeholder="Masukkan text yang akan dijadikan barcode..." required>{{ old('text', $text ?? '') }}</textarea>
                            @error('text')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">
                                Maksimal 255 karakter. Contoh: "123456789", "HELLO WORLD", dll.
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-qr-code"></i> Generate Barcode
                            </button>
                        </div>
                    </form>

                    @if (isset($barcodeImage) && isset($text))
                        <div class="barcode-container text-center">
                            <h3 class="mb-3">Generated Barcode:</h3>
                            <div class="mb-3">
                                <strong>Text:</strong> <span class="badge bg-secondary">{{ $text }}</span>
                            </div>
                            <div class="barcode-display">
                                {!! $barcodeImage !!}
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">Format: Code128</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
