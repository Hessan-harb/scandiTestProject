<?php

declare(strict_types=1);

/*
 * Imports the data.json file supplied with the test task into MySQL.
 *
 *   php scripts/import.php database/data.json
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Config;
use App\Database\Database;

Config::load(dirname(__DIR__));

$path = $argv[1] ?? dirname(__DIR__) . '/database/data.json';

if (!file_exists($path)) {
    fwrite(STDERR, "data.json not found at {$path}\n");
    exit(1);
}

$raw = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
$payload = $raw['data'] ?? $raw;
$categories = $payload['categories'] ?? [];
$products = $payload['products'] ?? [];

$pdo = Database::connection();
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
foreach (['order_items', 'orders', 'attribute_items', 'attribute_sets', 'prices', 'galleries', 'products', 'categories'] as $table) {
    $pdo->exec('TRUNCATE TABLE ' . $table);
}
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$insertCategory = $pdo->prepare('INSERT INTO categories (name) VALUES (:name)');
foreach ($categories as $category) {
    $insertCategory->execute(['name' => $category['name']]);
}

$insertProduct = $pdo->prepare(
    'INSERT INTO products (id, name, in_stock, description, category, brand)
     VALUES (:id, :name, :in_stock, :description, :category, :brand)'
);
$insertGallery = $pdo->prepare(
    'INSERT INTO galleries (product_id, image_url, position) VALUES (:product_id, :image_url, :position)'
);
$insertPrice = $pdo->prepare(
    'INSERT INTO prices (product_id, amount, currency_label, currency_symbol)
     VALUES (:product_id, :amount, :label, :symbol)'
);
$insertSet = $pdo->prepare(
    'INSERT INTO attribute_sets (product_id, attribute_id, name, type)
     VALUES (:product_id, :attribute_id, :name, :type)'
);
$insertItem = $pdo->prepare(
    'INSERT INTO attribute_items (attribute_set_id, item_id, display_value, value)
     VALUES (:set_id, :item_id, :display_value, :value)'
);

$pdo->beginTransaction();

foreach ($products as $product) {
    $insertProduct->execute([
        'id' => $product['id'],
        'name' => $product['name'],
        'in_stock' => (int) ($product['inStock'] ?? true),
        'description' => $product['description'] ?? '',
        'category' => $product['category'],
        'brand' => $product['brand'] ?? '',
    ]);

    foreach (($product['gallery'] ?? []) as $position => $image) {
        $insertGallery->execute([
            'product_id' => $product['id'],
            'image_url' => $image,
            'position' => $position,
        ]);
    }

    foreach (($product['prices'] ?? []) as $price) {
        $insertPrice->execute([
            'product_id' => $product['id'],
            'amount' => $price['amount'],
            'label' => $price['currency']['label'] ?? 'USD',
            'symbol' => $price['currency']['symbol'] ?? '$',
        ]);
    }

    foreach (($product['attributes'] ?? []) as $set) {
        $insertSet->execute([
            'product_id' => $product['id'],
            'attribute_id' => $set['id'],
            'name' => $set['name'],
            'type' => $set['type'] ?? 'text',
        ]);

        $setId = (int) $pdo->lastInsertId();

        foreach (($set['items'] ?? []) as $item) {
            $insertItem->execute([
                'set_id' => $setId,
                'item_id' => $item['id'],
                'display_value' => $item['displayValue'],
                'value' => $item['value'],
            ]);
        }
    }
}

$pdo->commit();

printf("Imported %d categories and %d products.\n", count($categories), count($products));
