<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Resolvers\AttributeResolver;
use App\Models\Product\AbstractProduct;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => static fn (): array => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractProduct $product): string => $product->getId(),
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractProduct $product): string => $product->getName(),
                ],
                'inStock' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'resolve' => static fn (AbstractProduct $product): bool => $product->isInStock(),
                ],
                'gallery' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(Type::string()))),
                    'resolve' => static fn (AbstractProduct $product): array => $product->getGallery(),
                ],
                'description' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractProduct $product): string => $product->getDescription(),
                ],
                'category' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractProduct $product): string => $product->getCategory(),
                ],
                'brand' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractProduct $product): string => $product->getBrand(),
                ],
                'type' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractProduct $product): string => $product->getType(),
                ],
                'summary' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractProduct $product): string => $product->getSummary(),
                ],
                'attributes' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::attributeSet()))),
                    'resolve' => static fn (AbstractProduct $product): array => AttributeResolver::forProduct($product),
                ],
                'prices' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::price()))),
                    'resolve' => static fn (AbstractProduct $product): array => $product->getPrices(),
                ],
            ],
        ]);
    }
}
