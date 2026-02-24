<?php include 'includes/header.php'; ?>

<style>
    /* Contact Page Specific Styles */
    .contact-hero {
        padding: 148px 24px 72px;
        background: var(--bg-white);
        text-align: center;
        border-bottom: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0,0,0,0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,0.035) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
    }

    .contact-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 72px 24px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
    }

    .contact-info-section h2 {
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: 32px;
        letter-spacing: -0.03em;
        margin-bottom: 24px;
        color: var(--text-dark);
    }

    .contact-method {
        margin-bottom: 32px;
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .contact-method i {
        width: 44px;
        height: 44px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 18px;
        flex-shrink: 0;
    }

    .contact-method h4 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
        color: var(--text-dark);
    }

    .contact-method p {
        font-size: 14px;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }

    .hours-table {
        margin-top: 48px;
        border-top: 1px solid var(--border);
        padding-top: 32px;
    }

    .hour-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
        color: var(--text-mid);
    }

    .hour-row:last-child {
        border-bottom: none;
    }

    .hour-row span:first-child {
        font-weight: 600;
        color: var(--text-dark);
    }

    /* Form Overrides (using shared style concepts) */
    .contact-form-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        padding: 48px;
    }

    @media (max-width: 991px) {
        .contact-container {
            grid-template-columns: 1fr;
            gap: 60px;
        }
    }
</style>

<main>
    <section class="contact-hero">
        <div class="container">
            <span class="service-badge">Support Center</span>
            <h1 class="hero-title">Get in <span class="accent">Touch</span></h1>
            <p class="hero-sub">Whether you have a critical technical failure or need a custom build consultation, our team is ready to assist.</p>
        </div>
    </section>

    <div class="contact-container">
        <div class="contact-info-section">
            <h2>Connect with Experts</h2>
            <p style="color: var(--text-muted); margin-bottom: 48px; line-height: 1.8;">Our technical laboratory in Silicon Valley handles everything from micro-soldering to enterprise audits. Reach out via the channel that suits you best.</p>

            <div class="contact-method">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h4>Visit Laboratory</h4>
                    <p>123 Tech Park, Silicon Valley Avenue<br>Block 4, Floor 2</p>
                </div>
            </div>

            <div class="contact-method">
                <i class="fas fa-phone-alt"></i>
                <div>
                    <h4>Direct Line</h4>
                    <p>+91 98765 43210<br>Available during working hours</p>
                </div>
            </div>

            <div class="contact-method">
                <i class="fas fa-envelope"></i>
                <div>
                    <h4>Electronic Mail</h4>
                    <p>support@need4it.com<br>24-hour response average</p>
                </div>
            </div>

            <div class="hours-table">
                <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 1.5px; color: var(--text-muted); margin-bottom: 16px;">Working Hours</h4>
                <div class="hour-row"><span>Monday - Friday</span><span>9:00 AM - 7:00 PM</span></div>
                <div class="hour-row"><span>Saturday</span><span>10:00 AM - 4:00 PM</span></div>
                <div class="hour-row"><span>Sunday</span><span style="color: var(--accent);">Closed</span></div>
            </div>
        </div>

        <div class="contact-form-section">
            <div class="contact-form-card">
                <h3 style="font-family: 'Manrope'; font-weight: 800; font-size: 22px; margin-bottom: 24px; text-align: center;">Send Message</h3>
                <form action="process-contact.php" method="POST">
                    <div class="form-group">
                        <label>Your Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="john@company.com" required>
                    </div>
                    <div class="form-group">
                        <label>Message Brief</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Describe your technical requirement..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit" style="margin-top: 12px;">Despatch Message</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
