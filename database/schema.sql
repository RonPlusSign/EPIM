DROP DATABASE epim IF EXISTS;
CREATE DATABASE epim;
USE epim;

CREATE TABLE Category (
    category_id INT(10) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name        VARCHAR(128)                 NOT NULL
);

CREATE TABLE Product (
    product_id          INT(10) UNSIGNED PRIMARY KEY   NOT NULL AUTO_INCREMENT,
    name                VARCHAR(256)                   NOT NULL,
    description         VARCHAR(4096)                  NOT NULL,
    price               FLOAT                          NOT NULL,
    quantityAvailable   INT(10) UNSIGNED               NOT NULL,
    category            INT(10) UNSIGNED               NOT NULL FOREIGN KEY REFERENCES Category(category_id)
            ON UPDATE RESTRICT,
            ON DELETE CASCADE
);

CREATE TABLE ProductImage (
    imagePath VARCHAR(256)      NOT NULL, -- Il path dell'immagine all'interno del file system
    product_id INT(10) UNSIGNED NOT NULL FOREIGN KEY REFERENCES Product(product_id)
              ON UPDATE RESTRICT,
              ON DELETE CASCADE
);