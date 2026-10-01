<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Resolvers\ProductResolver;
use App\Models\Category\AbstractCategory;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class CategoryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Category',
            'fields' => static fn (): array => [
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractCategory $category): string => $category->getName(),
                ],
                'type' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractCategory $category): string => $category->getType(),
                ],
                'products' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::product()))),
                    'resolve' => static fn (AbstractCategory $category): array => ProductResolver::forCategory($category),
                ],
            ],
        ]);
    }
}
