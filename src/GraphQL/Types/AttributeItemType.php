<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Resolvers\AttributeResolver;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * A single option of an attribute set. Every field is resolved through the
 * attribute resolver, never inline on the product resolver.
 */
final class AttributeItemType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (array $item): string => AttributeResolver::itemId($item),
                ],
                'displayValue' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (array $item): string => AttributeResolver::itemDisplayValue($item),
                ],
                'value' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (array $item): string => AttributeResolver::itemValue($item),
                ],
                'presentation' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'What the client paints or prints for this item, decided by the attribute type.',
                    'resolve' => static fn (array $item): string => AttributeResolver::itemPresentation($item),
                ],
            ],
        ]);
    }
}
