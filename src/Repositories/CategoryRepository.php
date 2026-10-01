<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\Category\AbstractCategory;
use App\Models\Category\CategoryFactory;
use PDO;

final class CategoryRepository
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::connection();
    }

    /**
     * @return AbstractCategory[]
     */
    public function findAll(): array
    {
        $statement = $this->connection->query('SELECT name FROM categories ORDER BY id ASC');

        return array_map(
            static fn (array $row): AbstractCategory => CategoryFactory::createFromRow($row),
            $statement->fetchAll()
        );
    }

    public function findByName(string $name): ?AbstractCategory
    {
        $statement = $this->connection->prepare('SELECT name FROM categories WHERE name = :name LIMIT 1');
        $statement->execute(['name' => $name]);
        $row = $statement->fetch();

        return $row === false ? null : CategoryFactory::createFromRow($row);
    }
}
