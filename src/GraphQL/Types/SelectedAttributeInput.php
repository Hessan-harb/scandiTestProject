<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

final class SelectedAttributeInput extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'SelectedAttributeInput',
            'fields' => [
                'name' => ['type' => Type::nonNull(Type::string())],
                'value' => ['type' => Type::nonNull(Type::string())],
            ],
        ]);
    }
}
