CREATE DATABASE IF NOT EXISTS epim;
USE epim;

CREATE TABLE IF NOT EXISTS Category (
    category_id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name        VARCHAR(128)                 NOT NULL
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS Product (
    product_id          INT(10) UNSIGNED PRIMARY KEY   NOT NULL AUTO_INCREMENT,
    name                VARCHAR(256)                   NOT NULL,
    description         VARCHAR(4096)                  NOT NULL,
    price               FLOAT                          NOT NULL,
    quantityAvailable   INT(10) UNSIGNED               NOT NULL,
    category            INT(10) UNSIGNED               NOT NULL,
    CONSTRAINT `fk_category_id`
        FOREIGN KEY (category) REFERENCES Category (category_id)
            ON DELETE CASCADE
            ON UPDATE RESTRICT
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS ProductImage (
    imagePath VARCHAR(256)      NOT NULL, -- Il path dell'immagine all'interno del file system
    product_id INT(10) UNSIGNED NOT NULL,
    CONSTRAINT `fk_product_id`
        FOREIGN KEY (product_id) REFERENCES Product (product_id)
            ON DELETE CASCADE
            ON UPDATE RESTRICT
) ENGINE = InnoDB;