<?php
include_once '../includes/config.php';
requireAdmin();

// Handle Status Update
if (isset($_POST['update_status'])) {
    $stmt = $pdo->prepare("UPDATE service_requests SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['request_id']]);
    redirect('admin/manage-services.php?msg=updated');
}

$stmt = $pdo->query("SELECT s.*, u.name as customer_name FROM service_requests s JOIN users u ON s.user_id = u.id ORDER BY s.id DESC");
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Services | Need4IT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<main class="main-content">
    <header class="admin-header">
        <h1>Service Requests</h1>
    </header>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Repair ID</th>
                    <th>Customer</th>
                    <th>Device</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                    <tr><td colspan="6" style="text-align: center; color: var(--text-grey); padding: 40px;">No service requests found.</td></tr>
                <?php else: ?>
                    <?php foreach($requests as $r): ?>
                    <tr>
                        <td><strong><?php echo $r['repair_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($r['customer_name']); ?></td>
                        <td><span class="status-badge" style="background: #f0f0f0;"><?php echo $r['device_type']; ?></span></td>
                        <td>
                            <div title="<?php echo htmlspecialchars($r['description']); ?>" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 13px; color: var(--text-grey);">
                                <?php echo htmlspecialchars($r['description']); ?>
                            </div>
                        </td>
                        <td>
                            <form action="manage-services.php" method="POST" style="display: flex; gap: 8px;">
                                <input type="hidden" name="request_id" value="<?php echo $r['id']; ?>">
                                <select name="status" class="status-select" style="margin-bottom: 0; padding: 6px 12px; font-size: 12px; width: auto;">
                                    <option value="received" <?php echo $r['status'] == 'received' ? 'selected' : ''; ?>>Received</option>
                                    <option value="in repair" <?php echo $r['status'] == 'in repair' ? 'selected' : ''; ?>>In Repair</option>
                                    <option value="completed" <?php echo $r['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="delivered" <?php echo $r['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary" style="padding: 6px 12px; font-size: 11px;">Update</button>
                            </form>
                        </td>
                        <td style="text-align: right;">
                            <button class="btn btn-outline" style="padding: 6px 12px; font-size: 12px;"><i class="fas fa-print"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
