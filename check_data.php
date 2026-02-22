<?php
$pdo = new PDO('mysql:host=localhost;dbname=need4it', 'root', '');
$stmt = $pdo->query('SELECT DISTINCT category FROM products');
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo json_encode($rows);
