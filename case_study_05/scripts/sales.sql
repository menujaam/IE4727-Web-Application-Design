CREATE TABLE IF NOT EXISTS sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product VARCHAR(50),
  category VARCHAR(20),
  quantity INT,
  total DECIMAL(6,2),
  sale_date DATE
);



CREATE TABLE IF NOT EXISTS products (
  product_id INT AUTO_INCREMENT PRIMARY KEY, -- id starts from 1
  product_abbreviated_name VARCHAR(10) NOT NULL, -- not sure if this is needed (abbreviated name used in value for checkboxes)
  product_full_name VARCHAR(50) NOT NULL,
  product_description VARCHAR(500) NOT NULL
);

CREATE TABLE IF NOT EXISTS product_prices (
  product_price_id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT,
  product_option_name VARCHAR(50),
  product_option_price DECIMAL(4,2) NOT NULL,

  CHECK (product_option_price >= 0),
  FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- clear previous price updates and insert default values into products and product_prices

-- delete and truncate have different behaviours: delete doesn't reset the primary key to start from 1 again, but truncate does
DELETE FROM products;
DELETE FROM product_prices; -- truncation doesn't work on tables with foreign keys (?) so use delete on both for consistency

INSERT INTO products (product_id, product_abbreviated_name, product_full_name, product_description) VALUES
(1, 'jj', 'Just Java', 'Rich and creamy coffee served with milk'),
(2, 'cal', 'Cafe au Lait', 'Nuts, milk, and coffee meld together in this delightful latte.'),
(3, 'ic', 'Iced Cappucino', 'Enjoy a refreshing shot of coffee. Chilled and brewed with berries and chocolate.');

INSERT INTO product_prices (product_id, product_option_name, product_option_price) VALUES
(1, 'Endless Cup', 2),
(2, 'Single', 2),
(2, 'Double', 3),
(3, 'Single', 4.75),
(3, 'Double', 5.75);