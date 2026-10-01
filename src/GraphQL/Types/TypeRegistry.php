<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;

/**
 * Lazily instantiates every type exactly once, so that types can reference
 * each other without recursion problems.
 */
final class TypeRegistry
{
    /** @var array<class-string<Type>, Type> */
    private static array $types = [];

    public static function currency(): CurrencyType
    {
        return self::get(CurrencyType::class);
    }

    public static function price(): PriceType
    {
        return self::get(PriceType::class);
    }

    public static function attributeItem(): AttributeItemType
    {
        return self::get(AttributeItemType::class);
    }

    public static function attributeSet(): AttributeSetType
    {
        return self::get(AttributeSetType::class);
    }

    public static function product(): ProductType
    {
        return self::get(ProductType::class);
    }

    public static function category(): CategoryType
    {
        return self::get(CategoryType::class);
    }

    public static function orderItem(): OrderItemType
    {
        return self::get(OrderItemType::class);
    }

    public static function order(): OrderType
    {
        return self::get(OrderType::class);
    }

    public static function selectedAttributeInput(): SelectedAttributeInput
    {
        return self::get(SelectedAttributeInput::class);
    }

    public static function orderItemInput(): OrderItemInput
    {
        return self::get(OrderItemInput::class);
    }

    public static function orderInput(): OrderInput
    {
        return self::get(OrderInput::class);
    }

    /**
     * @template T of Type
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    private static function get(string $class): Type
    {
        /** @var T */
        return self::$types[$class] ??= new $class();
    }
}
