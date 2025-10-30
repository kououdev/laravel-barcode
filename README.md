# Laravel Barcode Generator

A Laravel application for generating Code128 barcodes with HTML display and PNG download functionality.

## Features

-   🔢 **Code128 Barcode Generation**: Generate barcodes using the Code128 standard
-   🎨 **HTML Display**: View barcodes directly in the browser
-   📥 **PNG Download**: Download generated barcodes as PNG images
-   ✅ **Input Validation**: Validates text input (max 255 characters)
-   📱 **Responsive Design**: Bootstrap 5 responsive interface
-   🌙 **Dark Mode Support**: Beautiful UI with dark/light theme support

## Requirements

-   **PHP**: >= 8.1
-   **Laravel**: >= 10.x
-   **Composer**: Latest version
-   **GD Extension**: Required for PNG image generation

### PHP Extensions Required

The application requires the following PHP extensions:

-   `gd` (for image processing)
-   `mbstring` (for string handling)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd laravel-barcode
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Install Barcode Package

The application uses the `milon/barcode` package for barcode generation:

```bash
composer require milon/barcode
```

### 4. Environment Setup

Copy the environment file and generate application key:

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Database (Optional)

If you plan to use database features, configure your database in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_barcode
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Then run migrations:

```bash
php artisan migrate
```

### 6. Install Frontend Dependencies (Optional)

If you want to customize the frontend:

```bash
npm install
npm run build
```

## Usage

### 1. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### 2. Generate Barcodes

1. Navigate to `/barcode-code128` or click "Barcode Generator" on the homepage
2. Enter text in the textarea (max 255 characters)
3. Click "Generate Barcode" to display the barcode
4. Use any of the download options:
    - **Download PNG + Save**: Downloads PNG and automatically saves PNG + JPG to server
    - **Save Both (PNG+JPG)**: Saves files to server only (via AJAX)
    - **Direct Download Link**: Use the direct URL pattern for downloads

### 3. Download Methods

#### Method 1: Form-Based Download

-   Generate barcode via the web interface
-   Click download buttons for instant download + server save

#### Method 2: Direct URL Download

-   Use pattern: `/barcode-download/{your-text-here}`
-   Example: `http://localhost:8000/barcode-download/RESI123456789`
-   Automatically downloads PNG and saves both PNG + JPG to server

#### Method 3: AJAX Save Only

-   Use the "Save Both" button to save files without downloading
-   Returns JSON response with file locations

### 4. Supported Formats

-   **Display**: HTML format for web viewing
-   **Download**: PNG format for offline use
-   **Storage**: Both PNG and JPG formats saved to `public/barcodes/` folder

## API Routes

| Method | Route                       | Controller Method | Description                                    |
| ------ | --------------------------- | ----------------- | ---------------------------------------------- |
| GET    | `/barcode-code128`          | `index`           | Display barcode generation form                |
| POST   | `/barcode-code128`          | `generateCode128` | Generate and display barcode                   |
| POST   | `/barcode-code128/download` | `downloadPng`     | Download PNG + auto-save PNG & JPG             |
| POST   | `/barcode-code128/save`     | `saveAndDownload` | Save files to server (AJAX) with format option |
| GET    | `/barcode-download/{code}`  | Closure Route     | Direct download link + auto-save both formats  |

### Route Parameters

#### `/barcode-download/{code}`

-   **Parameter**: `code` - The text to encode as barcode
-   **Example**: `/barcode-download/HELLO123`
-   **Behavior**: Downloads PNG file and saves both PNG + JPG to `public/barcodes/`

#### POST `/barcode-code128/save`

-   **Parameters**:
    -   `text` (required): Text to encode
    -   `format` (optional): `png`, `jpg`, or `both` (default: `both`)
-   **Response**: JSON with success status and saved file paths

## Package Details

### milon/barcode Package

This application uses the `milon/barcode` package which provides:

-   **Version**: ^12.0
-   **Features**: Multiple barcode formats including Code128
-   **Output Formats**: HTML, PNG, JPG, SVG
-   **Documentation**: [GitHub Repository](https://github.com/milon/barcode)

### Installation Command

```bash
composer require milon/barcode
```

## File Structure

```
app/
├── Http/Controllers/
│   └── BarcodeController.php    # Main barcode logic with download methods
resources/
├── views/
│   ├── barcode/
│   │   └── index.blade.php      # Barcode form, display & download options
│   └── welcome.blade.php        # Homepage with navigation
routes/
└── web.php                      # Application routes (form + direct download)
public/
└── barcodes/                    # Auto-generated barcode storage folder
    ├── barcode_HELLO123_2025-10-30_14-30-15.png
    └── barcode_HELLO123_2025-10-30_14-30-15.jpg
```

## Configuration

### Barcode Settings

You can modify barcode generation parameters in `BarcodeController.php` or the direct route:

```php
// HTML barcode generation
$barcodeImage = $barcode->getBarcodeHTML($text, 'C128', 2, 50);

// PNG barcode generation
$barcodeImage = $barcode->getBarcodePNG($text, 'C128', 2, 50);
```

Parameters:

-   `$text`: The text to encode
-   `'C128'`: Barcode type (Code128)
-   `2`: Width multiplier
-   `50`: Height in pixels (60 for downloads)

## Usage Examples

### 1. Direct Download URLs

```bash
# Download barcode for text "HELLO123"
GET http://localhost:8000/barcode-download/HELLO123

# Download barcode for tracking number
GET http://localhost:8000/barcode-download/RESI123456789

# Download barcode with special characters (auto-cleaned for filename)
GET http://localhost:8000/barcode-download/ORDER-2025-001
```

### 2. AJAX Save Request

```javascript
// Save barcode files to server without download
fetch("/barcode-code128/save", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
    body: JSON.stringify({
        text: "HELLO123",
        format: "both", // 'png', 'jpg', or 'both'
    }),
})
    .then((response) => response.json())
    .then((data) => {
        console.log("Saved files:", data.files);
        // Example response:
        // {
        //   "success": true,
        //   "message": "Barcode saved successfully",
        //   "files": ["barcodes/barcode_HELLO123_2025-10-30_14-30-15.png",
        //             "barcodes/barcode_HELLO123_2025-10-30_14-30-15.jpg"],
        //   "download_url": "http://localhost:8000/barcode-download/HELLO123"
        // }
    });
```

### 3. Programmatic File Generation

```php
// Generate and save barcode files programmatically
$barcode = new \Milon\Barcode\DNS1D();
$text = 'RESI123456789';
$pngData = base64_decode($barcode->getBarcodePNG($text, 'C128', 2, 60));

// Save PNG
file_put_contents(public_path('barcodes/my_barcode.png'), $pngData);

// Convert and save as JPG
$image = imagecreatefromstring($pngData);
imagejpeg($image, public_path('barcodes/my_barcode.jpg'), 90);
imagedestroy($image);
```

### 4. File Storage Structure

After generating barcodes, files are automatically saved with this naming convention:

```
public/barcodes/barcode_{sanitized_text}_{timestamp}.{extension}

Examples:
- barcode_HELLO123_2025-10-30_14-30-15.png
- barcode_RESI123456789_2025-10-30_14-30-15.jpg
- barcode_ORDER_2025_001_2025-10-30_14-30-15.png
```

## Troubleshooting

### Common Issues

1. **GD Extension Missing**

    ```bash
    # Ubuntu/Debian
    sudo apt-get install php-gd

    # CentOS/RHEL
    sudo yum install php-gd

    # macOS (Homebrew)
    brew install php-gd
    ```

2. **Composer Dependencies**

    ```bash
    composer install --no-dev --optimize-autoloader
    ```

3. **Permission Issues**
    ```bash
    chmod -R 755 storage bootstrap/cache
    ```

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

```bash
./vendor/bin/pint
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and code style checks
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects.

For more information about Laravel, visit [https://laravel.com](https://laravel.com).
