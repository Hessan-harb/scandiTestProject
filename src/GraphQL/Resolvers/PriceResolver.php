<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Models\Currency;
use App\Models\Price;

final class PriceResolver
{
    public static function amount(Price $price): float
    {
        return $price->getAmount();
    }

    public static function currency(Price $price): Currency
    {
        return $price->getCurrency();
    }
}
