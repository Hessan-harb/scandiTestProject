<?php

declare(strict_types=1);

namespace App\Models\Order;

final class OrderItem
{
    /**
     * @param array<int, array{name: string, value: string}> $selectedAttributes
     */
    public function __construct(
        private string $productId,
        private string $productName,
        private int $quantity,
        private float $unitPrice,
        private array $selectedAttributes
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): float
    {
        return round($this->unitPrice, 2);
    }

    public function getSubtotal(): float
    {
        return round($this->unitPrice * $this->quantity, 2);
    }

    /** @return array<int, array{name: string, value: string}> */
    public function getSelectedAttributes(): array
    {
        return $this->selectedAttributes;
    }
}
