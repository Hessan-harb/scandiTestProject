<?php

declare(strict_types=1);

namespace App\Models\Product;

use App\Models\Currency;
use App\Models\Price;

final class ProductFactory
{
    /** @var array<string, class-string<AbstractProduct>> */
    private const MAP = [
        'clothes' => ClothesProduct::class,
        'tech' => TechProduct::class,
    ];

    /**
     * @param array<string, mixed> $row
     * @param string[]             $gallery
     */
    public static function createFromRow(array $row, array $gallery = []): AbstractProduct
    {
        $class = self::MAP[strtolower((string) $row['category'])] ?? GenericProduct::class;

        return new $class(
            (string) $row['id'],
            (string) $row['name'],
            (bool) $row['in_stock'],
            (string) ($row['description'] ?? ''),
            (string) $row['category'],
            (string) ($row['brand'] ?? ''),
            $gallery,
            [],
            self::buildPrices($row)
        );
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return Price[]
     */
    private static function buildPrices(array $row): array
    {
        if (!array_key_exists('amount', $row) || $row['amount'] === null) {
            return [];
        }

        return [
            new Price(
                (float) $row['amount'],
                new Currency((string) ($row['currency_label'] ?? 'USD'), (string) ($row['currency_symbol'] ?? '$'))
            ),
        ];
    }
}
