<?php
include_once 'includes/config.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            if ($stmt->execute([$name, $email, $hashed_password])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_name'] = $name;
                $_SESSION['role'] = 'user';
                redirect('index.php');
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<main style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 100px 20px;">
    <div class="glass-card" style="width: 100%; max-width: 450px; padding: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 28px;">Create Account</h2>
            <p style="font-size: 15px;">Join the Need4IT community.</p>
        </div>

        <?php if ($error): ?>
            <div style="background: rgba(255, 59, 48, 0.1); color: #ff3b30; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div style="margin-bottom: 20px;">
                <input type="text" name="name" placeholder="Full Name" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; font-size: 16px; outline: none;">
            </div>
            <div style="margin-bottom: 20px;">
                <input type="email" name="email" placeholder="Email Address" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; font-size: 16px; outline: none;">
            </div>
            <div style="margin-bottom: 20px;">
                <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; font-size: 16px; outline: none;">
            </div>
            <div style="margin-bottom: 20px;">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; font-size: 16px; outline: none;">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; margin-top: 10px;">Create Account</button>
        </form>

        <div style="text-align: center; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <p style="font-size: 14px;">Already have an account? <a href="login.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign In</a></p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
