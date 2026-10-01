<?php

declare(strict_types=1);

namespace App\Models\Product;

final class ClothesProduct extends AbstractProduct
{
    public function getType(): string
    {
        return 'clothes';
    }

    public function getSummary(): string
    {
        return sprintf('%s by %s', $this->name, $this->brand);
    }

    public function allowsPartialSelection(): bool
    {
        return false;
    }
}
