<?php

declare(strict_types=1);

namespace App\GraphQL\Schema;

use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Resolvers\OrderResolver;
use App\GraphQL\Resolvers\ProductResolver;
use App\GraphQL\Types\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;

final class SchemaBuilder
{
    public function build(): Schema
    {
        return new Schema(
            SchemaConfig::create()
                ->setQuery($this->queryType())
                ->setMutation($this->mutationType())
        );
    }

    private function queryType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::category()))),
                    'resolve' => static fn (): array => CategoryResolver::all(),
                ],
                'category' => [
                    'type' => TypeRegistry::category(),
                    'args' => [
                        'title' => ['type' => Type::string(), 'defaultValue' => 'all'],
                    ],
                    'resolve' => [CategoryResolver::class, 'one'],
                ],
                'products' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::product()))),
                    'args' => [
                        'category' => ['type' => Type::string(), 'defaultValue' => 'all'],
                    ],
                    'resolve' => [ProductResolver::class, 'list'],
                ],
                'product' => [
                    'type' => TypeRegistry::product(),
                    'args' => [
                        'id' => ['type' => Type::nonNull(Type::string())],
                    ],
                    'resolve' => [ProductResolver::class, 'one'],
                ],
            ],
        ]);
    }

    private function mutationType(): ObjectType
    {
        return new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => Type::nonNull(TypeRegistry::order()),
                    'args' => [
                        'input' => ['type' => Type::nonNull(TypeRegistry::orderInput())],
                    ],
                    'resolve' => [OrderResolver::class, 'create'],
                ],
            ],
        ]);
    }
}
