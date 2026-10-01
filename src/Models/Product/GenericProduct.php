<?php

declare(strict_types=1);

namespace App\Models\Product;

/**
 * Fallback type, used when the imported data introduces a category the
 * application does not model explicitly yet.
 */
final class GenericProduct extends AbstractProduct
{
    public function getType(): string
    {
        return 'generic';
    }

    public function getSummary(): string
    {
        return $this->name;
    }

    public function allowsPartialSelection(): bool
    {
        return true;
    }
}
