<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

                            <!-- Enhanced Download Options -->
                            <div class="mt-4">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-3">Download Options:</h5>

                                        <!-- Direct Download with Save -->
                                        <div class="d-flex gap-2 justify-content-center flex-wrap mb-3">
                                            <form action="{{ route('barcode.download') }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="text" value="{{ $text }}">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bi bi-download"></i> Download PNG + Save
                                                </button>
                                            </form>

                                            <form action="{{ route('barcode.save') }}" method="POST"
                                                style="display: inline;" class="save-form">
                                                @csrf
                                                <input type="hidden" name="text" value="{{ $text }}">
                                                <input type="hidden" name="format" value="both">
                                                <button type="submit" class="btn btn-info">
                                                    <i class="bi bi-save"></i> Save Both (PNG+JPG)
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Direct Download Link -->
                                        <div class="mb-3">
                                            <a href="{{ route('barcode.download.direct', ['code' => $text]) }}"
                                                class="btn btn-outline-primary" target="_blank">
                                                <i class="bi bi-link-45deg"></i> Direct Download Link
                                            </a>
                                        </div>

                                        <!-- Save Status -->
                                        <div id="saveStatus" class="mt-3" style="display: none;">
                                            <div class="alert alert-success">
                                                <strong>Files saved successfully!</strong>
                                                <div id="savedFiles"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle save form AJAX submission
        document.querySelectorAll('.save-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const button = this.querySelector('button');
                const originalText = button.innerHTML;

                button.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
                button.disabled = true;

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.getAttribute('content') || formData.get('_token')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const statusDiv = document.getElementById('saveStatus');
                            const filesDiv = document.getElementById('savedFiles');

                            filesDiv.innerHTML = 'Files: ' + data.files.join(', ');
                            statusDiv.style.display = 'block';

                            setTimeout(() => {
                                statusDiv.style.display = 'none';
                            }, 5000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error saving files');
                    })
                    .finally(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    });
            });
        });
    </script>
</body>

</html>
