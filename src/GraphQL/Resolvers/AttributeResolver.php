<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Models\Attribute\AbstractAttribute;
use App\Models\Product\AbstractProduct;
use App\Repositories\AttributeRepository;

/**
 * Attributes have their own resolver: the product resolver only hands over the
 * product, everything about attributes is decided here and in the attribute
 * classes themselves.
 */
final class AttributeResolver
{
    private static ?AttributeRepository $repository = null;

    /**
     * @return AbstractAttribute[]
     */
    public static function forProduct(AbstractProduct $product): array
    {
        $attributes = $product->getAttributes();

        if ($attributes === []) {
            $attributes = self::repository()->findByProductId($product->getId());
            $product->setAttributes($attributes);
        }

        return $attributes;
    }

    /**
     * Each item is passed on together with the set it belongs to, so the set
     * can decide how the item is presented.
     *
     * @return array<int, array{set: AbstractAttribute, item: \App\Models\Attribute\AttributeItem}>
     */
    public static function items(AbstractAttribute $set): array
    {
        return array_map(
            static fn ($item): array => ['set' => $set, 'item' => $item],
            $set->getItems()
        );
    }

    /** @param array{set: AbstractAttribute, item: \App\Models\Attribute\AttributeItem} $context */
    public static function itemId(array $context): string
    {
        return $context['item']->getId();
    }

    /** @param array{set: AbstractAttribute, item: \App\Models\Attribute\AttributeItem} $context */
    public static function itemDisplayValue(array $context): string
    {
        return $context['item']->getDisplayValue();
    }

    /** @param array{set: AbstractAttribute, item: \App\Models\Attribute\AttributeItem} $context */
    public static function itemValue(array $context): string
    {
        return $context['item']->getValue();
    }

    /** @param array{set: AbstractAttribute, item: \App\Models\Attribute\AttributeItem} $context */
    public static function itemPresentation(array $context): string
    {
        return $context['set']->presentItem($context['item']);
    }

    private static function repository(): AttributeRepository
    {
        return self::$repository ??= new AttributeRepository();
    }
}
