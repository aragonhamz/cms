<?php
/**
 * Local configuration loader. Copy .env.example to .env and set real values there.
 * The .env file is intentionally ignored by Git and must never be publicly served.
 */
function app_env(string $key, $default = null) {
    static $values = null;

    if ($values === null) {
        $values = [];
        $envFile = __DIR__ . DIRECTORY_SEPARATOR . '.env';
        if (is_readable($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$name, $value] = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                    $value = substr($value, 1, -1);
                }
                $values[$name] = $value;
            }
        }
    }

    $systemValue = getenv($key);
    return $systemValue !== false ? $systemValue : ($values[$key] ?? $default);
}

define('DB_HOST', app_env('DB_HOST', 'localhost'));
define('DB_NAME', app_env('DB_NAME', 'ecom'));
define('DB_USERNAME', app_env('DB_USERNAME', 'root'));
define('DB_PASSWORD', app_env('DB_PASSWORD', ''));
define('APP_URL', rtrim(app_env('APP_URL', 'http://localhost/cms'), '/'));
define('SMTP_HOST', app_env('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', (int) app_env('SMTP_PORT', '587'));
define('SMTP_USERNAME', app_env('SMTP_USERNAME', ''));
define('SMTP_PASSWORD', app_env('SMTP_PASSWORD', ''));
define('SMTP_ENCRYPTION', app_env('SMTP_ENCRYPTION', 'tls'));
define('APP_ENCRYPTION_KEY', app_env('APP_ENCRYPTION_KEY', ''));
define('APP_ENCRYPTION_IV', app_env('APP_ENCRYPTION_IV', ''));
