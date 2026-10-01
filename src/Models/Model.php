<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Base class for every domain model.
 *
 * Every concrete model knows its own type; the type is what the factories use
 * to pick the right subclass, so no type checking is needed anywhere else.
 */
abstract class Model
{
    abstract public function getId(): string;

    abstract public function getType(): string;
}
