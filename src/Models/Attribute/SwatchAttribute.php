<?php

declare(strict_types=1);

namespace App\Models\Attribute;

final class SwatchAttribute extends AbstractAttribute
{
    public function getType(): string
    {
        return 'swatch';
    }

    public function getDisplayMode(): string
    {
        return 'color';
    }

    public function presentItem(AttributeItem $item): string
    {
        return $item->getValue();
    }
}
