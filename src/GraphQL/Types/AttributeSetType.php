<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Resolvers\AttributeResolver;
use App\Models\Attribute\AbstractAttribute;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

final class AttributeSetType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeSet',
            'fields' => static fn (): array => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractAttribute $set): string => $set->getId(),
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractAttribute $set): string => $set->getName(),
                ],
                'type' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => static fn (AbstractAttribute $set): string => $set->getType(),
                ],
                'displayMode' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'How the client should render an item: label or color.',
                    'resolve' => static fn (AbstractAttribute $set): string => $set->getDisplayMode(),
                ],
                'items' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::attributeItem()))),
                    'resolve' => static fn (AbstractAttribute $set): array => AttributeResolver::items($set),
                ],
            ],
        ]);
    }
}
