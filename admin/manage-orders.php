<?php
include_once '../includes/config.php';
requireAdmin();

// Handle Status Update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);
    header("Location: manage-orders.php?msg=updated");
    exit();
}

// Fetch Orders with User Info
$stmt = $pdo->query("SELECT o.*, u.name as customer_name, u.email as customer_email 
                     FROM orders o 
                     LEFT JOIN users u ON o.user_id = u.id 
                     ORDER BY o.id DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For each order, fetch items
foreach ($orders as &$o) {
    $stmt = $pdo->prepare("SELECT oi.*, p.name, p.main_image 
                           FROM order_items oi 
                           JOIN products p ON oi.product_id = p.id 
                           WHERE oi.order_id = ?");
    $stmt->execute([$o['id']]);
    $o['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
unset($o);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .order-table img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 4px;
            background: #f8f9fa;
            border: 1px solid #eee;
        }
        .order-expand-content {
            background: #fcfcfd;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .item-row {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .item-row:last-child { border-bottom: none; }
        .status-select {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 13px;
            background: #fff;
        }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-processing { background: #cce5ff; color: #004085; }
        .badge-shipped { background: #d4edda; color: #155724; }
        .badge-delivered { background: #0071e3; color: #fff; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 15px;
        }
        .info-label {
            font-size: 12px;
            color: #86868b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <header class="admin-header">
        <h1>Order Management</h1>
    </header>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
        <div style="background: #dcfce7; color: #16a34a; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
            Order status updated successfully!
        </div>
    <?php endif; ?>

    <div class="card">
        <table class="order-table">
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
                <tr style="cursor: pointer;" onclick="toggleItems(<?php echo $o['id']; ?>)">
                    <td><strong>#ORD-<?php echo $o['id']; ?></strong></td>
                    <td>
                        <div style="font-weight: 500;"><?php echo htmlspecialchars($o['customer_name'] ?? 'Guest'); ?></div>
                        <div style="font-size: 12px; color: #86868b;"><?php echo htmlspecialchars($o['customer_email'] ?? ''); ?></div>
                    </td>
                    <td><?php echo date('d M Y, h:i A', strtotime($o['created_at'])); ?></td>
                    <td style="font-weight: 700;">₹<?php echo number_format($o['total']); ?></td>
                    <td>
                        <?php 
                        $statusClass = 'badge-' . strtolower($o['status']);
                        ?>
                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo $o['status']; ?></span>
                    </td>
                    <td style="text-align: right;">
                        <button class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;">
                            <i class="fas fa-chevron-down" id="icon-<?php echo $o['id']; ?>"></i> Details
                        </button>
                    </td>
                </tr>
                <tr id="items-<?php echo $o['id']; ?>" style="display: none;">
                    <td colspan="6" style="padding: 0;">
                        <div class="order-expand-content">
                            <div class="details-grid">
                                <div>
                                    <h4 style="margin-bottom: 15px; font-size: 14px;">Order Items</h4>
                                    <?php foreach($o['items'] as $item): ?>
                                    <div class="item-row">
                                        <?php 
                                        $img = $item['main_image'];
                                        if (empty($img)) $img = 'assets/placeholder.png';
                                        if (strpos($img, 'http') === false) $img = '../' . $img;
                                        ?>
                                        <img src="<?php echo $img; ?>" alt="">
                                        <div style="flex-grow: 1;">
                                            <div style="font-weight: 500; font-size: 14px;"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <div style="font-size: 12px; color: #86868b;">Qty: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price']); ?></div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <div>
                                    <h4 style="margin-bottom: 15px; font-size: 14px;">Shipping & Payment</h4>
                                    <div style="margin-bottom: 15px;">
                                        <div class="info-label">Shipping Address</div>
                                        <div style="font-size: 14px; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($o['address'])); ?></div>
                                    </div>
                                    <div style="display: flex; gap: 30px; margin-bottom: 20px;">
                                        <div>
                                            <div class="info-label">Payment Method</div>
                                            <div style="font-size: 14px; text-transform: uppercase; font-weight: 600;"><?php echo $o['payment_method']; ?></div>
                                        </div>
                                        <div>
                                            <div class="info-label">Transaction ID</div>
                                            <div style="font-size: 14px; font-family: monospace;"><?php echo $o['payment_id'] ?? 'N/A'; ?></div>
                                        </div>
                                    </div>
                                    
                                    <form action="" method="POST" style="padding-top: 15px; border-top: 1px solid #eee;">
                                        <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                        <div class="info-label">Update Order Status</div>
                                        <div style="display: flex; gap: 10px;">
                                            <select name="status" class="status-select" style="flex-grow: 1;">
                                                <option value="Pending" <?php echo $o['status']=='Pending'?'selected':''; ?>>Pending</option>
                                                <option value="Processing" <?php echo $o['status']=='Processing'?'selected':''; ?>>Processing</option>
                                                <option value="Shipped" <?php echo $o['status']=='Shipped'?'selected':''; ?>>Shipped</option>
                                                <option value="Delivered" <?php echo $o['status']=='Delivered'?'selected':''; ?>>Delivered</option>
                                                <option value="Cancelled" <?php echo $o['status']=='Cancelled'?'selected':''; ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-primary" style="padding: 6px 15px; font-size: 12px;">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function toggleItems(oid) {
    const row = document.getElementById('items-' + oid);
    const icon = document.getElementById('icon-' + oid);
    if (row.style.display === 'none') {
        row.style.display = 'table-row';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        row.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}
</script>

</body>
</html>
