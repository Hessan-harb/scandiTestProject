<?php

declare(strict_types=1);

namespace App\Models\Category;

final class CategoryFactory
{
    /** @var array<string, class-string<AbstractCategory>> */
    private const MAP = [
        'all' => AllCategory::class,
    ];

    /**
     * @param array{name: string} $row
     */
    public static function createFromRow(array $row): AbstractCategory
    {
        return self::create((string) $row['name']);
    }

    public static function create(string $name): AbstractCategory
    {
        $class = self::MAP[strtolower($name)] ?? RegularCategory::class;

        return new $class($name);
    }
}
