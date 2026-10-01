<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

final class OrderInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderInput',
            'fields' => static fn (): array => [
                'items' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::orderItemInput()))),
                ],
            ],
        ]);
    }
}
