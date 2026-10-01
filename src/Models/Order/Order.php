<?php

declare(strict_types=1);

namespace App\Models\Order;

use App\Models\Model;

final class Order extends Model
{
    /**
     * @param OrderItem[] $items
     */
    public function __construct(
        private string $id,
        private array $items,
        private float $total,
        private string $currencyLabel,
        private string $currencySymbol,
        private string $createdAt
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return 'order';
    }

    /** @return OrderItem[] */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        return round($this->total, 2);
    }

    public function getCurrencyLabel(): string
    {
        return $this->currencyLabel;
    }

    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
