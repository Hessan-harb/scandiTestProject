<?php

declare(strict_types=1);

namespace App\Config;

use Dotenv\Dotenv;

final class Config
{
    /** @var array<string, mixed> */
    private static array $values = [];

    public static function load(string $basePath): void
    {
        if (file_exists($basePath . '/.env')) {
            Dotenv::createImmutable($basePath)->safeLoad();
        }

        self::$values = [
            'db.host' => self::env('DB_HOST', '127.0.0.1'),
            'db.port' => self::env('DB_PORT', '3306'),
            'db.name' => self::env('DB_NAME', 'scandiweb'),
            'db.user' => self::env('DB_USER', 'root'),
            'db.password' => self::env('DB_PASSWORD', ''),
            'app.debug' => filter_var(self::env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOL),
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$values[$key] ?? $default;
    }

    private static function env(string $key, string $default): string
    {
        $value = $_ENV[$key] ?? getenv($key);

        return $value === false || $value === null || $value === '' ? $default : (string) $value;
    }
}
