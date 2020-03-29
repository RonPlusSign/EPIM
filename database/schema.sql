CREATE TABLE Product (
    product_id INT PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    description VARCHAR(128) NOT NULL,
    price VARCHAR(128) NOT NULL,
    quantity INT NOT NULL,
    category INT NOT NULL foreign key
);