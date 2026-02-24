<?php include 'includes/header.php'; ?>

<style>
    /* ═══════════════════════════════════════════════════
       SERVICES PAGE — Minimal Editorial Redesign
       ═══════════════════════════════════════════════════ */

    /* ── Hero ──────────────────────────────────────────── */
    .services-hero {
        padding: 148px 24px 80px;
        background: var(--bg-white);
        text-align: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--border);
    }

    .services-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
    }

    .service-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        background: var(--accent-muted);
        color: var(--accent);
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 20px;
        border: 1px solid rgba(255,59,48,0.15);
        animation: fadeInUp 0.7s var(--ease) forwards;
    }

    .service-title {
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: clamp(32px, 5vw, 56px);
        margin-bottom: 20px;
        color: var(--text-dark);
        letter-spacing: -0.04em;
        line-height: 1.05;
        animation: fadeInUp 0.8s 0.1s var(--ease) both;
    }

    .service-sub {
        font-size: 17px;
        color: var(--text-muted);
        max-width: 600px;
        margin: 0 auto 40px;
        line-height: 1.7;
        animation: fadeInUp 0.8s 0.2s var(--ease) both;
    }

    .hero-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 14px 32px;
        border-radius: var(--radius-md);
        background: var(--text-dark);
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s var(--ease);
    }

    .hero-btn-primary:hover {
        background: var(--accent);
        transform: translateY(-2px);
    }

    /* ── Service Grid ─────────────────────────────────── */
    .service-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 32px;
        margin-top: 64px;
    }

    .s-card {
        background: var(--bg-white);
        padding: 48px 40px;
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        transition: all 0.4s var(--ease);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        height: 100%;
    }

    .s-card:hover {
        border-color: var(--text-dark);
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .s-icon {
        width: 60px;
        height: 60px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--text-dark);
        margin-bottom: 32px;
        transition: all 0.3s var(--ease);
    }

    .s-card:hover .s-icon {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }

    .s-card h3 {
        font-family: 'Manrope', sans-serif;
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 16px;
        color: var(--text-dark);
        letter-spacing: -0.02em;
    }

    .s-card p {
        color: var(--text-mid);
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 24px;
    }

    .s-link {
        margin-top: auto;
        font-weight: 700;
        text-decoration: none;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .s-link:hover { color: var(--accent); }

    /* ── Dark Features Section ────────────────────────── */
    .features-dark {
        background: var(--bg-dark);
        padding: 96px 24px;
        border-radius: var(--radius-xl);
        margin: 80px 0;
        color: white;
    }

    .f-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 48px;
    }

    .f-item i {
        font-size: 32px;
        color: var(--accent);
        margin-bottom: 24px;
        display: block;
    }

    .f-item h4 {
        font-family: 'Manrope', sans-serif;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .f-item p {
        font-size: 14px;
        color: rgba(255,255,255,0.5);
        line-height: 1.6;
    }

    /* ── Timeline ──────────────────────────────────────── */
    .timeline-wrap {
        padding: 96px 24px;
    }

    .t-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 32px;
        margin-top: 64px;
    }

    .t-step {
        text-align: center;
        position: relative;
    }

    .t-num {
        width: 48px;
        height: 48px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
        margin: 0 auto 24px;
        color: var(--text-dark);
        transition: all 0.3s;
    }

    .t-step:hover .t-num {
        background: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    @media (max-width: 991px) {
        .service-grid, .f-grid, .t-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 600px) {
        .service-grid, .f-grid, .t-grid { grid-template-columns: 1fr; }
    }
</style>

<main>
    <section class="services-hero">
        <div class="container">
            <span class="service-badge">Core Infrastructure</span>
            <h1 class="service-title">Systems Engineering <br>& Technical Support</h1>
            <p class="service-sub">A comprehensive suite of specialist services designed for high-performance hardware and enterprise-grade environments.</p>
            <a href="contact.php" class="hero-btn-primary">Initiate Project</a>
        </div>
    </section>

    <div class="container" style="max-width: 1100px; margin: 0 auto; padding: 0 24px;">
        <!-- Service Grid -->
        <div id="services-grid" class="service-grid">
            <div class="s-card">
                <div class="s-icon"><i class="fas fa-laptop-medical"></i></div>
                <h3>Chip-level Repair</h3>
                <p>Advanced microsoldering and logic board restoration for mission-critical mobile and desktop hardware.</p>
                <a href="chip-repair.php" class="s-link">Specifications <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="s-card">
                <div class="s-icon"><i class="fas fa-server"></i></div>
                <h3>Enterprise AMC</h3>
                <p>Continuous uptime assurance for corporate environments with dedicated response times and audits.</p>
                <a href="enterprise-amc.php" class="s-link">Review Plans <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="s-card">
                <div class="s-icon"><i class="fas fa-network-wired"></i></div>
                <h3>Network Support</h3>
                <p>Design and deployment of robust structured cabling and next-generation wireless architectures.</p>
                <a href="network-support.php" class="s-link">Deploy Now <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="s-card">
                <div class="s-icon"><i class="fas fa-shield-halved"></i></div>
                <h3>Data Recovery</h3>
                <p>Forensic-grade extraction from failed storage media within our controlled laboratory environment.</p>
                <a href="cleanroom-recovery.php" class="s-link">Recovery Logs <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="s-card">
                <div class="s-icon"><i class="fas fa-tv"></i></div>
                <h3>Retail Systems</h3>
                <p>End-to-end integration of point-of-sale hardware and digital interaction touchpoints for modern retail.</p>
                <a href="pos-integration.php" class="s-link">Learn More <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="s-card">
                <div class="s-icon"><i class="fas fa-lock"></i></div>
                <h3>Cyber Security</h3>
                <p>Hardening digital assets through professional auditing, firewall management, and endpoint defense.</p>
                <a href="cyber-security.php" class="s-link">Security Audit <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <!-- Features -->
        <div class="features-dark">
            <div class="f-grid">
                <div class="f-item">
                    <i class="fas fa-microchip"></i>
                    <h4>OEM Verified</h4>
                    <p>Strict adherence to original equipment standards for all component replacements.</p>
                </div>
                <div class="f-item">
                    <i class="fas fa-user-shield"></i>
                    <h4>Elite Lab Staff</h4>
                    <p>Operated by veteran systems engineers with over 15 years in high-density electronics.</p>
                </div>
                <div class="f-item">
                    <i class="fas fa-clock"></i>
                    <h4>Rapid Protocol</h4>
                    <p>Tiered response system ensuring critical failures are addressed within business hours.</p>
                </div>
                <div class="f-item">
                    <i class="fas fa-hand-holding-heart"></i>
                    <h4>Build Assurance</h4>
                    <p>Every intervention is logged and backed by our comprehensive service guarantee.</p>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="timeline-wrap">
            <div style="text-align: center; margin-bottom: 48px;">
                <h2 style="font-family: 'Manrope'; font-weight: 800; font-size: 32px; letter-spacing: -0.03em;">The Protocol</h2>
                <p style="color: var(--text-muted);">Scientific approach to technical service and hardware fulfillment.</p>
            </div>
            
            <div class="t-grid">
                <div class="t-step">
                    <div class="t-num">01</div>
                    <h4>Diagnosis</h4>
                    <p>Initial scanning and forensic health check of the hardware or architecture.</p>
                </div>
                <div class="t-step">
                    <div class="t-num">02</div>
                    <h4>Logistics</h4>
                    <p>Secure pickup or receipt into our technical facility for processing.</p>
                </div>
                <div class="t-step">
                    <div class="t-num">03</div>
                    <h4>Execution</h4>
                    <p>Technological intervention using high-precision tooling and OEM parts.</p>
                </div>
                <div class="t-step">
                    <div class="t-num">04</div>
                    <h4>Validation</h4>
                    <p>Post-repair stress testing and final QA before secure delivery or hand-off.</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div style="background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-xl); padding: 72px 48px; text-align: center; margin-bottom: 96px;">
            <h2 style="font-family: 'Manrope'; font-weight: 800; font-size: 36px; margin-bottom: 16px; letter-spacing: -0.04em;">Ready for Deployment?</h2>
            <p style="color: var(--text-mid); max-width: 500px; margin: 0 auto 32px;">Consult with our lead engineers today for custom builds or infrastructure audits.</p>
            <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
                <a href="contact.php" class="hero-btn-primary">Get in Touch</a>
                <a href="products.php" class="hero-btn-primary" style="background: var(--bg-white); color: var(--text-dark); border: 1px solid var(--border);">Browse Products</a>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
