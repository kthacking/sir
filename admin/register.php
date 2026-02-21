<?php
include_once '../includes/config.php';

$error = "";
$success = "";

// Security: Define a Master Key for Admin Registration
// In a real app, this should be in a .env file or DB config
define('ADMIN_MASTER_KEY', 'need4it_admin_2024');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $master_key = $_POST['master_key'];
    
    if ($master_key !== ADMIN_MASTER_KEY) {
        $error = "Invalid Master Registration Key.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            if ($stmt->execute([$name, $email, $hashed_password])) {
                $success = "Admin account created successfully! <a href='../login.php'>Login here</a>";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration | Need4IT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #0071e3; --text-dark: #1d1d1f; --text-grey: #86868b; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f5f5f7; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); width: 100%; max-width: 450px; }
        .logo { font-weight: 700; font-size: 24px; text-align: center; margin-bottom: 30px; }
        input { width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; margin-bottom: 20px; font-size: 16px; outline: none; }
        input:focus { border-color: var(--primary); }
        .btn { width: 100%; padding: 15px; background: var(--primary); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; text-align: center; }
        .alert-error { background: rgba(255, 59, 48, 0.1); color: #ff3b30; }
        .alert-success { background: rgba(52, 199, 89, 0.1); color: #2e7d32; }
    </style>
</head>
<body>

<div class="card">
    <div class="logo">Need4IT <span style="color: var(--primary);">Admin</span></div>
    <p style="text-align: center; color: var(--text-grey); margin-bottom: 30px;">Authorized Access Only</p>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Admin Name" required>
        <input type="email" name="email" placeholder="Admin Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="text" name="master_key" placeholder="Master Registration Key" required>
        
        <button type="submit" class="btn">Register Admin</button>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="../login.php" style="color: var(--text-grey); font-size: 14px; text-decoration: none;">Back to Login</a>
    </div>
</div>

</body>
</html>
