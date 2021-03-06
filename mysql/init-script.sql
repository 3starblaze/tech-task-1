CREATE TABLE IF NOT EXISTS products(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sku VARCHAR(100) NOT NULL,
    price INT NOT NULL
);

CREATE TABLE IF NOT EXISTS discs(
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    disc_size INT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS books(
       id INT AUTO_INCREMENT PRIMARY KEY,
       product_id INT NOT NULL,
       weight FLOAT(10, 2) NOT NULL,
       FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE furniture(
       id INT AUTO_INCREMENT PRIMARY KEY,
       product_id INT NOT NULL,
       height INT NOT NULL,
       width INT NOT NULL,
       length INT NOT NULL,
       FOREIGN KEY (product_id) REFERENCES products(id)
);
