<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Models\Order\Order;
use App\Repositories\OrderRepository;

final class OrderResolver
{
    private static ?OrderRepository $repository = null;

    /**
     * @param array<string, mixed> $args
     */
    public static function create(mixed $root, array $args): Order
    {
        /** @var array{items: array<int, array<string, mixed>>} $input */
        $input = $args['input'];

        return self::repository()->create($input['items']);
    }

    private static function repository(): OrderRepository
    {
        return self::$repository ??= new OrderRepository();
    }
}
