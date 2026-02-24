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

<style>
    .auth-page {
        min-height: 100vh;
        background: var(--bg-white);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 64px 24px;
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
        max-width: 480px;
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
        margin-bottom: 20px;
    }

    .auth-form label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        margin-bottom: 8px;
        margin-left: 4px;
    }

    .auth-input {
        width: 100%;
        padding: 14px 20px;
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
        margin-top: 16px;
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
            <h2>Create Account</h2>
            <p>Join the digital laboratory network</p>
        </div>

        <?php if ($error): ?>
            <div class="auth-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="auth-form">
            <div class="form-group">
                <label>Full Identity</label>
                <input type="text" name="name" class="auth-input" placeholder="Operative Name" required>
            </div>

            <div class="form-group">
                <label>Electronic Mail</label>
                <input type="email" name="email" class="auth-input" placeholder="email@protocol.com" required>
            </div>
            
            <div class="form-group">
                <label>Define Access Code</label>
                <input type="password" name="password" class="auth-input" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label>Repeat Code</label>
                <input type="password" name="confirm_password" class="auth-input" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="auth-btn">Initialize Account</button>
        </form>

        <div class="auth-footer">
            <p>Already in the system? <a href="login.php">Authenticate</a></p>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
