<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Models\Category\AbstractCategory;
use App\Repositories\CategoryRepository;

final class CategoryResolver
{
    private static ?CategoryRepository $repository = null;

    /**
     * @return AbstractCategory[]
     */
    public static function all(): array
    {
        return self::repository()->findAll();
    }

    /**
     * @param array<string, mixed> $args
     */
    public static function one(mixed $root, array $args): ?AbstractCategory
    {
        return self::repository()->findByName((string) ($args['title'] ?? 'all'));
    }

    private static function repository(): CategoryRepository
    {
        return self::$repository ??= new CategoryRepository();
    }
}
