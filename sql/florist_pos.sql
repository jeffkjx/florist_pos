CREATE DATABASE florist_pos;
USE florist_pos;

CREATE TABLE items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  image_path VARCHAR(255),
  price DECIMAL(10,2),
  stock INT,
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
);

CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  datetime DATETIME,
  total_amount DECIMAL(10,2)
);

CREATE TABLE transaction_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  transaction_id INT,
  item_id INT,
  quantity INT,
  unit_price DECIMAL(10,2),
  FOREIGN KEY (transaction_id) REFERENCES transactions(id),
  FOREIGN KEY (item_id) REFERENCES items(id)
);

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100),
  password_hash VARCHAR(255)
);

-- Sample admin
INSERT INTO admins (username, password_hash)
VALUES ('admin', SHA2('admin', 256));

-- Sample items (6 flowers)
INSERT INTO items (name, image_path, price, stock) VALUES
('Roses', 'assets/images/rose.jpg', 10.00, 10),
('Tulips', 'assets/images/tulip.jpg', 8.00, 10),
('Sunflowers', 'assets/images/sunflower.jpg', 12.00, 10),
('Lilies', 'assets/images/lily.jpg', 9.00, 10),
('Daisies', 'assets/images/daisy.jpg', 7.00, 10),
('Orchids', 'assets/images/orchid.jpg', 15.00, 10);
