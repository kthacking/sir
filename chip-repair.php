<?php include 'includes/header.php'; ?>

<style>
    /* All service styles → css/service-pages.css */
    /* Page-specific overrides only */
</style>

<main>
    <!-- ── Hero ───────────────────────────────────────────── -->
    <section class="service-hero">
        <div class="container">
            <span class="service-badge">Repair Excellence</span>
            <h1 class="hero-title">Chip-level Logic<br>Board Repair</h1>
            <p class="hero-sub">Specialized micro-soldering solutions for dead laptops, water-damaged MacBooks, and high-end gaming logic boards.</p>
        </div>
    </section>

    <!-- ── Feature Cards ──────────────────────────────────── -->
    <section style="padding: 64px 24px; background: var(--bg);">
        <div class="container" style="max-width: 1100px;">
            <div class="svc-features-grid">
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-microchip"></i></div>
                    <h4>SMC &amp; IC Programming</h4>
                    <p>Full reprogramming of power management chips and SMC controllers on Apple logic boards.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-tint"></i></div>
                    <h4>Liquid Damage Recovery</h4>
                    <p>Ultrasonic cleaning and component-level restoration after water or beverage damage.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-video"></i></div>
                    <h4>GPU Reballing</h4>
                    <p>BGA reballing under HD microscopy — restoring display output on faulty discrete GPUs.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-bolt"></i></div>
                    <h4>Short Circuit Detection</h4>
                    <p>Thermal imaging to locate shorted rail components without trial-and-error removal.</p>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <!-- ── Process Steps ──────────────────────────────────── -->
    <section class="svc-process">
        <div class="container">
            <div class="svc-process-header">
                <span class="eyebrow">How It Works</span>
                <h2>Our Repair Process</h2>
            </div>
            <div class="svc-steps">
                <div class="svc-step">
                    <div class="svc-step-num">01</div>
                    <h4>Diagnostic Intake</h4>
                    <p>Free board-level diagnosis using thermal imaging and multimeter checks.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">02</div>
                    <h4>Repair Quotation</h4>
                    <p>Transparent report sent within 24 hours — no hidden charges.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">03</div>
                    <h4>Micro Repair</h4>
                    <p>Precision soldering performed under HD microscope in our controlled lab.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">04</div>
                    <h4>Quality &amp; Handoff</h4>
                    <p>48-hour burn test before return. 6-month repair warranty included.</p>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <!-- ── Details + Form ─────────────────────────────────── -->
    <section class="details-section">
        <div class="container" style="max-width: 1100px;">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="glass-box h-100">
                        <h2 class="mb-4" style="font-weight: 800; font-family: 'Outfit';">Expert Micro-Soldering</h2>
                        <p style="font-size: 18px; line-height: 1.7; color: #48484a;">When others say "replace the motherboard," we say "repair it." Our engineers use clinical-grade infrared rework stations and HD microscopy to identify and replace failed micro-components at the chip level.</p>
                        
                        <h4 class="mt-5 mb-3" style="font-weight: 700;">Service Features</h4>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> SMC &amp; Power IC Programming</li>
                            <li><i class="fas fa-check-circle"></i> Liquid Damage Ultrasonics</li>
                            <li><i class="fas fa-check-circle"></i> GPU Reballing &amp; Replacement</li>
                            <li><i class="fas fa-check-circle"></i> Short Circuit Detection &amp; Fix</li>
                            <li><i class="fas fa-check-circle"></i> Connector &amp; Port Replacement</li>
                        </ul>

                        <div class="info-callout mt-5">
                            <h5>Why Repair Instead of Replace?</h5>
                            <p class="mb-0"> motherboards can cost 60-80% of a new laptop. Our chip-level repairs typically cost between ₹3,000 to ₹12,000, saving you significant overhead while keeping your original data intact.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="request-form" id="request-form">
                        <h3 class="mb-4" style="font-weight: 800; font-family: 'Outfit'; text-align: center;">Request Quote</h3>
                        
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                            <div class="msg-box msg-success">Submissions received! We will contact you shortly.</div>
                        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
                            <div class="msg-box msg-error">Error processing request. Please try again.</div>
                        <?php endif; ?>

                        <form action="submit-service.php" method="POST">
                            <input type="hidden" name="service_type" value="Chip-level Repair">
                            
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
                            </div>

                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+91 XXXXX XXXXX" required>
                            </div>

                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                            </div>

                            <div class="form-group">
                                <label>Company Name (Optional)</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Your Business">
                            </div>

                            <div class="form-group">
                                <label>Describe Problem</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="e.g. Laptop not turning on after spill..." required></textarea>
                            </div>

                            <button type="submit" class="btn-submit">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
