<?php
include_once 'includes/config.php';
include 'includes/header.php';

// Fetch active PC Combos and Full Setups
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE (category = 'Pre-Built Combo' OR category = 'Full Setup') AND status = 'Active' ORDER BY id DESC");
    $stmt->execute();
    $all_items = $stmt->fetchAll();
    
    $combos = array_filter($all_items, function($i) { return $i['category'] === 'Pre-Built Combo'; });
    $setups = array_filter($all_items, function($i) { return $i['category'] === 'Full Setup'; });
} catch (PDOException $e) {
    $combos = $setups = [];
}
?>

<style>
    /* ═══════════════════════════════════════════════════
       PC BUILDER — Minimal Editorial Theme
       ═══════════════════════════════════════════════════ */

    /* ── Hero ──────────────────────────────────────────── */
    .builder-hero {
        padding: 148px 24px 72px;
        background: var(--bg-white);
        text-align: center;
        border-bottom: 1px solid var(--border);
    }

    /* section-title is handled globally in index.css */

    /* ── Tab Switcher ──────────────────────────────────── */
    .nav-tabs-custom {
        display: inline-flex;
        background: var(--bg);
        border: 1px solid var(--border);
        padding: 5px;
        border-radius: 100px;
        margin-bottom: 56px;
        gap: 4px;
    }

    .tab-btn {
        padding: 10px 28px;
        border-radius: 100px;
        border: none;
        background: transparent;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s var(--ease);
        color: var(--text-muted);
        font-size: 13px;
        font-family: 'Manrope', sans-serif;
        letter-spacing: 0.01em;
    }

    .tab-btn.active {
        background: var(--text-dark);
        color: #fff;
        box-shadow: var(--shadow-sm);
    }

    .tab-btn:hover:not(.active) {
        color: var(--text-dark);
    }

    /* ── PC Card ───────────────────────────────────────── */
    .pc-card {
        background: var(--bg-white);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        overflow: hidden;
        transition: border-color 0.25s var(--ease), box-shadow 0.3s var(--ease), transform 0.3s var(--ease);
        display: flex;
        flex-direction: row;
        align-items: stretch;
        position: relative;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto 28px;
        opacity: 0;
        transform: translateY(20px);
    }

    .pc-card.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .pc-card:hover {
        border-color: #333;
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
    }

    /* ── Card Image ────────────────────────────────────── */
    .pc-img-wrapper {
        width: 380px;
        min-width: 380px;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px;
        position: relative;
        overflow: hidden;
        border-right: 1px solid var(--border);
    }

    .pc-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.5s var(--ease);
        z-index: 2;
    }

    .pc-card:hover .pc-img-wrapper img {
        transform: scale(1.05);
    }

    /* ── Card Content ──────────────────────────────────── */
    .pc-content {
        padding: 48px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: var(--bg-white);
        position: relative;
    }

    .pc-category {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--accent);
        margin-bottom: 10px;
        display: inline-block;
    }

    .pc-title {
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: clamp(18px, 2vw, 24px);
        margin-bottom: 24px;
        color: var(--text-dark);
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    /* ── Specs Grid ────────────────────────────────────── */
    .pc-specs {
        margin-bottom: 28px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .spec-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        background: var(--bg);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        transition: border-color 0.2s, background 0.2s;
    }

    .spec-item:hover {
        border-color: #333;
        background: var(--bg-white);
    }

    .spec-item i {
        color: var(--accent);
        font-size: 15px;
        width: 18px;
        flex-shrink: 0;
    }

    .spec-item span {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
    }

    /* ── Performance Bar ───────────────────────────────── */
    .perf-meta {
        margin-bottom: 24px;
        padding: 14px 18px;
        background: var(--bg);
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
    }

    .perf-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
    }

    .perf-bar {
        height: 4px;
        background: var(--border);
        border-radius: 10px;
        overflow: hidden;
    }

    .perf-progress {
        height: 100%;
        background: var(--text-dark);
        border-radius: 10px;
        transition: width 1.5s var(--ease);
    }

    /* ── Pricing Row ───────────────────────────────────── */
    .pc-pricing {
        margin-top: auto;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
    }

    .price-group {
        display: flex;
        flex-direction: column;
    }

    .price-old {
        font-size: 13px;
        text-decoration: line-through;
        color: var(--text-muted);
        font-weight: 400;
        margin-bottom: -2px;
    }

    .price-new {
        font-family: 'Manrope', sans-serif;
        font-size: clamp(22px, 2.5vw, 30px);
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.03em;
    }

    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(52, 199, 89, 0.08);
        color: #1a9c3e;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid rgba(52, 199, 89, 0.2);
    }

    .stock-badge.out {
        background: var(--accent-muted);
        color: var(--accent);
        border-color: rgba(255, 59, 48, 0.15);
    }

    /* ── Action Buttons ────────────────────────────────── */
    .pc-actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
    }

    .btn-buy {
        flex: 2;
        background: var(--text-dark);
        color: #fff;
        padding: 14px 20px;
        border-radius: var(--radius-md);
        text-decoration: none !important;
        font-weight: 600;
        text-align: center;
        transition: background 0.25s var(--ease), transform 0.2s;
        font-size: 14px;
        font-family: 'Manrope', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-buy:hover {
        background: var(--accent);
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-cart, .btn-view {
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        font-weight: 600;
        transition: all 0.25s var(--ease);
        font-size: 16px;
        border: 1.5px solid var(--border);
        background: var(--bg-white);
        color: var(--text-dark);
        cursor: pointer;
    }

    .btn-cart:hover {
        background: var(--text-dark);
        color: #fff;
        border-color: var(--text-dark);
        transform: translateY(-1px);
    }

    .btn-view:hover {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
        transform: translateY(-1px);
    }

    /* ── Responsive ────────────────────────────────────── */
    @media (max-width: 991px) {
        .pc-card {
            flex-direction: column;
            max-width: 560px;
        }

        .pc-img-wrapper {
            width: 100%;
            min-width: unset;
            height: 280px;
            border-right: none;
            border-bottom: 1px solid var(--border);
        }

        .pc-content { padding: 32px; }
        .pc-specs   { grid-template-columns: 1fr; }
    }

    @media (max-width: 600px) {
        .pc-content { padding: 24px; }
        .pc-actions { flex-wrap: wrap; }
        .btn-buy    { flex: 1 1 100%; }
    }
    /* ── 3D Visualizer ────────────────────────────────── */
    #3d-section {
        padding-bottom: 80px;
    }
    .scroll-3d-wrap {
        max-width: 1100px;
        margin: 20px auto;
        height: 400vh; /* Tall scroll zone — 280 frames spread over ~4 viewports for smooth scrubbing */
        position: relative;
        background: transparent; /* Invisible container — only a scroll sentinel */
        overflow: visible;
        /* No border/background here — canvas is the visual element */
    }
    #builder3dCanvas {
        position: -webkit-sticky;
        position: sticky;
        top: 120px; /* Header offset */
        width: 100%;
        height: 80vh; /* Generous canvas viewport */
        display: block;
        z-index: 1;
        background: #000;
        border-radius: var(--radius-xl);
        border: 1px solid var(--border);
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    }
    #builder3dLoader {
        position: -webkit-sticky;
        position: sticky;
        top: 120px; /* Match canvas sticky position */
        width: 100%;
        height: 80vh;
        margin-top: -80vh; /* Pull up to overlay the canvas */
        background: #000;
        display: flex; align-items: center; justify-content: center;
        z-index: 10;
        transition: opacity 0.6s ease;
        pointer-events: none;
        border-radius: var(--radius-xl);
    }
    #builder3dLoader.hidden { opacity: 0; }
    .sv-spinner {
        width: 32px; height: 32px;
        border: 2px solid rgba(255,255,255,0.1);
        border-top-color: var(--accent);
        border-radius: 50%;
        animation: svSpin 0.8s linear infinite;
    }
    @keyframes svSpin { to { transform: rotate(360deg); } }
</style>

<main>
    <section class="builder-hero">
        <div class="container">
            <h1 class="section-title">Professional PC Builds</h1>
            <p style="font-size: 1.25rem; color: #86868b; max-width: 600px; margin: 0 auto 40px;">Choose from our expertly crafted pre-built gaming combos or complete professional workstations.</p>
            
            <div class="nav-tabs-custom">
                <button class="tab-btn active" onclick="showSection('prebuilt')">Pre-Built Combos</button>
                <button class="tab-btn" onclick="showSection('fullsetup')">Full PC Setups</button>
                <button class="tab-btn" onclick="showSection('3d')">3D View</button>
            </div>
        </div>
    </section>

    <div class="container pb-5">
        <!-- Pre-Built Combos Section -->
        <section id="prebuilt-section" class="content-section fade-in-up">
            <div class="row g-4">
                <?php if (empty($combos)): ?>
                    <div class="col-12 text-center py-5 glass-card" style="margin-top: 20px;">
                        <i class="fas fa-box-open fa-3x" style="color: #d2d2d7; margin-bottom: 20px; display: block;"></i>
                        <h3 style="color: #86868b;">No Pre-Built Combos available yet.</h3>
                    </div>
                <?php else: foreach($combos as $p): 
                    $perf = 60 + ($p['offer_price'] / 1000); 
                    $perf = min($perf, 98);
                ?>
                    <div class="col-12">
                        <div class="pc-card scroll-reveal">
                            <div class="pc-img-wrapper">
                                <img src="<?php echo $p['main_image']; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                            </div>
                            <div class="pc-content">
                                <span class="pc-category">Pre-Built Combo</span>
                                <h3 class="pc-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                                
                                <div class="perf-meta">
                                    <div class="perf-label">
                                        <span>Gaming Performance</span>
                                        <span><?php echo round($perf); ?>%</span>
                                    </div>
                                    <div class="perf-bar">
                                        <div class="perf-progress" style="width: 0%" data-width="<?php echo $perf; ?>%"></div>
                                    </div>
                                </div>

                                <div class="pc-specs">
                                    <div class="spec-item"><i class="fas fa-microchip"></i> <span><?php echo htmlspecialchars($p['processor']); ?></span></div>
                                    <div class="spec-item"><i class="fas fa-memory"></i> <span><?php echo htmlspecialchars($p['ram']); ?></span></div>
                                    <div class="spec-item"><i class="fas fa-video"></i> <span><?php echo htmlspecialchars($p['gpu']); ?></span></div>
                                    <div class="spec-item"><i class="fas fa-bolt"></i> <span><?php echo htmlspecialchars($p['smps']); ?></span></div>
                                </div>

                                <div class="pc-pricing">
                                    <div class="price-group">
                                        <div class="price-old">₹<?php echo number_format($p['price']); ?></div>
                                        <div class="price-new">₹<?php echo number_format($p['offer_price']); ?></div>
                                    </div>
                                    <div class="stock-badge <?php echo $p['stock']<=0?'out':''; ?>">
                                        <i class="fas <?php echo $p['stock']>0?'fa-check-circle':'fa-times-circle'; ?>"></i>
                                        <?php echo $p['stock']>0?'Ready to Ship':'Sold Out'; ?>
                                    </div>
                                </div>

                                <div class="pc-actions">
                                    <a href="checkout.php?direct_buy=<?php echo $p['id']; ?>" class="btn-buy">Purchase Build</a>
                                    <a href="product-details.php?id=<?php echo $p['id']; ?>" class="btn-view" title="View Details"><i class="fas fa-info-circle"></i></a>
                                    <button onclick="addToCart(<?php echo $p['id']; ?>)" class="btn-cart" title="Add to Bag"><i class="fas fa-shopping-bag"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </section>
                        
        <!-- Full Setup Section -->
        <section id="fullsetup-section" class="content-section fade-in-up" style="display: none;">
            <div class="row g-4">
                <?php if (empty($setups)): ?>
                    <div class="col-12 text-center py-5 glass-card" style="margin-top: 20px;">
                        <i class="fas fa-desktop fa-3x" style="color: #d2d2d7; margin-bottom: 20px; display: block;"></i>
                        <h3 style="color: #86868b;">No Full Setups available yet.</h3>
                    </div>
                <?php else: foreach($setups as $p): 
                    $perf = 75 + ($p['offer_price'] / 1500);
                    $perf = min($perf, 99);
                ?>
                    <div class="col-12">
                        <div class="pc-card scroll-reveal">
                            <div class="pc-img-wrapper">
                                <img src="<?php echo $p['main_image']; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                            </div>
                            <div class="pc-content">
                                <span class="pc-category">Full PC Setup</span>
                                <h3 class="pc-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                                
                                <div class="perf-meta">
                                    <div class="perf-label">
                                        <span>System Capability</span>
                                        <span><?php echo round($perf); ?>%</span>
                                    </div>
                                    <div class="perf-bar">
                                        <div class="perf-progress" style="width: 0%" data-width="<?php echo $perf; ?>%"></div>
                                    </div>
                                </div>

                                <div class="pc-specs">
                                    <div class="spec-item"><i class="fas fa-microchip"></i> <span><?php echo htmlspecialchars($p['processor']); ?></span></div>
                                    <div class="spec-item"><i class="fas fa-tv"></i> <span><?php echo htmlspecialchars($p['monitor']); ?></span></div>
                                    <div class="spec-item"><i class="fas fa-keyboard"></i> <span><?php echo htmlspecialchars($p['peripherals']); ?></span></div>
                                    <div class="spec-item"><i class="fas fa-hdd"></i> <span><?php echo htmlspecialchars($p['storage']); ?></span></div>
                                </div>

                                <div class="pc-pricing">
                                    <div class="price-group">
                                        <div class="price-old">₹<?php echo number_format($p['price']); ?></div>
                                        <div class="price-new">₹<?php echo number_format($p['offer_price']); ?></div>
                                    </div>
                                    <div class="stock-badge <?php echo $p['stock']<=0?'out':''; ?>">
                                        <i class="fas <?php echo $p['stock']>0?'fa-check-circle':'fa-times-circle'; ?>"></i>
                                        <?php echo $p['stock']>0?'In Stock':'Unavailable'; ?>
                                    </div>
                                </div>

                                <div class="pc-actions">
                                    <a href="checkout.php?direct_buy=<?php echo $p['id']; ?>" class="btn-buy">Complete Order</a>
                                    <a href="product-details.php?id=<?php echo $p['id']; ?>" class="btn-view" title="View Details"><i class="fas fa-info-circle"></i></a>
                                    <button onclick="addToCart(<?php echo $p['id']; ?>)" class="btn-cart" title="Add to Bag"><i class="fas fa-shopping-bag"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </section>

        <!-- 3D Experience Section -->
        <section id="3d-section" class="content-section fade-in-up" style="display: none;">
            <div class="scroll-3d-wrap">
                <canvas id="builder3dCanvas"></canvas>
                <div id="builder3dLoader"><div class="sv-spinner"></div></div>
            </div>
        </section>
    </div>
</main>

<script>
// Global Observer
const observerOptions = { threshold: 0.1, rootMargin: "0px 0px -50px 0px" };
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            const bar = entry.target.querySelector('.perf-progress');
            if (bar) setTimeout(() => bar.style.width = bar.getAttribute('data-width'), 400);
        }
    });
}, observerOptions);

document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));

function showSection(id) {
    window.scrollTo({ top: 0, behavior: 'instant' });
    document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');
    const target = document.getElementById(id + '-section');
    if (target) {
        target.style.display = 'block';
        if (id === '3d') {
            setTimeout(() => { 
                if (window.trigger3dUpdate) window.trigger3dUpdate(); 
            }, 50);
        }
    }
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.toggle('active', b.getAttribute('onclick').includes(id)));
}

function addToCart(pid) {
    fetch('cart.php?add=' + pid).then(() => { alert('Added to bag!'); window.location.reload(); });
}

/* ── 3D Scroll Animation Engine (Apple-style) ── */
(function() {
    const canvas = document.getElementById('builder3dCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const loader = document.getElementById('builder3dLoader');

    // ── Configuration ──
    const FRAME_COUNT       = 280;                          // Total frames in sequence
    const FRAME_PATH        = 'assets/images/3d1/';         // Folder path
    const FRAME_PREFIX      = 'ezgif-frame-';               // Filename prefix
    const FRAME_EXT         = '.jpg';                       // File extension
    const LERP_FACTOR       = 0.08;                         // Smoothing factor (lower = smoother, 0.04–0.12 sweet spot)
    const PRIORITY_COUNT    = 30;                           // Frames to load first (high priority)
    const BATCH_SIZE        = 10;                           // Concurrent loads per batch after priority
    const READY_THRESHOLD   = PRIORITY_COUNT;               // Minimum frames before hiding loader

    // ── State ──
    const frames        = new Array(FRAME_COUNT);
    let loadedCount     = 0;
    let currentSmooth   = 0;                                // Lerp-interpolated frame position
    let targetFrame     = 0;                                // Raw scroll-mapped frame index
    let lastRendered    = -1;                                // Last frame index drawn
    let rafId           = null;
    let isActive        = false;
    let loaderHidden    = false;
    let canvasW         = 0;
    let canvasH         = 0;

    // ── Helpers ──
    function frameSrc(i) {
        return FRAME_PATH + FRAME_PREFIX + String(i).padStart(3, '0') + FRAME_EXT;
    }

    function syncCanvasSize() {
        const w = canvas.offsetWidth  * (window.devicePixelRatio || 1);
        const h = canvas.offsetHeight * (window.devicePixelRatio || 1);
        if (canvas.width !== w || canvas.height !== h) {
            canvas.width  = w;
            canvas.height = h;
            canvasW = w;
            canvasH = h;
            return true; // size changed
        }
        return false;
    }

    // ── Render a single frame (cover-fit) ──
    function renderFrame(img) {
        if (!img || !img.complete || img.naturalWidth === 0) return;
        syncCanvasSize();
        if (canvasW === 0 || canvasH === 0) return;

        const imgRatio    = img.naturalWidth / img.naturalHeight;
        const canvasRatio = canvasW / canvasH;
        let dw, dh, dx, dy;

        // Cover: fill entire canvas, crop overflow
        if (canvasRatio > imgRatio) {
            dw = canvasW;
            dh = canvasW / imgRatio;
            dx = 0;
            dy = (canvasH - dh) / 2;
        } else {
            dh = canvasH;
            dw = canvasH * imgRatio;
            dx = (canvasW - dw) / 2;
            dy = 0;
        }

        ctx.clearRect(0, 0, canvasW, canvasH);
        ctx.drawImage(img, dx, dy, dw, dh);
    }

    // ── Scroll → target frame index ──
    function computeTargetFrame() {
        const section = document.getElementById('3d-section');
        if (!section || section.style.display === 'none') { isActive = false; return; }
        isActive = true;
        const wrap = document.querySelector('.scroll-3d-wrap');
        if (!wrap) return;
        
        const rect       = wrap.getBoundingClientRect();
        const startTop   = 120; // The top offset where sticky begins
        const scrollable = wrap.offsetHeight - canvas.offsetHeight;
        
        if (scrollable <= 0) { targetFrame = 0; return; }
        
        // Calculate progress exactly during the sticky period
        const progress   = Math.max(0, Math.min(1, (startTop - rect.top) / scrollable));
        targetFrame      = progress * (FRAME_COUNT - 1);
    }

    // ── Animation Loop (RAF) ──
    function tick() {
        rafId = requestAnimationFrame(tick);
        if (!isActive) return;

        // Lerp toward target
        currentSmooth += (targetFrame - currentSmooth) * LERP_FACTOR;

        // Snap when very close to avoid jitter
        if (Math.abs(currentSmooth - targetFrame) < 0.2) currentSmooth = targetFrame;

        const idx = Math.round(Math.max(0, Math.min(FRAME_COUNT - 1, currentSmooth)));

        if (idx !== lastRendered && frames[idx]) {
            lastRendered = idx;
            renderFrame(frames[idx]);
        }
    }

    // ── Preloader ──
    function loadImage(index) {
        return new Promise(resolve => {
            const img      = new Image();
            img.decoding   = 'async';
            img.src        = frameSrc(index + 1); // 1-based filenames
            img.onload     = () => {
                frames[index] = img;
                loadedCount++;
                onFrameLoaded(index);
                resolve();
            };
            img.onerror    = () => {
                // Skip broken frames silently
                loadedCount++;
                resolve();
            };
        });
    }

    function onFrameLoaded(index) {
        // Show first frame immediately to prevent black screen
        if (index === 0 && lastRendered === -1) {
            syncCanvasSize();
            renderFrame(frames[0]);
            lastRendered = 0;
        }

        // Hide loader once enough priority frames are loaded
        if (!loaderHidden && loadedCount >= READY_THRESHOLD) {
            loaderHidden = true;
            if (loader) {
                loader.classList.add('hidden');
                setTimeout(() => { loader.style.display = 'none'; }, 800);
            }
        }
    }

    async function preloadAll() {
        // Phase 1: Load priority frames (first N) concurrently
        const priorityPromises = [];
        for (let i = 0; i < Math.min(PRIORITY_COUNT, FRAME_COUNT); i++) {
            priorityPromises.push(loadImage(i));
        }
        await Promise.all(priorityPromises);

        // Phase 2: Load remaining frames in controlled batches
        const remaining = [];
        for (let i = PRIORITY_COUNT; i < FRAME_COUNT; i++) {
            remaining.push(i);
        }

        for (let b = 0; b < remaining.length; b += BATCH_SIZE) {
            const batch = remaining.slice(b, b + BATCH_SIZE);
            await Promise.all(batch.map(i => loadImage(i)));
        }
    }

    // ── Resize handling via ResizeObserver ──
    let resizeObserver = null;
    if (typeof ResizeObserver !== 'undefined') {
        resizeObserver = new ResizeObserver(() => {
            if (syncCanvasSize() && frames[lastRendered >= 0 ? lastRendered : 0]) {
                renderFrame(frames[lastRendered >= 0 ? lastRendered : 0]);
            }
        });
        resizeObserver.observe(canvas);
    }

    // Fallback resize for older browsers
    window.addEventListener('resize', () => {
        if (syncCanvasSize() && frames[lastRendered >= 0 ? lastRendered : 0]) {
            renderFrame(frames[lastRendered >= 0 ? lastRendered : 0]);
        }
    }, { passive: true });

    // ── Scroll listener ──
    window.addEventListener('scroll', computeTargetFrame, { passive: true });

    // ── Public trigger for tab switching ──
    window.trigger3dUpdate = function() {
        computeTargetFrame();
        // Force re-render the current frame after tab show
        syncCanvasSize();
        const idx = Math.round(Math.max(0, Math.min(FRAME_COUNT - 1, currentSmooth)));
        if (frames[idx]) renderFrame(frames[idx]);
        else if (frames[0]) renderFrame(frames[0]);
    };

    // ── Fill canvas black to prevent flash of white ──
    syncCanvasSize();
    ctx.fillStyle = '#000';
    ctx.fillRect(0, 0, canvasW || canvas.width, canvasH || canvas.height);

    // ── Start ──
    preloadAll();
    rafId = requestAnimationFrame(tick);
})();
</script>

<?php include 'includes/footer.php'; ?>
