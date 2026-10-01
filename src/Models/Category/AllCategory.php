<?php

declare(strict_types=1);

namespace App\Models\Category;

final class AllCategory extends AbstractCategory
{
    public function getType(): string
    {
        return 'all';
    }

    public function getProductCondition(): string
    {
        return '1 = 1';
    }

    public function getProductConditionParams(): array
    {
        return [];
    }
}
