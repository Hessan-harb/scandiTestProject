<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use InvalidArgumentException;
use PDO;

final class OrderRepository
{
    private PDO $connection;

    public function __construct(
        ?PDO $connection = null,
        private ?ProductRepository $products = null
    ) {
        $this->connection = $connection ?? Database::connection();
        $this->products = $products ?? new ProductRepository($this->connection);
    }

    /**
     * Prices are taken from the database, never from the client payload.
     *
     * @param array<int, array{productId: string, quantity: int, attributes?: array<int, array{name: string, value: string}>}> $items
     */
    public function create(array $items): Order
    {
        if ($items === []) {
            throw new InvalidArgumentException('An order must contain at least one item.');
        }

        $orderItems = [];
        $total = 0.0;
        $currencyLabel = 'USD';
        $currencySymbol = '$';

        foreach ($items as $item) {
            $product = $this->products->findById((string) $item['productId']);

            if ($product === null) {
                throw new InvalidArgumentException('Unknown product: ' . (string) $item['productId']);
            }

            if (!$product->isInStock()) {
                throw new InvalidArgumentException('Product is out of stock: ' . $product->getName());
            }

            $quantity = max(1, (int) $item['quantity']);
            $price = $product->getPrice();
            $amount = $price === null ? 0.0 : $price->getAmount();

            if ($price !== null) {
                $currencyLabel = $price->getCurrency()->getLabel();
                $currencySymbol = $price->getCurrency()->getSymbol();
            }

            $total += $amount * $quantity;

            $orderItems[] = new OrderItem(
                $product->getId(),
                $product->getName(),
                $quantity,
                $amount,
                array_values($item['attributes'] ?? [])
            );
        }

        return $this->persist($orderItems, round($total, 2), $currencyLabel, $currencySymbol);
    }

    /**
     * @param OrderItem[] $orderItems
     */
    private function persist(array $orderItems, float $total, string $currencyLabel, string $currencySymbol): Order
    {
        $this->connection->beginTransaction();

        try {
            $createdAt = date('Y-m-d H:i:s');

            $statement = $this->connection->prepare(
                'INSERT INTO orders (total, currency_label, currency_symbol, created_at)
                 VALUES (:total, :label, :symbol, :created_at)'
            );
            $statement->execute([
                'total' => $total,
                'label' => $currencyLabel,
                'symbol' => $currencySymbol,
                'created_at' => $createdAt,
            ]);

            $orderId = (string) $this->connection->lastInsertId();

            $itemStatement = $this->connection->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, attributes)
                 VALUES (:order_id, :product_id, :product_name, :quantity, :unit_price, :attributes)'
            );

            foreach ($orderItems as $item) {
                $itemStatement->execute([
                    'order_id' => $orderId,
                    'product_id' => $item->getProductId(),
                    'product_name' => $item->getProductName(),
                    'quantity' => $item->getQuantity(),
                    'unit_price' => $item->getUnitPrice(),
                    'attributes' => json_encode($item->getSelectedAttributes(), JSON_THROW_ON_ERROR),
                ]);
            }

            $this->connection->commit();

            return new Order($orderId, $orderItems, $total, $currencyLabel, $currencySymbol, $createdAt);
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }
    }
}
