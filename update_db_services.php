<?php
include 'includes/config.php';

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS service_inquiries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(255) NOT NULL,
        company_name VARCHAR(255),
        service_type VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('Pending', 'Contacted', 'Closed') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'service_inquiries' created successfully!";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
