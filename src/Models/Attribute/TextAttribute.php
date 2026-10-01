<?php

declare(strict_types=1);

namespace App\Models\Attribute;

final class TextAttribute extends AbstractAttribute
{
    public function getType(): string
    {
        return 'text';
    }

    public function getDisplayMode(): string
    {
        return 'label';
    }

    public function presentItem(AttributeItem $item): string
    {
        return $item->getDisplayValue();
    }
}
