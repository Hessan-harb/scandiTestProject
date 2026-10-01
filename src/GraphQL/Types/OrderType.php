<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Order\Order;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class OrderType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Order',
            'fields' => static fn (): array => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (Order $order): string => $order->getId(),
                ],
                'total' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => static fn (Order $order): float => $order->getTotal(),
                ],
                'currency' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (Order $order): string => $order->getCurrencySymbol(),
                ],
                'createdAt' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (Order $order): string => $order->getCreatedAt(),
                ],
                'items' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::orderItem()))),
                    'resolve' => static fn (Order $order): array => $order->getItems(),
                ],
            ],
        ]);
    }
}
