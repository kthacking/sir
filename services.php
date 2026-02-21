<?php include 'includes/header.php'; ?>

<main style="padding-top: 100px;">
    <!-- Service Hero -->
    <section class="section-padding" style="background: white; text-align: center;">
        <div style="max-width: 800px; margin: 0 auto;">
            <h1 class="animate-fade-up" style="font-size: 56px; margin-bottom: 20px;">Certified Professional Services</h1>
            <p class="animate-fade-up" style="font-size: 18px; color: var(--text-grey); margin-bottom: 40px;">Expert hardware repairs, software optimization, and AMC services tailored for your business and home.</p>
            <div class="animate-fade-up">
                <a href="book-service.php" class="btn btn-primary" style="padding: 16px 40px;">Book a Repair</a>
                <a href="track-repair.php" class="btn btn-outline" style="margin-left: 15px; padding: 16px 40px;">Track a Repair</a>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="section-padding">
        <div class="grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="glass-card animate-fade-up">
                <i class="fas fa-laptop-medical" style="font-size: 40px; color: var(--primary); margin-bottom: 25px;"></i>
                <h3 style="margin-bottom: 15px;">Hardware Repair</h3>
                <p style="font-size: 15px; color: var(--text-grey); line-height: 1.6; margin-bottom: 20px;">Component level repairs for laptops, desktops, and printers. Screen replacement, motherboard repair, and more.</p>
                <ul style="list-style: none; font-size: 14px; color: var(--text-grey); line-height: 2;">
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> Chip-level Repair</li>
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> Screen & Keyboard Replacement</li>
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> Data Recovery</li>
                </ul>
            </div>

            <div class="glass-card animate-fade-up" style="transition-delay: 0.1s;">
                <i class="fas fa-shield-alt" style="font-size: 40px; color: var(--primary); margin-bottom: 25px;"></i>
                <h3 style="margin-bottom: 15px;">Annual Maintenance (AMC)</h3>
                <p style="font-size: 15px; color: var(--text-grey); line-height: 1.6; margin-bottom: 20px;">Worry-free computing for businesses. Priority support, regular health checks, and emergency onsite visits.</p>
                <ul style="list-style: none; font-size: 14px; color: var(--text-grey); line-height: 2;">
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> 24/7 Priority Support</li>
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> Preventive Maintenance</li>
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> Software Audits</li>
                </ul>
            </div>

            <div class="glass-card animate-fade-up" style="transition-delay: 0.2s;">
                <i class="fas fa-network-wired" style="font-size: 40px; color: var(--primary); margin-bottom: 25px;"></i>
                <h3 style="margin-bottom: 15px;">OS & Network Support</h3>
                <p style="font-size: 15px; color: var(--text-grey); line-height: 1.6; margin-bottom: 20px;">Complete software solutions. OS installation, malware removal, and secure office network configuration.</p>
                <ul style="list-style: none; font-size: 14px; color: var(--text-grey); line-height: 2;">
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> Windows/macOS Installation</li>
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> Network Security Setup</li>
                    <li><i class="fas fa-check" style="color: var(--primary); margin-right: 10px;"></i> Cloud Configuration</li>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
