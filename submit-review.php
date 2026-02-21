<?php
include_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $rating = $_POST['rating'] ?? 0;
    $review_text = trim($_POST['review_text'] ?? '');
    
    // Basic Validation
    if (!$product_id || !$rating || empty($review_text)) {
        redirect("product-details.php?id=$product_id&msg=review_error");
    }

    if (!isLoggedIn()) {
        redirect('login.php?msg=login_required');
    }

    // Fetch user name
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $user_name = $user['name'] ?? 'User';

    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, reviewer_name, rating, review_text, is_admin_review) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->execute([$product_id, $_SESSION['user_id'], $user_name, $rating, $review_text]);
        
        redirect("product-details.php?id=$product_id&msg=review_success");
    } catch (PDOException $e) {
        redirect("product-details.php?id=$product_id&msg=db_error");
    }
} else {
    redirect('index.php');
}
?>
