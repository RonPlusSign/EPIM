CREATE DATABASE IF NOT EXISTS epim;
USE epim;

-- Products

CREATE TABLE IF NOT EXISTS product (
    id INT(10) UNSIGNED PRIMARY KEY NOT NULL,
    title VARCHAR(128) NOT NULL,
    description VARCHAR(512) NOT NULL,
    purchase_price FLOAT NOT NULL,
    sell_price FLOAT NOT NULL,
    recommended_price FLOAT NOT NULL,
    quantity INT(10) UNSIGNED NOT NULL,
    category            INT(10) UNSIGNED               NOT NULL,
    CONSTRAINT `fk_category`
        FOREIGN KEY (category) REFERENCES category (id)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
    brand            INT(10) UNSIGNED               NOT NULL,
    CONSTRAINT `fk_brand`
        FOREIGN KEY (brand) REFERENCES brand (id)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT,
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS product_image (
    product INT(10) UNSIGNED PRIMARY KEY NOT NULL,
    url VARCHAR(128) NOT NULL,
    CONSTRAINT `fk_product`
        FOREIGN KEY (product) REFERENCES product (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS category (
    id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name        VARCHAR(128)                 NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS brand (
    id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name        VARCHAR(128)                 NOT NULL
) ENGINE = InnoDB;



-- Users

CREATE TABLE IF NOT EXISTS user (
    id INT(10) UNSIGNED PRIMARY KEY NOT NULL,
    email VARCHAR(128) NOT NULL,
    name VARCHAR(128) NOT NULL,
    surname VARCHAR(128) NOT NULL,
    phone_number VARCHAR(128) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN NOT NULL,
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS cart (
    user INT(10) UNSIGNED NOT NULL,
    product INT(10) UNSIGNED NOT NULL,
    quantity INT(10) UNSIGNED NOT NULL,

    CONSTRAINT `cart_pk` PRIMARY KEY (user, product),
    CONSTRAINT `product_fk` 
        FOREIGN KEY (product) REFERENCES product (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `user_fk` 
        FOREIGN KEY (user) REFERENCES user (id)
            ON DELETE CASCADE
            ON UPDATE CASCADE,

) ENGINE = InnoDB;



-- Addresses

CREATE TABLE IF NOT EXISTS region (
    id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name        VARCHAR(128)                 NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS province (
    id CHAR(2) PRIMARY KEY NOT NULL,
    name        VARCHAR(128)                 NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS address (
    id BIGINT NOT NULL,
    street VARCHAR(255) NOT NULL, 
    house_number INT(10) NOT NULL, 
    postal_code INT(10) NOT NULL, 
    city VARCHAR(255) NOT NULL, 
    province INT(10) NOT NULL, 
    region INT(10) NOT NULL,

    CONSTRAINT `address_pk` PRIMARY KEY (id, street, house_number, postal_code, city, province, region),
    CONSTRAINT `province_fk` 
        FOREIGN KEY (province) REFERENCES province (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
    CONSTRAINT `region_fk` 
        FOREIGN KEY (region) REFERENCES region (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS user_address (
    address BIGINT NOT NULL, 
    user INT(10) NOT NULL,
    phone_number VARCHAR(128) NOT NULL,

    CONSTRAINT `user_address_pk` PRIMARY KEY (address, user),
    CONSTRAINT `address_fk` 
        FOREIGN KEY (address) REFERENCES address (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
    CONSTRAINT `user_fk` 
        FOREIGN KEY (user) REFERENCES user (id)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
) ENGINE = InnoDB;

