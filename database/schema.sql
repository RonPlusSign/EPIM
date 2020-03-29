DROP DATABASE epim IF EXISTS;
CREATE DATABASE epim IF NOT EXISTS;
USE epim;

CREATE TABLE Category (
    category_id INT          PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name        VARCHAR(128)             NOT NULL
);

CREATE TABLE Product (
    product_id        INT PRIMARY KEY   NOT NULL AUTO_INCREMENT,
    name              VARCHAR(256)      NOT NULL,
    description       VARCHAR(4096)     NOT NULL,
    price             FLOAT             NOT NULL,
    quantityAvailable INT               NOT NULL,
    category          INT               NOT NULL FOREIGN KEY REFERENCES Category(category_id) );

CREATE TABLE ProductImage(
    imageFile VARBINARY(MAX) NOT NULL, -- Usare MAX per contenere immagini di più di 8000 byte
                                       -- TODO: Valutare di salvare soltanto il path dell'immagine
    product_id INT           NOT NULL FOREIGN KEY REFERENCES Product(product_id)
);