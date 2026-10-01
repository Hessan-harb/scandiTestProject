<?php

declare(strict_types=1);

namespace App\Models\Product;

final class TechProduct extends AbstractProduct
{
    public function getType(): string
    {
        return 'tech';
    }

    public function getSummary(): string
    {
        return sprintf('%s - %s', $this->brand, $this->name);
    }

    public function allowsPartialSelection(): bool
    {
        return false;
    }
}
