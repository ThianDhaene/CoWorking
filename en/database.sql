-- User Table
CREATE TABLE IF NOT EXISTS users (
    `user_id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- Product Table
CREATE TABLE IF NOT EXISTS products (
    `product_id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

INSERT INTO products (`product_id`, `name`, `price`)
VALUES
('1', 'ietsgents hoodie',  49.99),
('2', 'ietsgents tshirt', 19.99),
('3', 'ietsgents totebag', 9.99),
('4', 'ietsgents pants', 39.99),
('5', 'ietsgents beanie', 24.99),
('6', 'ietsgents sock', 24.99),
('7', 'ietsgents lighter', 4.99),
('8', 'ietsgents bottle', 19.99);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    `order_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'Pending', -- Status can be 'Pending', 'Processing', 'Shipped', etc.
    `street` varchar(255) DEFAULT NULL,
    `number` varchar(20) DEFAULT NULL,
    `city` varchar(100) DEFAULT NULL,
    `postal_code` varchar(20) DEFAULT NULL,
    `country` varchar(100) DEFAULT NULL,
    `extra_info` text DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES users(`user_id`)
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    `item_id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT,
    `product_id` INT,
    `quantity` INT,
    `price` DECIMAL(10, 2),
    FOREIGN KEY (`order_id`) REFERENCES orders(`order_id`),
    FOREIGN KEY (`product_id`) REFERENCES products(`product_id`)
);

-- Messages Table
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL auto_increment,
  `sender` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_unicode_ci AUTO_INCREMENT = 1;


-- Review Table
CREATE TABLE IF NOT EXISTS `reviews` (
    `review_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `rating` INT(1) NOT NULL,
    `comment` TEXT,
    PRIMARY KEY (`review_id`),
    FOREIGN KEY (`user_id`) REFERENCES users(`user_id`),
    FOREIGN KEY (`product_id`) REFERENCES products(`product_id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;


