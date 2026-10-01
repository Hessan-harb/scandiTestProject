<?php

declare(strict_types=1);

namespace App\Models\Category;

use App\Models\Model;

/**
 * A category knows how its own products are selected. "all" returns every
 * product, a regular category filters by its own name — the difference lives
 * in the subclasses instead of in a conditional inside the repository.
 */
abstract class AbstractCategory extends Model
{
    public function __construct(protected string $name)
    {
    }

    public function getId(): string
    {
        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * SQL fragment used to filter products belonging to this category.
     */
    abstract public function getProductCondition(): string;

    /**
     * Bound parameters for the condition above.
     *
     * @return array<string, string>
     */
    abstract public function getProductConditionParams(): array;
}
