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

CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NULL,
    reviewer_name VARCHAR(255) NOT NULL,
    rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT NOT NULL,
    is_admin_review BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 4. Initial Data
-- Default Admin User (Password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Admin', 'admin@need4it.com', '$2y$10$89W1h/7S5N.qHjPz1G.iU.R8c4iY5nQzI6fV.p0l6U0V7Q5Q5Q5Q5', 'admin');

-- Sample Categories
INSERT INTO categories (id, name, slug) VALUES 
(1, 'Laptops', 'laptops'),
(2, 'Workstations', 'workstations'),
(3, 'Custom PCs', 'custom-pcs'),
(4, 'Components', 'components'),
(5, 'Printers & Peripherals', 'printers-peripherals');

-- Sample Products
INSERT INTO products (id, name, brand, category, category_id, price, offer_price, stock, main_image, specifications, description, `condition`, featured) VALUES 
(1, 'MacBook Pro M3 Max', 'Apple', 'Laptops', 1, 349900, 319900, 5, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca4', 'M3 Max Chip, 32GB RAM, 1TB SSD', 'The most powerful MacBook ever.', 'New', 1),
(2, 'Dell XPS 15', 'Dell', 'Laptops', 1, 185000, 169900, 8, 'https://images.unsplash.com/photo-1593642632823-8f785bc67885', 'i9 13th Gen, 32GB RAM, 1TB SSD, RTX 4050', 'Slim, powerful, and stunning display.', 'New', 1),
(3, 'ASUS ROG Strix G16', 'ASUS', 'Laptops', 1, 145000, 135000, 12, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302', 'i7 13th Gen, 16GB RAM, RTX 4060', 'Dominate the game with high-end performance.', 'New', 0),
(4, 'ThinkPad X1 Carbon', 'Lenovo', 'Laptops', 1, 135000, 45000, 15, 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef', 'i7 12th Gen, 16GB RAM, 512GB SSD', 'Legendary durability and professional performance.', 'Refurbished', 0),
(5, 'HP Spectre x360', 'HP', 'Laptops', 1, 125000, 115000, 6, 'https://images.unsplash.com/photo-1525547718571-03b05761adbe', 'i7 13th Gen, 16GB RAM, Touchscreen', 'Versatile 2-in-1 with premium design.', 'Demo', 0),
(6, 'Mac Studio M2 Ultra', 'Apple', 'Workstations', 2, 399900, 349900, 3, 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea', 'M2 Ultra, 64GB RAM, 2TB SSD', 'The creative powerhouse for professionals.', 'New', 1),
(7, 'Precision Pro X1', 'Dell', 'Workstations', 2, 210000, 185000, 4, 'https://images.unsplash.com/photo-1593642702749-b7d2a804fbcf', 'Xeon Processor, 64GB ECC RAM, RTX A4000', 'Enterprise grade performance for engineering.', 'New', 1),
(8, 'Vision 24" AIO', 'Need4IT', 'Workstations', 2, 85000, 74900, 10, 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97', 'i7 12th Gen, 16GB RAM, 24" 4K Display', 'Minimal setup, maximum performance. Perfect for office.', 'New', 0),
(9, 'iMac 24-inch M3', 'Apple', 'Workstations', 2, 149900, 139900, 7, 'https://images.unsplash.com/photo-1527443213868-4721714bc024', 'M3 Chip, 16GB RAM, 512GB SSD', 'Best all-in-one for creative workspaces.', 'New', 0),
(10, 'ThinkCentre M90q', 'Lenovo', 'Workstations', 2, 65000, 55000, 20, 'https://images.unsplash.com/photo-1593642702821-c856728a1ec1', 'i5 12th Gen, 8GB RAM, 256GB SSD', 'Compact and powerful business desktop.', 'New', 0),
(11, 'GeForce RTX 4090', 'NVIDIA', 'Components', 4, 195000, 185000, 2, 'https://images.unsplash.com/photo-1591488320449-011701bb6704', '24GB GDDR6X, Triple Fan', 'The ultimate graphics card for 4K gaming.', 'New', 1),
(12, 'Intel Core i9-14900K', 'Intel', 'Components', 4, 65000, 58000, 15, 'https://images.unsplash.com/photo-1591488320449-011701bb6704', '24 Cores, 6.0 GHz Turbo', 'The world fastest desktop processor.', 'New', 0),
(13, 'Samsung 990 Pro 2TB', 'Samsung', 'Components', 4, 22000, 18500, 30, 'https://images.unsplash.com/photo-1580076249660-a7f340f18bd5', 'NVMe Gen4, 7450 MB/s Read', 'Maximum speed for your storage needs.', 'New', 0),
(14, 'Corsair Vengeance 32GB', 'Corsair', 'Components', 4, 15000, 12500, 25, 'https://images.unsplash.com/photo-1591485121415-397c726354b6', 'DDR5 6000MHz, RGB', 'High-performance memory with style.', 'New', 0),
(15, 'ASUS ROG Z790', 'ASUS', 'Components', 4, 55000, 48000, 10, 'https://images.unsplash.com/photo-1587202372775-e229f170b998', 'Intel Z790 Chipset, WiFi 7', 'Extreme performance and aesthetics.', 'New', 0),
(16, 'LaserJet Pro M404dn', 'HP', 'Printers & Peripherals', 5, 32000, 28500, 15, 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6', 'Duplex Printing, Monochrome', 'Fast and reliable laser printer for office.', 'New', 0),
(17, 'Epson EcoTank L3210', 'Epson', 'Printers & Peripherals', 5, 18000, 15500, 12, 'https://images.unsplash.com/photo-1612815555544-d4f399497eef', 'Color Printing, Ink Tank', 'Low-cost color printing with zero waste.', 'New', 0),
(18, 'Logitech MX Master 3S', 'Logitech', 'Printers & Peripherals', 5, 12000, 9500, 40, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46', 'Silent clicks, 8K DPI Sensor', 'The ultimate mouse for productivity.', 'New', 1),
(19, 'Keychron K2 V2', 'Keychron', 'Printers & Peripherals', 5, 9500, 8500, 25, 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae', 'Wireless, Mechanical, RGB', 'Best mechanical keyboard for Mac/Windows.', 'New', 0),
(20, 'UltraSharp 27" 4K', 'Dell', 'Printers & Peripherals', 5, 45000, 38000, 10, 'https://images.unsplash.com/photo-1527443213868-4721714bc024', '4K UHD, 100% sRGB, IPS', 'Professional monitor for color accuracy.', 'New', 0);

-- Sample Banners
INSERT INTO banners (id, title, subtitle, image_url, link, active) VALUES 
(1, 'Mac Studio M2 Ultra', 'Extreme performance for pros.', 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea', 'product-details.php?id=6', 1),
(2, 'Next-Gen Gaming', 'RTX 4090 Now in Stock & Ready to Ship.', 'https://images.unsplash.com/photo-1591488320449-011701bb6704', 'product-details.php?id=11', 1),
(3, 'Professional Repairs', 'Expert care at your doorstep.', 'https://images.unsplash.com/photo-1525547718571-03b05761adbe', 'services.php', 1);
