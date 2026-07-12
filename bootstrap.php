<?php

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if ($value !== '' && (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        )) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;

        if (function_exists('putenv')) {
            @putenv("{$key}={$value}");
        }
    }
}

loadEnv(__DIR__ . '/.env');

$vendorAutoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($vendorAutoload)) {
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Setup required</title></head><body style="font-family:Arial,sans-serif;max-width:640px;margin:40px auto;padding:20px;">';
    echo '<h1>Composer dependencies missing</h1>';
    echo '<p>The <code>vendor/</code> folder is not installed. PHPMailer and other libraries are required for email, PDF, and QR features.</p>';
    echo '<h2>cPanel fix</h2><ol>';
    echo '<li>Open <strong>Terminal</strong> in cPanel (or upload <code>vendor/</code> from your PC).</li>';
    echo '<li>Run: <code>cd ~/path/to/osa &amp;&amp; composer install --no-dev</code></li>';
    echo '<li>Or on your PC run <code>install.bat</code>, then upload the entire <code>vendor/</code> folder via File Manager.</li>';
    echo '</ol></body></html>';
    exit;
}
require_once $vendorAutoload;

/**
 * Translate UI string for the active locale (ta|en).
 */
function __(string $key, ?string $default = null): string
{
    return \App\Helpers\Lang::get($key, $default);
}

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $file = __DIR__ . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});
