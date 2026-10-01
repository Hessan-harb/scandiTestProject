<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Currency;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class CurrencyType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Currency',
            'fields' => [
                'label' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (Currency $currency): string => $currency->getLabel(),
                ],
                'symbol' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (Currency $currency): string => $currency->getSymbol(),
                ],
            ],
        ]);
    }
}
