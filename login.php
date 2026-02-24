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

<style>
    .auth-page {
        min-height: 100vh;
        background: var(--bg-white);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 48px 24px;
    }

    .auth-overlay {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0,0,0,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,0.02) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
    }

    .auth-card {
        width: 100%;
        max-width: 440px;
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        padding: 48px;
        position: relative;
        z-index: 10;
        box-shadow: var(--shadow-lg);
    }

    .auth-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .auth-header h2 {
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: 32px;
        letter-spacing: -0.04em;
        margin-bottom: 8px;
        color: var(--text-dark);
    }

    .auth-header p {
        color: var(--text-muted);
        font-size: 15px;
    }

    .auth-form .form-group {
        margin-bottom: 24px;
    }

    .auth-form label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 8px;
        margin-left: 4px;
    }

    .auth-input {
        width: 100%;
        padding: 16px 20px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        font-size: 15px;
        color: var(--text-dark);
        outline: none;
        transition: all 0.25s var(--ease);
    }

    .auth-input:focus {
        border-color: var(--text-dark);
        background: var(--bg-white);
        box-shadow: 0 0 0 4px var(--accent-muted);
    }

    .auth-btn {
        width: 100%;
        padding: 18px;
        background: var(--text-dark);
        color: white;
        border: none;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s var(--ease);
        margin-top: 8px;
    }

    .auth-btn:hover {
        background: var(--accent);
        transform: translateY(-2px);
    }

    .auth-error {
        background: #FFF1F0;
        border: 1px solid #FFA39E;
        color: #CF1322;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        margin-bottom: 24px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .auth-footer {
        margin-top: 40px;
        padding-top: 32px;
        border-top: 1px solid var(--border);
        text-align: center;
    }

    .auth-footer p {
        font-size: 14px;
        color: var(--text-mid);
    }

    .auth-footer a {
        color: var(--accent);
        font-weight: 700;
        text-decoration: none;
        transition: color 0.2s;
    }

    .auth-footer a:hover {
        color: var(--text-dark);
    }
</style>

<main class="auth-page">
    <div class="auth-overlay"></div>
    
    <div class="auth-card animate-fade-up">
        <div class="auth-header">
            <h2>Sign In</h2>
            <p>Access your digital laboratory</p>
        </div>

        <?php if ($error): ?>
            <div class="auth-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="auth-form">
            <div class="form-group">
                <label>Electronic Mail</label>
                <input type="email" name="email" class="auth-input" placeholder="john@company.com" required>
            </div>
            
            <div class="form-group">
                <label>Access Code</label>
                <input type="password" name="password" class="auth-input" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="auth-btn">Verify Credentials</button>
        </form>

        <div class="auth-footer">
            <p>New operative? <a href="register.php">Initialize Account</a></p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
