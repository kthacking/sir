<?php include 'includes/header.php'; ?>

<style>
    /* All service styles → css/service-pages.css */
</style>

<main>
    <!-- ── Hero ───────────────────────────────────────────── -->
    <section class="service-hero">
        <div class="container">
            <span class="service-badge">Retail Automation</span>
            <h1 class="hero-title">Omni-Channel POS<br>Hardware Integration</h1>
            <p class="hero-sub">Industrial-strength Point-of-Sale systems for supermarkets, luxury boutiques, and hospitality chains.</p>
        </div>
    </section>

    <!-- ── Feature Cards ──────────────────────────────────── -->
    <section style="padding: 64px 24px; background: var(--bg);">
        <div class="container" style="max-width: 1100px;">
            <div class="svc-features-grid">
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-desktop"></i></div>
                    <h4>Touch Terminals</h4>
                    <p>Dual-screen capacitive touch POS terminals with customer-facing displays and integrated card readers.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-print"></i></div>
                    <h4>Thermal Receipt Printing</h4>
                    <p>High-speed 300mm/s thermal printers with auto-cutter, kitchen printer, and cloud printing support.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-barcode"></i></div>
                    <h4>Barcode &amp; RFID Scanning</h4>
                    <p>Omni-directional 2D scanners and RFID readers for fast checkout and inventory tracking.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-cloud"></i></div>
                    <h4>Cloud Sync Ready</h4>
                    <p>Compatible with Shopify, Odoo, SAP, and custom ERP for real-time stock sync across outlets.</p>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <!-- ── Process Steps ──────────────────────────────────── -->
    <section class="svc-process">
        <div class="container">
            <div class="svc-process-header">
                <span class="eyebrow">Implementation Journey</span>
                <h2>POS Deployment Process</h2>
            </div>
            <div class="svc-steps">
                <div class="svc-step">
                    <div class="svc-step-num">01</div>
                    <h4>Requirements Scoping</h4>
                    <p>Number of counters, software, peripherals, and integration points are defined.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">02</div>
                    <h4>Hardware Sourcing</h4>
                    <p>Curated hardware bundle selected and pre-configured before delivery.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">03</div>
                    <h4>On-site Setup</h4>
                    <p>Full counter installation, cable management, and software pairing on-site.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">04</div>
                    <h4>Staff Training</h4>
                    <p>Team walkthrough and operational guide delivered at handover.</p>
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
                        <h2 class="mb-4" style="font-weight: 800; font-family: 'Outfit';">Modernizing Your Checkout</h2>
                        <p style="font-size: 18px; line-height: 1.7; color: #48484a;">The checkout experience is the final impression your brand leaves. We provide end-to-end POS hardware ecosystems including touch-terminals, lightning-fast thermal printers, and integrated card readers that work together flawlessly.</p>
                        
                        <h4 class="mt-5 mb-3" style="font-weight: 700;">Integration Ecosystem</h4>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Dual-Screen Touch Terminals</li>
                            <li><i class="fas fa-check-circle"></i> High-Speed Thermal Receipt Printers</li>
                            <li><i class="fas fa-check-circle"></i> Omni-directional Barcode Scanners</li>
                            <li><i class="fas fa-check-circle"></i> Cash Drawer &amp; Coin Sorter Integration</li>
                            <li><i class="fas fa-check-circle"></i> Digital Signage &amp; Customer Displays</li>
                        </ul>

                        <div class="info-callout mt-5">
                            <h5>Cloud Sync Ready</h5>
                            <p class="mb-0">Our hardware selection is specifically optimized for modern cloud-based inventory software like Shopify, Odoo, and SAP, ensuring real-time stock updates across all your outlets.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="request-form">
                        <h3 class="mb-4" style="font-weight: 800; font-family: 'Outfit'; text-align: center;">Build Your System</h3>
                        
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                            <div class="msg-box msg-success">Submissions received! We will contact you shortly.</div>
                        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
                            <div class="msg-box msg-error">Error processing request. Please try again.</div>
                        <?php endif; ?>

                        <form action="submit-service.php" method="POST">
                            <input type="hidden" name="service_type" value="POS Integration">
                            
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
                                <input type="email" name="email" class="form-control" placeholder="john@retail.com" required>
                            </div>

                            <div class="form-group">
                                <label>Business Name</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Enter Store Name" required>
                            </div>

                            <div class="form-group">
                                <label>Store Requirements</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="e.g. 5 checkout counters, inventory scanners, receipt printing..." required></textarea>
                            </div>

                            <button type="submit" class="btn-submit">Request POS Bundle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
