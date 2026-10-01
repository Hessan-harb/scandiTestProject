<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\Attribute\AbstractAttribute;
use App\Models\Attribute\AttributeFactory;
use App\Models\Attribute\AttributeItem;
use PDO;

final class AttributeRepository
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::connection();
    }

    /**
     * @return AbstractAttribute[]
     */
    public function findByProductId(string $productId): array
    {
        return $this->findByProductIds([$productId])[$productId] ?? [];
    }

    /**
     * @param string[] $productIds
     *
     * @return array<string, AbstractAttribute[]>
     */
    public function findByProductIds(array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($productIds), '?'));

        $sql = 'SELECT s.id AS set_key, s.product_id, s.attribute_id, s.name, s.type,
                       i.item_id, i.display_value, i.value
                FROM attribute_sets s
                LEFT JOIN attribute_items i ON i.attribute_set_id = s.id
                WHERE s.product_id IN (' . $placeholders . ')
                ORDER BY s.id ASC, i.id ASC';

        $statement = $this->connection->prepare($sql);
        $statement->execute(array_values($productIds));

        /** @var array<string, AbstractAttribute[]> $result */
        $result = [];
        /** @var array<int, AbstractAttribute> $sets */
        $sets = [];

        foreach ($statement->fetchAll() as $row) {
            $setKey = (int) $row['set_key'];
            $productId = (string) $row['product_id'];

            if (!isset($sets[$setKey])) {
                $sets[$setKey] = AttributeFactory::createFromRow($row);
                $result[$productId][] = $sets[$setKey];
            }

            if ($row['item_id'] !== null) {
                $sets[$setKey]->addItem(new AttributeItem(
                    (string) $row['item_id'],
                    (string) $row['display_value'],
                    (string) $row['value']
                ));
            }
        }

        return $result;
    }
}
