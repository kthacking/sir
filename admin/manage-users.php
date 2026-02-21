<?php
include_once '../includes/config.php';
requireAdmin();

$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Need4IT Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<main class="main-content">
    <header class="admin-header">
        <h1>User Directory</h1>
    </header>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td style="font-weight: 600;"><?php echo htmlspecialchars($u['name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $u['role'] == 'admin' ? 'badge-warning' : 'badge-success'; ?>" style="font-size: 11px;">
                            <?php echo ucfirst($u['role']); ?>
                        </span>
                    </td>
                    <td style="color: var(--text-grey);"><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                    <td style="text-align: right;">
                        <button class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; color: #ff3b30;"><i class="fas fa-user-slash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
