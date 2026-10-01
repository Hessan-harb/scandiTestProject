<?php

declare(strict_types=1);

namespace App\Models;

final class Price
{
    public function __construct(
        private float $amount,
        private Currency $currency
    ) {
    }

    public function getAmount(): float
    {
        return round($this->amount, 2);
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
