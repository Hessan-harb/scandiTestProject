<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\Category\AbstractCategory;
use App\Models\Product\AbstractProduct;
use App\Models\Product\ProductFactory;
use PDO;

final class ProductRepository
{
    private const SELECT = 'SELECT p.id, p.name, p.in_stock, p.description, p.category, p.brand,
                                   pr.amount, pr.currency_label, pr.currency_symbol
                            FROM products p
                            LEFT JOIN prices pr ON pr.product_id = p.id';

    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::connection();
    }

    /**
     * The category itself provides the filter, so this method never inspects
     * the category type.
     *
     * @return AbstractProduct[]
     */
    public function findByCategory(AbstractCategory $category): array
    {
        $sql = self::SELECT . ' WHERE ' . $category->getProductCondition() . ' ORDER BY p.name ASC';
        $statement = $this->connection->prepare($sql);
        $statement->execute($category->getProductConditionParams());

        return $this->hydrateMany($statement->fetchAll());
    }

    public function findById(string $id): ?AbstractProduct
    {
        $statement = $this->connection->prepare(self::SELECT . ' WHERE p.id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return $this->hydrateMany([$row])[0] ?? null;
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     *
     * @return AbstractProduct[]
     */
    private function hydrateMany(array $rows): array
    {
        if ($rows === []) {
            return [];
        }

        $ids = array_map(static fn (array $row): string => (string) $row['id'], $rows);
        $galleries = $this->findGalleries($ids);

        return array_map(
            static fn (array $row): AbstractProduct => ProductFactory::createFromRow(
                $row,
                $galleries[(string) $row['id']] ?? []
            ),
            $rows
        );
    }

    /**
     * @param string[] $productIds
     *
     * @return array<string, string[]>
     */
    private function findGalleries(array $productIds): array
    {
        $placeholders = implode(', ', array_fill(0, count($productIds), '?'));
        $statement = $this->connection->prepare(
            'SELECT product_id, image_url FROM galleries
             WHERE product_id IN (' . $placeholders . ')
             ORDER BY position ASC, id ASC'
        );
        $statement->execute(array_values($productIds));

        $galleries = [];
        foreach ($statement->fetchAll() as $row) {
            $galleries[(string) $row['product_id']][] = (string) $row['image_url'];
        }

        return $galleries;
    }
}
