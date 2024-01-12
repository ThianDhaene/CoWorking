-- User Table
CREATE TABLE users (
    user_id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- Product Table
CREATE TABLE products (
    product_id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (product_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- Cart Table
CREATE TABLE carts (
    cart_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11),
    PRIMARY KEY (cart_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- CartItem Table
CREATE TABLE cart_items (
    cart_item_id INT(11) NOT NULL AUTO_INCREMENT,
    cart_id INT(11),
    product_id INT(11),
    quantity INT NOT NULL,
    PRIMARY KEY (cart_item_id),
    FOREIGN KEY (cart_id) REFERENCES carts(cart_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- Orders Table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending', -- Status can be 'Pending', 'Processing', 'Shipped', etc.
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Order Items Table
CREATE TABLE order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

