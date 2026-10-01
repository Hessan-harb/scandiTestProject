<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Order\OrderItem;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class OrderItemType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderItem',
            'fields' => [
                'productId' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (OrderItem $item): string => $item->getProductId(),
                ],
                'productName' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (OrderItem $item): string => $item->getProductName(),
                ],
                'quantity' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => static fn (OrderItem $item): int => $item->getQuantity(),
                ],
                'unitPrice' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => static fn (OrderItem $item): float => $item->getUnitPrice(),
                ],
                'subtotal' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => static fn (OrderItem $item): float => $item->getSubtotal(),
                ],
            ],
        ]);
    }
}
