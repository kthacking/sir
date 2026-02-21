-- Need4IT Sample Data Script
-- This script populates the database with 20 high-quality records.

USE need4it;

-- 1. Clear existing data in correct order to avoid FK issues
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM order_items;
DELETE FROM orders;
DELETE FROM products;
DELETE FROM categories;
DELETE FROM banners;
-- Optional: Reset auto-increment counters (TRUNCATE can't be used due to FKs)
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE products AUTO_INCREMENT = 1;
ALTER TABLE banners AUTO_INCREMENT = 1;
SET FOREIGN_KEY_CHECKS = 1;

-- 2. Insert Categories with Explicit IDs
INSERT INTO categories (id, name, slug) VALUES 
(1, 'Laptops', 'laptops'),
(2, 'Workstations', 'workstations'),
(3, 'Custom PCs', 'custom-pcs'),
(4, 'Components', 'components'),
(5, 'Printers & Peripherals', 'printers-peripherals');

-- 3. Insert 20 Sample Products with Explicit IDs
-- This ensures the Banners and Links work perfectly.
INSERT INTO products (id, name, brand, category, category_id, price, offer_price, stock, main_image, specifications, description, `condition`, featured) VALUES 
-- Laptops (ID 1-5)
(1, 'MacBook Pro M3 Max', 'Apple', 'Laptops', 1, 349900, 319900, 5, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca4', 'M3 Max Chip, 32GB RAM, 1TB SSD', 'The most powerful MacBook ever.', 'New', 1),
(2, 'Dell XPS 15', 'Dell', 'Laptops', 1, 185000, 169900, 8, 'https://images.unsplash.com/photo-1593642632823-8f785bc67885', 'i9 13th Gen, 32GB RAM, 1TB SSD, RTX 4050', 'Slim, powerful, and stunning display.', 'New', 1),
(3, 'ASUS ROG Strix G16', 'ASUS', 'Laptops', 1, 145000, 135000, 12, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302', 'i7 13th Gen, 16GB RAM, RTX 4060', 'Dominate the game with high-end performance.', 'New', 0),
(4, 'ThinkPad X1 Carbon', 'Lenovo', 'Laptops', 1, 135000, 45000, 15, 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef', 'i7 12th Gen, 16GB RAM, 512GB SSD', 'Legendary durability and professional performance.', 'Refurbished', 0),
(5, 'HP Spectre x360', 'HP', 'Laptops', 1, 125000, 115000, 6, 'https://images.unsplash.com/photo-1525547718571-03b05761adbe', 'i7 13th Gen, 16GB RAM, Touchscreen', 'Versatile 2-in-1 with premium design.', 'Demo', 0),

-- Workstations (ID 6-10)
(6, 'Mac Studio M2 Ultra', 'Apple', 'Workstations', 2, 399900, 349900, 3, 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea', 'M2 Ultra, 64GB RAM, 2TB SSD', 'The creative powerhouse for professionals.', 'New', 1),
(7, 'Precision Pro X1', 'Dell', 'Workstations', 2, 210000, 185000, 4, 'https://images.unsplash.com/photo-1593642702749-b7d2a804fbcf', 'Xeon Processor, 64GB ECC RAM, RTX A4000', 'Enterprise grade performance for engineering.', 'New', 1),
(8, 'Vision 24" AIO', 'Need4IT', 'Workstations', 2, 85000, 74900, 10, 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97', 'i7 12th Gen, 16GB RAM, 24" 4K Display', 'Minimal setup, maximum performance. Perfect for office.', 'New', 0),
(9, 'iMac 24-inch M3', 'Apple', 'Workstations', 2, 149900, 139900, 7, 'https://images.unsplash.com/photo-1527443213868-4721714bc024', 'M3 Chip, 16GB RAM, 512GB SSD', 'Best all-in-one for creative workspaces.', 'New', 0),
(10, 'ThinkCentre M90q', 'Lenovo', 'Workstations', 2, 65000, 55000, 20, 'https://images.unsplash.com/photo-1593642702821-c856728a1ec1', 'i5 12th Gen, 8GB RAM, 256GB SSD', 'Compact and powerful business desktop.', 'New', 0),

-- Components (ID 11-15)
(11, 'GeForce RTX 4090', 'NVIDIA', 'Components', 4, 195000, 185000, 2, 'https://images.unsplash.com/photo-1591488320449-011701bb6704', '24GB GDDR6X, Triple Fan', 'The ultimate graphics card for 4K gaming.', 'New', 1),
(12, 'Intel Core i9-14900K', 'Intel', 'Components', 4, 65000, 58000, 15, 'https://images.unsplash.com/photo-1591488320449-011701bb6704', '24 Cores, 6.0 GHz Turbo', 'The world fastest desktop processor.', 'New', 0),
(13, 'Samsung 990 Pro 2TB', 'Samsung', 'Components', 4, 22000, 18500, 30, 'https://images.unsplash.com/photo-1580076249660-a7f340f18bd5', 'NVMe Gen4, 7450 MB/s Read', 'Maximum speed for your storage needs.', 'New', 0),
(14, 'Corsair Vengeance 32GB', 'Corsair', 'Components', 4, 15000, 12500, 25, 'https://images.unsplash.com/photo-1591485121415-397c726354b6', 'DDR5 6000MHz, RGB', 'High-performance memory with style.', 'New', 0),
(15, 'ASUS ROG Z790', 'ASUS', 'Components', 4, 55000, 48000, 10, 'https://images.unsplash.com/photo-1587202372775-e229f170b998', 'Intel Z790 Chipset, WiFi 7', 'Extreme performance and aesthetics.', 'New', 0),

-- Printers & Peripherals (ID 16-20)
(16, 'LaserJet Pro M404dn', 'HP', 'Printers & Peripherals', 5, 32000, 28500, 15, 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6', 'Duplex Printing, Monochrome', 'Fast and reliable laser printer for office.', 'New', 0),
(17, 'Epson EcoTank L3210', 'Epson', 'Printers & Peripherals', 5, 18000, 15500, 12, 'https://images.unsplash.com/photo-1612815555544-d4f399497eef', 'Color Printing, Ink Tank', 'Low-cost color printing with zero waste.', 'New', 0),
(18, 'Logitech MX Master 3S', 'Logitech', 'Printers & Peripherals', 5, 12000, 9500, 40, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46', 'Silent clicks, 8K DPI Sensor', 'The ultimate mouse for productivity.', 'New', 1),
(19, 'Keychron K2 V2', 'Keychron', 'Printers & Peripherals', 5, 9500, 8500, 25, 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae', 'Wireless, Mechanical, RGB', 'Best mechanical keyboard for Mac/Windows.', 'New', 0),
(20, 'UltraSharp 27" 4K', 'Dell', 'Printers & Peripherals', 5, 45000, 38000, 10, 'https://images.unsplash.com/photo-1527443213868-4721714bc024', '4K UHD, 100% sRGB, IPS', 'Professional monitor for color accuracy.', 'New', 0);

-- 4. Insert Banners
INSERT INTO banners (id, title, subtitle, image_url, link, active) VALUES 
(1, 'Mac Studio M2 Ultra', 'Extreme performance for pros.', 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea', 'product-details.php?id=6', 1),
(2, 'Next-Gen Gaming', 'RTX 4090 Now in Stock & Ready to Ship.', 'https://images.unsplash.com/photo-1591488320449-011701bb6704', 'product-details.php?id=11', 1),
(3, 'Professional Repairs', 'Expert care at your doorstep.', 'https://images.unsplash.com/photo-1525547718571-03b05761adbe', 'services.php', 1);
