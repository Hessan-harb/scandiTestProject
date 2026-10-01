<?php

declare(strict_types=1);

namespace App\Router;

final class Router
{
    /** @var array<string, array<string, callable|array{0: class-string, 1: string}>> */
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->register('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->register('POST', $path, $handler);
    }

    public function options(string $path, callable|array $handler): void
    {
        $this->register('OPTIONS', $path, $handler);
    }

    public function dispatch(string $method, string $uri): string
    {
        $path = $this->normalize($uri);
        $handler = $this->routes[strtoupper($method)][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);

            return json_encode(['errors' => [['message' => 'Route not found: ' . $path]]], JSON_THROW_ON_ERROR);
        }

        return (string) call_user_func($handler);
    }

    private function register(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][$this->normalize($path)] = $handler;
    }

    /**
     * Strips the sub-directory the app may be deployed in, so that both
     * https://host/graphql and https://host/app/public/graphql resolve.
     */
    private function normalize(string $uri): string
    {
        $base = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
        $path = (string) parse_url($uri, PHP_URL_PATH);

        if ($base !== '' && $base !== '/' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }

        return '/' . trim($path, '/');
    }
}
