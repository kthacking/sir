<?php include 'includes/header.php'; ?>

<style>
    /* All service styles → css/service-pages.css */
</style>

<main>
    <!-- ── Hero ───────────────────────────────────────────── -->
    <section class="service-hero">
        <div class="container">
            <span class="service-badge">Zero Trust Architecture</span>
            <h1 class="hero-title">Next-Generation<br>Cyber Security Audits</h1>
            <p class="hero-sub">Proactive threat hunting, UTM firewall implementation, and end-to-end encryption for enterprise data protection.</p>
        </div>
    </section>

    <!-- ── Feature Cards ──────────────────────────────────── -->
    <section style="padding: 64px 24px; background: var(--bg);">
        <div class="container" style="max-width: 1100px;">
            <div class="svc-features-grid">
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-bug"></i></div>
                    <h4>Penetration Testing</h4>
                    <p>Simulated red-team attacks exposing real vulnerabilities before malicious actors do.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-fire-alt"></i></div>
                    <h4>UTM Firewall Setup</h4>
                    <p>Unified Threat Management with IDS/IPS, content filtering, and application control layers.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-laptop-code"></i></div>
                    <h4>Endpoint Detection (EDR)</h4>
                    <p>AI-powered endpoint agents detecting lateral movement, ransomware, and zero-day exploits.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-user-shield"></i></div>
                    <h4>Security Awareness Training</h4>
                    <p>Phishing simulations and interactive workshops to build a human firewall across your team.</p>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <!-- ── Process Steps ──────────────────────────────────── -->
    <section class="svc-process">
        <div class="container">
            <div class="svc-process-header">
                <span class="eyebrow">Audit Lifecycle</span>
                <h2>How We Secure Your Business</h2>
            </div>
            <div class="svc-steps">
                <div class="svc-step">
                    <div class="svc-step-num">01</div>
                    <h4>Reconnaissance</h4>
                    <p>Passive and active information gathering to map your attack surface.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">02</div>
                    <h4>Vulnerability Scan</h4>
                    <p>Automated and manual scanning across network, applications, and endpoints.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">03</div>
                    <h4>Threat Simulation</h4>
                    <p>Controlled exploitation of discovered gaps to validate real-world impact.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">04</div>
                    <h4>Remediation Report</h4>
                    <p>Detailed risk-ranked report with step-by-step fixes and executive summary.</p>
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
                        <h2 class="mb-4" style="font-weight: 800; font-family: 'Outfit';">Shielding Your Intelligence</h2>
                        <p style="font-size: 18px; line-height: 1.7; color: #48484a;">Cyber threats are evolving. A simple antivirus is no longer enough to protect your intellectual property. We implement "Defense-in-Depth" strategies that protect your perimeter, your internal network, and your data at rest.</p>

                        <h4 class="mt-5 mb-3" style="font-weight: 700;">Security Solutions</h4>
                        <ul class="feature-list">
                            <li><i class="fas fa-shield-alt"></i> Penetration Testing &amp; Vulnerability Audits</li>
                            <li><i class="fas fa-shield-alt"></i> Unified Threat Management (UTM) Firewalls</li>
                            <li><i class="fas fa-shield-alt"></i> AI-Powered Endpoint Protection (EDR)</li>
                            <li><i class="fas fa-shield-alt"></i> Secure Remote Access (VPN/ZTNA)</li>
                            <li><i class="fas fa-shield-alt"></i> Employee Security Awareness Training</li>
                        </ul>

                        <div class="info-callout mt-5">
                            <h5>Is Your Data Secure?</h5>
                            <p class="mb-0">Our 48-hour rapid audit identifies critical backdoors and weak password policies that could lead to ransomware or data leaks. Get your report today.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="request-form">
                        <h3 class="mb-4" style="font-weight: 800; font-family: 'Outfit'; text-align: center;">Get Secure Now</h3>

                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                            <div class="msg-box msg-success">Submissions received! We will contact you shortly.</div>
                        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
                            <div class="msg-box msg-error">Error processing request. Please try again.</div>
                        <?php endif; ?>

                        <form action="submit-service.php" method="POST">
                            <input type="hidden" name="service_type" value="Cyber Security">

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
                                <label>Security Concerns</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="e.g. Server protection, firewall setup, employee phishing tests..." required></textarea>
                            </div>

                            <button type="submit" class="btn-submit">Request Security Audit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
