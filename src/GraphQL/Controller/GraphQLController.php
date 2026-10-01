<?php

declare(strict_types=1);

namespace App\GraphQL\Controller;

use App\Config\Config;
use App\GraphQL\Schema\SchemaBuilder;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL;
use RuntimeException;
use Throwable;

final class GraphQLController
{
    public static function handle(): string
    {
        try {
            $schema = (new SchemaBuilder())->build();

            $rawInput = file_get_contents('php://input');

            if ($rawInput === false) {
                throw new RuntimeException('Failed to read the request body.');
            }

            /** @var array{query?: string, variables?: array<string, mixed>, operationName?: string} $input */
            $input = json_decode($rawInput, true, 512, JSON_THROW_ON_ERROR) ?? [];

            $result = GraphQL::executeQuery(
                $schema,
                $input['query'] ?? '',
                null,
                null,
                $input['variables'] ?? null,
                $input['operationName'] ?? null
            );

            $debug = Config::get('app.debug') === true
                ? DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE
                : DebugFlag::NONE;

            $output = $result->toArray($debug);
        } catch (Throwable $exception) {
            http_response_code(500);

            $output = [
                'errors' => [
                    ['message' => $exception->getMessage()],
                ],
            ];
        }

        return json_encode($output, JSON_THROW_ON_ERROR);
    }
}
