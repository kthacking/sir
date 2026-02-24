<?php
include_once '../includes/config.php';
requireAdmin();

// Fetch summary metrics
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$product_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM service_requests");
$service_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'");
$user_count = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$order_count = $stmt->fetchColumn();

// Fetch recent activity
$stmt = $pdo->query("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5");
$recent_orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Need4IT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <header class="admin-header">
        <h1>Dashboard</h1>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="text-align: right;">
                <span style="display: block; font-size: 14px; font-weight: 600;"><?php echo $_SESSION['user_name']; ?></span>
                <span style="display: block; font-size: 12px; color: var(--text-grey);">Administrator</span>
            </div>
            <div style="width: 44px; height: 44px; background: var(--primary-light); color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                <?php echo substr($_SESSION['user_name'], 0, 1) . substr(strrchr($_SESSION['user_name'], " "), 1, 1); ?>
            </div>
        </div>
    </header>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total Orders</span>
            <div class="stat-value"><?php echo $order_count; ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Inventory Items</span>
            <div class="stat-value"><?php echo $product_count; ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Service Requests</span>
            <div class="stat-value"><?php echo $service_count; ?></div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Total Users</span>
            <div class="stat-value"><?php echo $user_count; ?></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 style="font-weight: 600;">Recent Orders</h3>
            <a href="manage-orders.php" class="btn btn-outline" style="padding: 6px 15px;">View All</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recent_orders)): ?>
                    <tr><td colspan="5" style="text-align: center; color: var(--text-grey); padding: 40px;">No recent orders.</td></tr>
                <?php else: ?>
                    <?php foreach($recent_orders as $order): ?>
                    <tr>
                        <td><strong>#ORD-<?php echo $order['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                        <td>₹<?php echo number_format($order['total']); ?></td>
                        <td><span class="status-badge badge-success"><?php echo ucfirst($order['status']); ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
