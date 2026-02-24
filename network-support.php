<?php include 'includes/header.php'; ?>

<style>
    /* All service styles → css/service-pages.css */
</style>

<main>
    <!-- ── Hero ───────────────────────────────────────────── -->
    <section class="service-hero">
        <div class="container">
            <span class="service-badge">Universal Connectivity</span>
            <h1 class="hero-title">Custom Network<br>Infrastructure Support</h1>
            <p class="hero-sub">High-bandwidth structured cabling, Wi-Fi 6 mesh deployments, and secure multi-office VPN tunnels.</p>
        </div>
    </section>

    <!-- ── Feature Cards ──────────────────────────────────── -->
    <section style="padding: 64px 24px; background: var(--bg);">
        <div class="container" style="max-width: 1100px;">
            <div class="svc-features-grid">
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-network-wired"></i></div>
                    <h4>Structured Cabling</h4>
                    <p>Cat6/Cat7 cabling with proper cable management, patch panels, and rack installation.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-wifi"></i></div>
                    <h4>Wi-Fi 6 Mesh Deployment</h4>
                    <p>Enterprise-grade access point placement using heat-mapping for zero dead zones.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-fire-alt"></i></div>
                    <h4>Firewall &amp; UTM</h4>
                    <p>Hardware UTM firewalls with IDS/IPS and application-layer filtering configured for your environment.</p>
                </div>
                <div class="svc-feat-card">
                    <div class="svc-feat-icon"><i class="fas fa-lock"></i></div>
                    <h4>Site-to-Site VPN</h4>
                    <p>Encrypted IPSec tunnels connecting multiple offices with seamless internal access.</p>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <!-- ── Process Steps ──────────────────────────────────── -->
    <section class="svc-process">
        <div class="container">
            <div class="svc-process-header">
                <span class="eyebrow">Deployment Lifecycle</span>
                <h2>How We Build Your Network</h2>
            </div>
            <div class="svc-steps">
                <div class="svc-step">
                    <div class="svc-step-num">01</div>
                    <h4>Site Assessment</h4>
                    <p>Signal survey, floor plan review, and bandwidth requirement analysis.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">02</div>
                    <h4>Network Design</h4>
                    <p>Topology diagram, VLAN planning, and equipment specification report.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">03</div>
                    <h4>Installation</h4>
                    <p>Cabling, AP mounting, switch racking, and firewall provisioning on-site.</p>
                </div>
                <div class="svc-step">
                    <div class="svc-step-num">04</div>
                    <h4>Testing &amp; Handover</h4>
                    <p>Speed tests, failover validation, and documentation handed to your team.</p>
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
                        <h2 class="mb-4" style="font-weight: 800; font-family: 'Outfit';">High-Performance Networking</h2>
                        <p style="font-size: 18px; line-height: 1.7; color: #48484a;">In the age of cloud computing, your network is your lifeline. We design and implement robust network architectures that eliminate dead zones, prevent bandwidth bottlenecks, and ensure end-to-end encryption for all transmitted data.</p>
                        
                        <h4 class="mt-5 mb-3" style="font-weight: 700;">Deployment Services</h4>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Cat6/Cat7 Structured Data Cabling</li>
                            <li><i class="fas fa-check-circle"></i> Enterprise Wi-Fi 6 &amp; 6E Mesh Setup</li>
                            <li><i class="fas fa-check-circle"></i> Layer 3 Managed Switch Configuration</li>
                            <li><i class="fas fa-check-circle"></i> Hardware Firewall &amp; UTM Deployment</li>
                            <li><i class="fas fa-check-circle"></i> Multi-site Site-to-Site VPN Tunnels</li>
                        </ul>

                        <div class="info-callout mt-5" style="background: rgba(0, 113, 227, 0.03); border-radius: 20px;">
                            <h5>Office WiFi Optimization</h5>
                            <p class="mb-0">Using advanced heat-mapping software, we identify interference patterns and signal drops in your office, ensuring seamless roaming for your team across all floors.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="request-form">
                        <h3 class="mb-4" style="font-weight: 800; font-family: 'Outfit'; text-align: center;">Consult an Engineer</h3>
                        
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                            <div class="msg-box msg-success">Submissions received! We will contact you shortly.</div>
                        <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
                            <div class="msg-box msg-error">Error processing request. Please try again.</div>
                        <?php endif; ?>

                        <form action="submit-service.php" method="POST">
                            <input type="hidden" name="service_type" value="Network Support">
                            
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
                                <label>Company Name (Optional)</label>
                                <input type="text" name="company_name" class="form-control" placeholder="Enter Company Name">
                            </div>

                            <div class="form-group">
                                <label>Network Scale / Details</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="e.g. 50 user office, floor plan wifi setup, firewalls..." required></textarea>
                            </div>

                            <button type="submit" class="btn-submit">Request Design</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
