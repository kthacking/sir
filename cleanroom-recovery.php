<?php include 'includes/header.php'; ?>

<style>
    /* All service styles → css/service-pages.css */
</style>

<main>
    <!-- ── Hero ───────────────────────────────────────────── -->
    <section class="service-hero">
        <div class="container">
            <span class="service-badge">Critical Retrieval</span>
            <h1 class="hero-title">Class-100 Cleanroom<br>Advanced Data Recovery</h1>
            <p class="hero-sub">Highest success rates for mechanical drive failures, encrypted SSD corruption, and RAID server array reconstruction.</p>
        </div>
    </section>

    <!-- ── Feature Cards ──────────────────────────────────── -->
    <section style="padding: 64px 24px; background: var(--bg);">
        <div class="container" style="max-width: 1100px;">
            <div class="svc-features-grid">
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-hdd"></i></div>
                    <h4>Mechanical HDD Recovery</h4>
                    <p>Head replacement and platter swaps performed in a particle-free Class-100 environment.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-memory"></i></div>
                    <h4>SSD &amp; NAND Recovery</h4>
                    <p>Firmware bypass, wear-leveling table reconstruction, and controller chip bypass for flash storage.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-server"></i></div>
                    <h4>RAID Reconstruction</h4>
                    <p>RAID 0/1/5/10 array recovery with full parity and stripe-set analysis.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-user-shield"></i></div>
                    <h4>Forensic Acquisition</h4>
                    <p>Bit-perfect sector-by-sector imaging using write-blockers — admissible for legal use.</p>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <!-- ── Process Steps ──────────────────────────────────── -->
    <section class="svc-process">
        <div class="container">
            <div class="svc-process-header">
                <span class="eyebrow">Recovery Workflow</span>
                <h2>Our Recovery Process</h2>
            </div>
            <div class="svc-steps">
                <div class="svc-step">
                    <div class="svc-step-num">01</div>
                    <h4>Media Intake</h4>
                    <p>Drive received, catalogued, and visually inspected within 2 hours of drop-off.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">02</div>
                    <h4>Diagnostic Imaging</h4>
                    <p>Non-destructive sector scan to assess damage extent and recovery probability.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">03</div>
                    <h4>Cleanroom Work</h4>
                    <p>Physical repairs performed in ISO Class 5 environment to prevent contamination.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">04</div>
                    <h4>Data Delivery</h4>
                    <p>Recovered files transferred to new encrypted drive with a full file manifest.</p>
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
                    <div class="glass-box">
                        <h2 class="mb-4" style="font-weight: 800; font-family: 'Outfit';">The Last Line of Defense</h2>
                        <p style="font-size: 18px; line-height: 1.7; color: #48484a;">Failed storage isn't just a hardware issue—it's a threat to your digital legacy or business continuity. We operate a Class-100 cleanroom environment allowing us to physically open hard drives and perform head replacements or platter swaps safely.</p>
                        
                        <h4 class="mt-5 mb-3" style="font-weight: 700;">Recovery Capabilities</h4>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Physical Head &amp; Motor Replacement</li>
                            <li><i class="fas fa-check-circle"></i> Firmware Corruption Repair</li>
                            <li><i class="fas fa-check-circle"></i> Encrypted File System Decryption</li>
                            <li><i class="fas fa-check-circle"></i> RAID 0/1/5/10 Reconstruction</li>
                            <li><i class="fas fa-check-circle"></i> Forensic Data Acquisition</li>
                        </ul>

                        <div class="info-callout mt-5">
                            <h5>Emergency Priority 24/7</h5>
                            <p class="mb-0">For mission-critical server failures, we offer a "Midnight Emergency" service where our engineers work around the clock to restore your data within hours.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="request-form">
                        <h3 class="mb-4" style="font-weight: 800; font-family: 'Outfit'; text-align: center;">Emergency Intake</h3>
                        
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                            <div class="msg-box msg-success">Submissions received! We will contact you shortly.</div>
                        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
                            <div class="msg-box msg-error">Error processing request. Please try again.</div>
                        <?php endif; ?>

                        <form action="submit-service.php" method="POST">
                            <input type="hidden" name="service_type" value="Data Recovery">
                            
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
                                <label>Storage Device Model</label>
                                <input type="text" name="company_name" class="form-control" placeholder="e.g. Seagate 2TB, Samsung SSD">
                            </div>

                            <div class="form-group">
                                <label>Failure Symptoms</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="e.g. Clicking noise, drive not detected, formatted by mistake..." required></textarea>
                            </div>

                            <button type="submit" class="btn-submit">Start Rescue Process</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
