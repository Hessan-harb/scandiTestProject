<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

final class OrderItemInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderItemInput',
            'fields' => static fn (): array => [
                'productId' => ['type' => Type::nonNull(Type::string())],
                'quantity' => ['type' => Type::nonNull(Type::int())],
                'attributes' => [
                    'type' => Type::listOf(Type::nonNull(TypeRegistry::selectedAttributeInput())),
                    'defaultValue' => [],
                ],
            ],
        ]);
    }
}
