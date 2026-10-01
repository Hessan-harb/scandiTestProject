-- MySQL 5.6 compatible schema (no JSON column type, utf8 charset).

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS attribute_items;
DROP TABLE IF EXISTS attribute_sets;
DROP TABLE IF EXISTS prices;
DROP TABLE IF EXISTS galleries;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE products (
    id VARCHAR(100) NOT NULL,
    name VARCHAR(255) NOT NULL,
    in_stock TINYINT(1) NOT NULL DEFAULT 1,
    description TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    brand VARCHAR(150) NOT NULL DEFAULT '',
    PRIMARY KEY (id),
    KEY idx_products_category (category),
    CONSTRAINT fk_products_category FOREIGN KEY (category)
        REFERENCES categories (name) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE galleries (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id VARCHAR(100) NOT NULL,
    image_url VARCHAR(1000) NOT NULL,
    position INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_galleries_product (product_id),
    CONSTRAINT fk_galleries_product FOREIGN KEY (product_id)
        REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE prices (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    currency_label VARCHAR(10) NOT NULL DEFAULT 'USD',
    currency_symbol VARCHAR(10) NOT NULL DEFAULT '$',
    PRIMARY KEY (id),
    KEY idx_prices_product (product_id),
    CONSTRAINT fk_prices_product FOREIGN KEY (product_id)
        REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE attribute_sets (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id VARCHAR(100) NOT NULL,
    attribute_id VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'text',
    PRIMARY KEY (id),
    KEY idx_attribute_sets_product (product_id),
    CONSTRAINT fk_attribute_sets_product FOREIGN KEY (product_id)
        REFERENCES products (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE attribute_items (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    attribute_set_id INT UNSIGNED NOT NULL,
    item_id VARCHAR(100) NOT NULL,
    display_value VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    KEY idx_attribute_items_set (attribute_set_id),
    CONSTRAINT fk_attribute_items_set FOREIGN KEY (attribute_set_id)
        REFERENCES attribute_sets (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE orders (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    currency_label VARCHAR(10) NOT NULL DEFAULT 'USD',
    currency_symbol VARCHAR(10) NOT NULL DEFAULT '$',
    created_at DATETIME NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE order_items (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id INT UNSIGNED NOT NULL,
    product_id VARCHAR(100) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    unit_price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    attributes TEXT NULL,
    PRIMARY KEY (id),
    KEY idx_order_items_order (order_id),
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id)
        REFERENCES orders (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
