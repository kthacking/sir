<?php include 'includes/header.php'; ?>

<style>
    /* All service styles → css/service-pages.css */
</style>

<main>
    <!-- ── Hero ───────────────────────────────────────────── -->
    <section class="service-hero">
        <div class="container">
            <span class="service-badge">Pro Active IT</span>
            <h1 class="hero-title">Enterprise Annual<br>Maintenance Contracts</h1>
            <p class="hero-sub">Zero-downtime IT infrastructure management for businesses, schools, and healthcare facilities.</p>
        </div>
    </section>

    <!-- ── Feature Cards ──────────────────────────────────── -->
    <section style="padding: 64px 24px; background: var(--bg);">
        <div class="container" style="max-width: 1100px;">
            <div class="svc-features-grid">
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-tools"></i></div>
                    <h4>Preventive Maintenance</h4>
                    <p>Quarterly on-site inspections and proactive cleaning to prevent hardware failures before they occur.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-headset"></i></div>
                    <h4>4-Hour Response SLA</h4>
                    <p>Guaranteed 4-hour response time for critical failures — backed by our service level agreement.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-shield-alt"></i></div>
                    <h4>Security &amp; Patch Management</h4>
                    <p>Regular vulnerability audits, OS patches, and firmware updates to keep your fleet secure.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-network-wired"></i></div>
                    <h4>Network Connectivity</h4>
                    <p>Peripheral and network layer support ensuring every device stays connected and productive.</p>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <!-- ── Process Steps ──────────────────────────────────── -->
    <section class="svc-process">
        <div class="container">
            <div class="svc-process-header">
                <span class="eyebrow">How We Onboard</span>
                <h2>AMC Enrollment Process</h2>
            </div>
            <div class="svc-steps">
                <div class="svc-step">
                    <div class="svc-step-num">01</div>
                    <h4>Site Survey</h4>
                    <p>Our team audits your existing infrastructure and documents all assets.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">02</div>
                    <h4>Plan Selection</h4>
                    <p>Choose an SMB or Corporate plan based on asset count and SLA needs.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">03</div>
                    <h4>Contract Signing</h4>
                    <p>Transparent contract with defined response times and coverage scope.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">04</div>
                    <h4>Proactive Support</h4>
                    <p>Regular check-ins, remote monitoring, and on-call emergency response.</p>
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
                        <h2 class="mb-4" style="font-weight: 800; font-family: 'Outfit';">Worry-Free IT Infrastructure</h2>
                        <p style="font-size: 18px; line-height: 1.7; color: #48484a;">An inefficient IT setup can cost your business hours of productivity. Our AMC plans provide proactive health monitoring, regular preventive maintenance, and guaranteed emergency response times to keep your operations fluid.</p>
                        
                        <h4 class="mt-5 mb-3" style="font-weight: 700;">What's Included?</h4>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Quarterly On-site Preventive Maintenance</li>
                            <li><i class="fas fa-check-circle"></i> 4-Hour Response for Critical Failures</li>
                            <li><i class="fas fa-check-circle"></i> Unlimited Remote Technical Support</li>
                            <li><i class="fas fa-check-circle"></i> Security Audits &amp; Patch Management</li>
                            <li><i class="fas fa-check-circle"></i> Peripheral &amp; Network Connectivity Support</li>
                        </ul>

                        <div class="pricing-simple">
                            <div class="price-item">
                                <h5 class="mb-2">SMB Plan</h5>
                                <div class="h3 mb-2" style="font-weight: 800; color: #1d1d1f;">₹4,999<span style="font-size: 14px; color: #86868b;"> /yr</span></div>
                                <p class="small mb-0">Up to 5 Workstations</p>
                            </div>
                            <div class="price-item featured" style="border-color: #0071e3; background: #fff;">
                                <h5 class="mb-2">Corporate Plan</h5>
                                <div class="h3 mb-2" style="font-weight: 800; color: #1d1d1f;">₹12,499<span style="font-size: 14px; color: #86868b;"> /yr</span></div>
                                <p class="small mb-0">Up to 15 Workstations</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="request-form">
                        <h3 class="mb-4" style="font-weight: 800; font-family: 'Outfit'; text-align: center;">Partner with Us</h3>
                        
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                            <div class="msg-box msg-success">Submissions received! We will contact you shortly.</div>
                        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
                            <div class="msg-box msg-error">Error processing request. Please try again.</div>
                        <?php endif; ?>

                        <form action="submit-service.php" method="POST">
                            <input type="hidden" name="service_type" value="Enterprise AMC">
                            
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
                                <input type="email" name="email" class="form-control" placeholder="john@company.com" required>
                            </div>

                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name" required>
                            </div>

                            <div class="form-group">
                                <label>Number of Systems</label>
                                <textarea name="message" class="form-control" rows="3" placeholder="e.g. 20 Laptops, 2 Servers, Office Networking..." required></textarea>
                            </div>

                            <button type="submit" class="btn-submit">Request Consultation</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
