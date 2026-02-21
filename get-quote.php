<?php
include_once 'includes/config.php';
include 'includes/header.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $type = $_POST['type'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO quote_requests (name, email, phone, requirement_type, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $type, $message])) {
            $success = "Thank you! Your quote request has been received. Our team will contact you within 24 hours.";
        } else {
            $error = "Something went wrong. Please try again later.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<main style="padding-top: 120px;">
    <section class="section-padding">
        <div style="max-width: 900px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 60px;">
                <h1 class="animate-fade-up" style="font-size: 48px; letter-spacing: -1.5px;">Get a Professional Quote</h1>
                <p class="animate-fade-up" style="color: var(--text-grey); font-size: 18px; margin-top: 15px;">Tell us your requirements, and we'll craft the perfect solution for you.</p>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1.5fr; gap: 50px; align-items: start;">
                <!-- Contact Info -->
                <div class="animate-fade-up">
                    <div class="glass-card" style="padding: 40px; background: var(--primary); color: white;">
                        <h3 style="margin-bottom: 25px;">Why professional?</h3>
                        <p style="font-size: 15px; line-height: 1.6; opacity: 0.9; margin-bottom: 30px;">
                            We don't just sell parts. We provide engineered solutions tailored for creative professionals and performance enthusiasts.
                        </p>
                        
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span>Free Compatibility Check</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <span>Express Pan-India Delivery</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <span>Dedicated Support</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quote Form -->
                <div class="animate-fade-up" style="transition-delay: 0.2s;">
                    <div class="glass-card" style="padding: 40px;">
                        <?php if ($success): ?>
                            <div style="background: rgba(52, 199, 89, 0.1); color: #2e7d32; padding: 20px; border-radius: 12px; margin-bottom: 30px; text-align: center;">
                                <i class="fas fa-check-circle" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div style="background: rgba(255, 59, 48, 0.1); color: #ff3b30; padding: 15px; border-radius: 12px; margin-bottom: 30px; text-align: center;">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form action="get-quote.php" method="POST">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; color: var(--text-grey); margin-bottom: 8px; display: block;">Full Name *</label>
                                    <input type="text" name="name" required placeholder="Aravind Kumar" style="width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                                </div>
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; color: var(--text-grey); margin-bottom: 8px; display: block;">Requirement *</label>
                                    <select name="type" style="width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #ddd; outline: none; background: white;">
                                        <option value="Custom Build">Custom Workstation</option>
                                        <option value="Bulk Purchase">Bulk Purchase (B2B)</option>
                                        <option value="IT Consulting">Service & Consulting</option>
                                        <option value="Other">Other Query</option>
                                    </select>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; color: var(--text-grey); margin-bottom: 8px; display: block;">Email Address *</label>
                                    <input type="email" name="email" required placeholder="name@example.com" style="width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                                </div>
                                <div>
                                    <label style="font-size: 13px; font-weight: 600; color: var(--text-grey); margin-bottom: 8px; display: block;">Phone Number</label>
                                    <input type="tel" name="phone" placeholder="+91 98765 43210" style="width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                                </div>
                            </div>

                            <div style="margin-bottom: 30px;">
                                <label style="font-size: 13px; font-weight: 600; color: var(--text-grey); margin-bottom: 8px; display: block;">Tell us about your needs *</label>
                                <textarea name="message" required rows="5" placeholder="Include processing needs, budget, or specific components..." style="width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-family: inherit;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; font-size: 16px; font-weight: 600;">Submit Quote Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
