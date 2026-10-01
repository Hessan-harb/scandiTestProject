<?php

declare(strict_types=1);

namespace App\Models\Attribute;

final class AttributeFactory
{
    /** @var array<string, class-string<AbstractAttribute>> */
    private const MAP = [
        'text' => TextAttribute::class,
        'swatch' => SwatchAttribute::class,
    ];

    /**
     * @param array{attribute_id: string, name: string, type: string} $row
     */
    public static function createFromRow(array $row): AbstractAttribute
    {
        $class = self::MAP[strtolower((string) $row['type'])] ?? TextAttribute::class;

        return new $class((string) $row['attribute_id'], (string) $row['name']);
    }
}
