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
4. Click "Download PNG" to download the barcode as an image

### 3. Supported Formats

-   **Display**: HTML format for web viewing
-   **Download**: PNG format for offline use

## API Routes

| Method | Route                       | Description                     |
| ------ | --------------------------- | ------------------------------- |
| GET    | `/barcode-code128`          | Display barcode generation form |
| POST   | `/barcode-code128`          | Generate and display barcode    |
| POST   | `/barcode-code128/download` | Download barcode as PNG         |

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
│   └── BarcodeController.php    # Main barcode logic
resources/
├── views/
│   ├── barcode/
│   │   └── index.blade.php      # Barcode form and display
│   └── welcome.blade.php        # Homepage with navigation
routes/
└── web.php                      # Application routes
```

## Configuration

### Barcode Settings

You can modify barcode generation parameters in `BarcodeController.php`:

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
-   `50`: Height in pixels

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
