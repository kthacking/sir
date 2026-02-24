<?php
include 'includes/config.php';
session_start();

if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    header('Location: my-orders.php');
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: my-orders.php');
    exit();
}

$stmt_items = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order_id; ?></title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #1d1d1f; padding: 40px; margin: 0; }
        .invoice-box { max-width: 800px; margin: auto; border: 1px solid #eee; padding: 30px; border-radius: 10px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0071e3; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: 800; }
        .logo span { color: #0071e3; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info-col { width: 45%; }
        .info-col h4 { margin: 0 0 10px 0; color: #86868b; text-transform: uppercase; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { text-align: left; background: #fbfbfd; padding: 12px; font-size: 12px; color: #86868b; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #f5f5f7; font-size: 14px; }
        .total-section { text-align: right; }
        .total-row { display: flex; justify-content: flex-end; gap: 20px; font-size: 16px; margin-bottom: 10px; }
        .grand-total { font-size: 24px; font-weight: 800; color: #0071e3; margin-top: 10px; }
        @media print {
            .btn-print { display: none; }
        }
        .btn-print {
            background: #0071e3; color: #fff; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 700; cursor: pointer; margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <button class="btn-print" onclick="window.print()">Print Invoice</button>
        <div class="header">
            <div class="logo">Need4<span>IT</span></div>
            <div style="text-align: right;">
                <div style="font-weight: 700;">INVOICE</div>
                <div style="font-size: 14px; color: #86868b;">#<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-col">
                <h4>Billed To</h4>
                <div style="font-weight: 700;"><?php echo $_SESSION['user_name']; ?></div>
                <div style="font-size: 14px; line-height: 1.6; margin-top: 5px;"><?php echo nl2br(htmlspecialchars($order['address'])); ?></div>
            </div>
            <div class="info-col" style="text-align: right;">
                <h4>Order Date</h4>
                <div style="font-weight: 700;"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></div>
                <h4 style="margin-top: 20px;">Payment Status</h4>
                <div style="font-weight: 700; color: <?php echo $order['payment_status'] == 'Paid' ? '#34c759' : '#ff3b30'; ?>"><?php echo $order['payment_status']; ?></div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td style="font-weight: 600;"><?php echo $item['name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                    <td style="text-align: right; font-weight: 600;">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <div style="color: #86868b;">Subtotal</div>
                <div style="font-weight: 600; width: 120px;">₹<?php echo number_format($order['total'], 2); ?></div>
            </div>
            <div class="total-row">
                <div style="color: #86868b;">Shipping</div>
                <div style="font-weight: 600; width: 120px;">₹0.00</div>
            </div>
            <div class="total-row grand-total">
                <div>TOTAL</div>
                <div style="width: 150px;">₹<?php echo number_format($order['total'], 2); ?></div>
            </div>
        </div>

        <div style="margin-top: 60px; padding-top: 20px; border-top: 1px solid #f5f5f7; font-size: 12px; color: #86868b; text-align: center;">
            Thank you for choosing Need4IT. For any technical support, please contact help@need4it.com
        </div>
    </div>
</body>
</html>
