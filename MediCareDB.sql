CREATE DATABASE IF NOT EXISTS MediCare;
USE MediCare;

-- USERS TABLE
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    security_question VARCHAR(100) NOT NULL,
    security_answer VARCHAR(255) NOT NULL,
    account_type ENUM('customer', 'admin', 'pharmacy') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- WALLET TABLE
CREATE TABLE wallet (
    walletId INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(10,2) DEFAULT 0.00,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE SET NULL
);

-- PRODUCTS TABLE
CREATE TABLE products (
    productId INT AUTO_INCREMENT PRIMARY KEY,
    pharmacyId INT,
    productName VARCHAR(100) NOT NULL,
    productDescription VARCHAR(255) NOT NULL,
    productQuantity INT DEFAULT 0,
    productImage VARCHAR(255), -- image filename or path
    productPrice DECIMAL(10, 2),
    FOREIGN KEY (pharmacyId) REFERENCES users(id) ON DELETE SET NULL
);

-- ORDERS TABLE
CREATE TABLE orders (
    orderId INT AUTO_INCREMENT PRIMARY KEY,
    productId INT,
    pharmacyId INT,
    customerId INT,
    isInTransaction TINYINT(1) DEFAULT 0,
    order_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE SET NULL,
    FOREIGN KEY (pharmacyId) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (customerId) REFERENCES users(id) ON DELETE SET NULL
);

-- TRANSACTION HISTORY TABLE
CREATE TABLE transactionHistory (
    transactionId INT AUTO_INCREMENT PRIMARY KEY,
    orderId INT,
    transactionStatus VARCHAR(50) NOT NULL,
    transaction_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE SET NULL
);

-- CART TABLE
CREATE TABLE cart (
    cartId INT AUTO_INCREMENT PRIMARY KEY,
    productId INT,
    customerId INT,
    quantity INT,
    cart_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE SET NULL,
    FOREIGN KEY (customerId) REFERENCES users(id) ON DELETE SET NULL
);
