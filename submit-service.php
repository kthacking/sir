<?php
include 'includes/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $company_name = $_POST['company_name'] ?? '';
    $service_type = $_POST['service_type'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($full_name) || empty($phone) || empty($email) || empty($service_type) || empty($message)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=error&detail=empty_fields");
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO service_inquiries (full_name, phone, email, company_name, service_type, message) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$full_name, $phone, $email, $company_name, $service_type, $message]);
        
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=success");
        exit();
    } catch (PDOException $e) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=error&detail=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: services.php");
    exit();
}
?>
