CREATE DATABASE IF NOT EXISTS epim;
USE epim;

-- Name conventions:
-- Table names and attributes: lowercase and snake_case

-- Always define foreign and primary keys as CONSTRAINT
-- Constraints name conventions:
-- CONSTRAINT `this_table_fk_referenced_table` FOREIGN KEY(column) REFERENCES [...];
-- CONSTRAINT `this_table_pk` PRIMARY KEY(columns);
-- CONSTRAINT `this_table_unique` UNIQUE(columns);    # You can also use `column_name_unique`

-- ----------------------- Products ----------------------- --

CREATE TABLE IF NOT EXISTS category
(
    id   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(128)     NOT NULL,

    CONSTRAINT `category_pk` PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS brand
(
    id   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(128)     NOT NULL,

    CONSTRAINT `brand_pk` PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS product
(
    id                INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    title             VARCHAR(128)     NOT NULL,
    description       TEXT             NOT NULL,
    purchase_price    FLOAT            NOT NULL,
    sell_price        FLOAT            NOT NULL,
    recommended_price FLOAT            NOT NULL,
    quantity          INT(10) UNSIGNED NOT NULL DEFAULT 0,
    category          INT(10) UNSIGNED NOT NULL,
    brand             INT(10) UNSIGNED NOT NULL,

    FULLTEXT idx_title (title),

    CONSTRAINT `product_pk` PRIMARY KEY (id),
    CONSTRAINT `product_fk_category`
        FOREIGN KEY (category) REFERENCES category (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
    CONSTRAINT `product_fk_brand`
        FOREIGN KEY (brand) REFERENCES brand (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS product_image
(
    product INT(10) UNSIGNED NOT NULL,
    url     VARCHAR(128)     NOT NULL,

    CONSTRAINT `product_image_pk` PRIMARY KEY (product, url),
    CONSTRAINT `product_image_fk_product`
        FOREIGN KEY (product) REFERENCES product (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB;


-- ----------------------- Users ----------------------- --

CREATE TABLE IF NOT EXISTS user
(
    id           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    email        VARCHAR(128)     NOT NULL,
    name         VARCHAR(128)     NOT NULL,
    surname      VARCHAR(128)     NOT NULL,
    phone_number VARCHAR(128)     NOT NULL,
    password     VARCHAR(255)     NOT NULL,
    is_admin     BOOLEAN          NOT NULL DEFAULT FALSE,

    CONSTRAINT `user_pk` PRIMARY KEY (id),
    CONSTRAINT `email` UNIQUE (email)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS cart
(
    user     INT(10) UNSIGNED NOT NULL,
    product  INT(10) UNSIGNED NOT NULL,
    quantity INT(10) UNSIGNED NOT NULL DEFAULT 1,

    CONSTRAINT `cart_pk` PRIMARY KEY (user, product),
    CONSTRAINT `product_fk`
        FOREIGN KEY (product) REFERENCES product (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `cart_fk_user`
        FOREIGN KEY (user) REFERENCES user (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB;


-- ----------------------- Addresses ----------------------- --

CREATE TABLE IF NOT EXISTS region
(
    id   INT(10) UNSIGNED NOT NULL,
    name VARCHAR(32)      NOT NULL,

    CONSTRAINT `region_pk` PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS province
(
    id     CHAR(2)          NOT NULL,
    name   VARCHAR(64)      NOT NULL,
    region INT(10) UNSIGNED NOT NULL,

    CONSTRAINT `province_pk` PRIMARY KEY (id),
    CONSTRAINT `province_fk_region`
        FOREIGN KEY (region) REFERENCES region (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS city
(
    id       INT(10) UNSIGNED NOT NULL,
    name     VARCHAR(64)      NOT NULL,
    province CHAR(2)          NOT NULL,

    CONSTRAINT `city_pk` PRIMARY KEY (id),
    CONSTRAINT `city_fk_province`
        FOREIGN KEY (province) REFERENCES province (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS address
(
    id           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    street       VARCHAR(128)     NOT NULL,
    house_number INT(10) UNSIGNED NOT NULL,
    postal_code  INT(10) UNSIGNED NOT NULL,
    city         INT(10) UNSIGNED NOT NULL,

    CONSTRAINT `address_unique` UNIQUE (street, house_number, postal_code, city),
    CONSTRAINT `address_pk` PRIMARY KEY (id),
    CONSTRAINT `address_fk_city`
        FOREIGN KEY (city) REFERENCES city (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS user_address
(
    address      INT(10) UNSIGNED NOT NULL,
    user         INT(10) UNSIGNED NOT NULL,
    phone_number VARCHAR(32)      NOT NULL,

    CONSTRAINT `user_address_pk` PRIMARY KEY (address, user),
    CONSTRAINT `user_address_fk_address`
        FOREIGN KEY (address) REFERENCES address (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `user_address_fk_user`
        FOREIGN KEY (user) REFERENCES user (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB;


-- ----------------------- Orders ----------------------- --

CREATE TABLE IF NOT EXISTS order_history
(
    id            INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    user          INT(10) UNSIGNED NOT NULL,
    address       INT(10) UNSIGNED NOT NULL,
    phone_number  VARCHAR(64)      NOT NULL, -- Stored because it could be useful during the shipping process
    timestamp     DATETIME         NOT NULL,
    shipping_cost FLOAT            NOT NULL,
    status        VARCHAR(64)      NOT NULL,

    CONSTRAINT `order_pk` PRIMARY KEY (id),
    CONSTRAINT `order_fk_user`
        FOREIGN KEY (user) REFERENCES user (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
    CONSTRAINT `order_fk_address`
        FOREIGN KEY (address) REFERENCES address (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS order_detail
(
    order_id      INT(10) UNSIGNED NOT NULL,
    product_id    INT(10) UNSIGNED DEFAULT NULL,
    quantity      INT(10) UNSIGNED NOT NULL,
    product_title VARCHAR(128)     NOT NULL,
    product_price FLOAT            NOT NULL,
    brand_name    VARCHAR(128)     NOT NULL,

    CONSTRAINT `order_detail_pk` PRIMARY KEY (order_id, product_id),

    CONSTRAINT `order_detail_fk_order`
        FOREIGN KEY (order_id) REFERENCES order_history (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

    CONSTRAINT `order_detail_fk_product`
        FOREIGN KEY (product_id) REFERENCES product (id)
            ON UPDATE CASCADE
            ON DELETE NO ACTION -- It should be SET NULL, but InnoDB seems to refuse it (?)
) ENGINE = InnoDB;
