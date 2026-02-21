<?php
include_once 'includes/config.php';
include 'includes/header.php';
?>

<main style="padding-top: 100px; min-height: 80vh; display: flex; align-items: center; justify-content: center;">
    <section class="section-padding" style="text-align: center;">
        <div class="glass-card" style="padding: 60px; max-width: 600px;">
            <div style="width: 80px; height: 80px; background: rgba(52, 199, 89, 0.1); color: #34c759; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 30px auto;">
                <i class="fas fa-check"></i>
            </div>
            <h1 style="margin-bottom: 10px;">Order Placed!</h1>
            <p style="color: var(--text-grey); margin-bottom: 40px;">Thank you for your purchase. We've sent a confirmation email with your order details.</p>
            
            <div style="background: #f9f9f9; padding: 20px; border-radius: 12px; margin-bottom: 40px; text-align: left;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px;">
                    <span style="color: var(--text-grey);">Order Number</span>
                    <span style="font-weight: 600;">#NIT-29481</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 14px;">
                    <span style="color: var(--text-grey);">Estimated Delivery</span>
                    <span style="font-weight: 600;">22 - 24 Feb, 2026</span>
                </div>
            </div>

            <a href="index.php" class="btn btn-primary" style="padding: 15px 40px;">Continue Shopping</a>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
