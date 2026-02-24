<?php
include_once 'includes/config.php';
$stmt = $pdo->query("SELECT * FROM categories");
$cats = $stmt->fetchAll();
header('Content-Type: application/json');
echo json_encode($cats, JSON_PRETTY_PRINT);
?>
