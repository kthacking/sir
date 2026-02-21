<?php
include_once '../includes/config.php';
requireAdmin();

$stmt = $pdo->query("SELECT o.*, u.name as customer_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | Need4IT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<main class="main-content">
    <header class="admin-header">
        <h1>Customer Orders</h1>
    </header>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $o): ?>
                <tr>
                    <td><strong>#ORD-<?php echo $o['id']; ?></strong></td>
                    <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                    <td><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                    <td><span style="font-weight: 600;">₹<?php echo number_format($o['total_amount']); ?></span></td>
                    <td><span class="status-badge badge-success"><?php echo ucfirst($o['status']); ?></span></td>
                    <td style="text-align: right;">
                        <button class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;"><i class="fas fa-eye"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
