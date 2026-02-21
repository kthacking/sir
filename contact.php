<?php include 'includes/header.php'; ?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 class="animate-fade-up">Contact Support</h1>
            <p class="animate-fade-up">We're here to help you with your technical needs.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px; max-width: 1200px; margin: 0 auto;">
            <div class="glass-card animate-fade-up">
                <h3 style="margin-bottom: 30px;">Get in Touch</h3>
                <form action="process-contact.php" method="POST">
                    <div style="margin-bottom: 20px;">
                        <input type="text" placeholder="Your Name" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; outline: none;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <input type="email" placeholder="Your Email" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; outline: none;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <textarea placeholder="Message" rows="5" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; outline: none; font-family: inherit;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">Send Message</button>
                </form>
            </div>

            <div class="animate-fade-up">
                <div style="margin-bottom: 40px;">
                    <h4 style="margin-bottom: 10px;">Contact Information</h4>
                    <p style="color: var(--text-grey); font-size: 15px; line-height: 1.8;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 10px;"></i> 123 Tech Park, Silicon Valley Avenue<br>
                        <i class="fas fa-phone" style="color: var(--primary); margin-right: 10px;"></i> +91 98765 43210<br>
                        <i class="fas fa-envelope" style="color: var(--primary); margin-right: 10px;"></i> support@need4it.com
                    </p>
                </div>
                
                <div>
                    <h4 style="margin-bottom: 20px;">Working Hours</h4>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 10px 0; font-size: 14px;">
                        <span>Monday - Friday</span>
                        <span>9:00 AM - 7:00 PM</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding: 10px 0; font-size: 14px;">
                        <span>Saturday</span>
                        <span>10:00 AM - 4:00 PM</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; padding: 10px 0; font-size: 14px;">
                        <span>Sunday</span>
                        <span style="color: #ff3b30;">Closed</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
