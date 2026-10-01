<?php

declare(strict_types=1);

namespace App\Models\Attribute;

use App\Models\Model;

/**
 * An attribute set (Size, Color, Capacity...). Concrete types decide how a
 * single item is presented to the client, which is what makes swatches render
 * as colour squares and text attributes as labelled buttons.
 */
abstract class AbstractAttribute extends Model
{
    /**
     * @param AttributeItem[] $items
     */
    public function __construct(
        protected string $id,
        protected string $name,
        protected array $items = []
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return AttributeItem[] */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(AttributeItem $item): void
    {
        $this->items[] = $item;
    }

    public function getDefaultItem(): ?AttributeItem
    {
        return $this->items[0] ?? null;
    }

    /**
     * How the front-end should render a single item of this attribute.
     */
    abstract public function getDisplayMode(): string;

    /**
     * The value the front-end paints/prints for one item of this attribute.
     */
    abstract public function presentItem(AttributeItem $item): string;
}
