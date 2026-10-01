<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Resolvers\PriceResolver;
use App\Models\Price;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class PriceType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => static fn (): array => [
                'amount' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => static fn (Price $price): float => PriceResolver::amount($price),
                ],
                'currency' => [
                    'type' => Type::nonNull(TypeRegistry::currency()),
                    'resolve' => static fn (Price $price) => PriceResolver::currency($price),
                ],
            ],
        ]);
    }
}
