<?php

declare(strict_types=1);

namespace App\Models\Product;

use App\Models\Attribute\AbstractAttribute;
use App\Models\Model;
use App\Models\Price;

/**
 * A product. Concrete product types (clothes, tech) describe themselves and
 * their own ordering rules; nothing outside these classes needs to know which
 * type it is holding.
 */
abstract class AbstractProduct extends Model
{
    /**
     * @param string[]             $gallery
     * @param AbstractAttribute[]  $attributes
     * @param Price[]              $prices
     */
    public function __construct(
        protected string $id,
        protected string $name,
        protected bool $inStock,
        protected string $description,
        protected string $category,
        protected string $brand,
        protected array $gallery = [],
        protected array $attributes = [],
        protected array $prices = []
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

    public function isInStock(): bool
    {
        return $this->inStock;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    /** @return string[] */
    public function getGallery(): array
    {
        return $this->gallery;
    }

    /** @return AbstractAttribute[] */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /** @param AbstractAttribute[] $attributes */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /** @return Price[] */
    public function getPrices(): array
    {
        return $this->prices;
    }

    public function getPrice(): ?Price
    {
        return $this->prices[0] ?? null;
    }

    /**
     * Short line shown under the product name in the cart overlay.
     */
    abstract public function getSummary(): string;

    /**
     * Whether an order line of this product type may be created without the
     * customer picking every attribute first.
     */
    abstract public function allowsPartialSelection(): bool;
}
