<?php
require_once 'includes/config.php';

try {
    // 1. Add expiry_date to banners table if it doesn't exist
    $stmt = $pdo->query("SHOW COLUMNS FROM banners LIKE 'expiry_date'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE banners ADD COLUMN expiry_date DATETIME NULL AFTER link");
        echo "Successfully added 'expiry_date' column to 'banners' table.<br>";
    } else {
        echo "'expiry_date' column already exists in 'banners' table.<br>";
    }

    // 2. Ensure testimonials table exists (as referenced in previous summaries)
    $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_name VARCHAR(255),
        content TEXT,
        rating INT DEFAULT 5,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Successfully ensured 'testimonials' table exists.<br>";

    echo "Database setup complete! You can now use the banner management system.";
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
