-- Need4IT Database Schema
-- Run this script in phpMyAdmin SQL tab to fully set up your database.

-- 1. Create and Select Database
CREATE DATABASE IF NOT EXISTS need4it;
USE need4it;

-- 2. Clean Up Existing Tables (Optional, but recommended for fresh setup)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS service_requests;
DROP TABLE IF EXISTS pc_builds;
DROP TABLE IF EXISTS banners;
DROP TABLE IF EXISTS coupons;
DROP TABLE IF EXISTS testimonials;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- 3. Create Tables
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    google_id VARCHAR(255),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100),
    category VARCHAR(100),
    category_id INT,
    price DECIMAL(10, 2) NOT NULL,
    offer_price DECIMAL(10, 2),
    stock INT DEFAULT 0,
    main_image VARCHAR(255),
    other_images TEXT,
    specifications TEXT,
    description TEXT,
    warranty VARCHAR(255),
    `condition` ENUM('New', 'Demo', 'Refurbished') DEFAULT 'New',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    address TEXT,
    payment_method VARCHAR(50),
    payment_status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE service_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repair_id VARCHAR(20) UNIQUE,
    user_id INT,
    device_type VARCHAR(100),
    description TEXT,
    image_url VARCHAR(255),
    pickup_date DATE,
    status ENUM('received', 'in repair', 'completed', 'delivered') DEFAULT 'received',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE pc_builds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    components JSON,
    total_price DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    subtitle VARCHAR(255),
    image_url VARCHAR(255),
    link VARCHAR(255),
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount DECIMAL(10, 2),
    type ENUM('percentage', 'fixed') DEFAULT 'fixed',
    expiry_date DATE,
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255),
    content TEXT,
    rating INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Initial Data
-- Default Admin User (Password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@need4it.com', '$2y$10$89W1h/7S5N.qHjPz1G.iU.R8c4iY5nQzI6fV.p0l6U0V7Q5Q5Q5Q5', 'admin');

-- Sample Categories
INSERT INTO categories (name, slug) VALUES 
('Laptops', 'laptops'),
('Workstations', 'workstations'),
('Custom PCs', 'custom-pcs');