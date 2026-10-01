<?php

declare(strict_types=1);

namespace App\Models;

final class Currency
{
    public function __construct(
        private string $label,
        private string $symbol
    ) {
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
