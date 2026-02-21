<?php
include_once '../includes/config.php';
requireAdmin();

// Fetch summary metrics
$stmt = $pdo->query("SELECT SUM(total_amount) FROM orders");
$total_revenue = $stmt->fetchColumn() ?: 0;

$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$total_sales = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM service_requests WHERE status = 'completed'");
$repairs_done = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports | Need4IT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<main class="main-content">
    <header class="admin-header">
        <h1>Analytical Reports</h1>
        <button class="btn btn-outline" onclick="window.print()"><i class="fas fa-download"></i> Export PDF</button>
    </header>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total GTV (Gross Transaction Value)</span>
            <div class="stat-value">₹<?php echo number_format($total_revenue); ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Order Volume</span>
            <div class="stat-value"><?php echo $total_sales; ?> units</div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Service Conversion</span>
            <div class="stat-value"><?php echo $repairs_done; ?> repairs</div>
        </div>
    </div>

    <div class="card" style="padding: 40px; text-align: center;">
        <i class="fas fa-chart-area" style="font-size: 60px; color: #eee; margin-bottom: 20px;"></i>
        <h3>Advanced Analytics coming soon</h3>
        <p style="color: var(--text-grey); margin-top: 10px;">We're working on deep insights for your business.</p>
    </div>
</main>
</body>
</html>
