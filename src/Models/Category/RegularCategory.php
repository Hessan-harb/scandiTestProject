<?php

declare(strict_types=1);

namespace App\Models\Category;

final class RegularCategory extends AbstractCategory
{
    public function getType(): string
    {
        return 'regular';
    }

    public function getProductCondition(): string
    {
        return 'p.category = :category';
    }

    public function getProductConditionParams(): array
    {
        return ['category' => $this->name];
    }
}
