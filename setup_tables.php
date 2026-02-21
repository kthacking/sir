<?php
/**
 * DATABASE SETUP SCRIPT
 * Run this by visiting: http://localhost/new/sir/setup_tables.php
 */

include_once 'includes/config.php';

try {
    echo "<h2>Starting Database Setup...</h2>";

    // 1. Create service_requests table
    $sql = "CREATE TABLE IF NOT EXISTS service_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        repair_id VARCHAR(20) UNIQUE,
        user_id INT,
        device_type VARCHAR(100),
        description TEXT,
        image_url VARCHAR(255),
        pickup_date DATE,
        status ENUM('received', 'in repair', 'completed', 'delivered') DEFAULT 'received',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (user_id)
    )";
    $pdo->exec($sql);
    echo "✅ Table 'service_requests' created or already exists.<br>";

    // 2. Ensure users table has role column
    $pdo->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
    echo "✅ Users table structure verified.<br>";

    // 3. Create products table if missing
    $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        brand VARCHAR(100),
        category VARCHAR(100),
        price DECIMAL(10, 2) NOT NULL,
        offer_price DECIMAL(10, 2),
        stock INT DEFAULT 0,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "✅ Table 'products' created or already exists.<br>";

    echo "<br><h3 style='color: green;'>Success! Database is now ready.</h3>";
    echo "<a href='index.php'>Go to Homepage</a>";

} catch (PDOException $e) {
    echo "<h3 style='color: red;'>Database Error:</h3>" . $e->getMessage();
}
?>
