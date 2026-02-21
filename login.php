<?php
include_once 'includes/config.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        
        if (isset($_SESSION['redirect_after_login'])) {
            $url = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header("Location: " . $url);
        } else {
            if ($user['role'] === 'admin') {
                redirect('admin/dashboard.php');
            } else {
                redirect('index.php');
            }
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<?php include 'includes/header.php'; ?>

<main style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 100px 20px;">
    <div class="glass-card" style="width: 100%; max-width: 400px; padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 28px;">Sign In</h2>
            <p style="font-size: 15px;">Use your Need4IT account.</p>
        </div>

        <?php if ($error): ?>
            <div style="background: rgba(255, 59, 48, 0.1); color: #ff3b30; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div style="margin-bottom: 20px;">
                <input type="email" name="email" placeholder="Email" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; font-size: 16px; outline: none;">
            </div>
            <div style="margin-bottom: 20px;">
                <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; font-size: 16px; outline: none;">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; margin-top: 10px;">Sign In</button>
        </form>

        <div style="text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <p style="font-size: 14px;">Don't have an account? <a href="register.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Create one now</a></p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
