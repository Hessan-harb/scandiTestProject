<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Models\Category\AbstractCategory;
use App\Models\Category\CategoryFactory;
use App\Models\Product\AbstractProduct;
use App\Repositories\ProductRepository;

final class ProductResolver
{
    private static ?ProductRepository $repository = null;

    /**
     * @return AbstractProduct[]
     */
    public static function forCategory(AbstractCategory $category): array
    {
        return self::repository()->findByCategory($category);
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return AbstractProduct[]
     */
    public static function list(mixed $root, array $args): array
    {
        return self::repository()->findByCategory(
            CategoryFactory::create((string) ($args['category'] ?? 'all'))
        );
    }

    /**
     * @param array<string, mixed> $args
     */
    public static function one(mixed $root, array $args): ?AbstractProduct
    {
        return self::repository()->findById((string) $args['id']);
    }

    private static function repository(): ProductRepository
    {
        return self::$repository ??= new ProductRepository();
    }
}
