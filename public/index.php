<?php

declare(strict_types=1);

/*
 * Application bootstrap. This is the only procedural part of the application:
 * autoloading, configuration, CORS headers and route registration.
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Config;
use App\GraphQL\Controller\GraphQLController;
use App\Router\Router;

Config::load(dirname(__DIR__));

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

$router = new Router();

$router->options('/graphql', static fn (): string => '');
$router->post('/graphql', [GraphQLController::class, 'handle']);
$router->get('/graphql', static fn (): string => json_encode(['message' => 'Send GraphQL queries with POST.']));
$router->get('/', static fn (): string => json_encode(['status' => 'ok', 'endpoint' => '/graphql']));

echo $router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH)
);
