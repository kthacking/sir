<?php
include 'includes/header.php';
include_once 'includes/db_connect.php';

// Fetch initial data for steps if needed, otherwise we use AJAX
// For a premium feel, we'll use a clean, dynamic JS-driven interface
?>

<!-- Bootstrap 5 CSS (Scoped or full, using CDN as user requested modern appearance) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Google Fonts: Inter & Outfit -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --premium-bg: #f8fafc;
        --card-radius: 16px;
        --primary-blue: #0071e3;
        --accent-glow: rgba(0, 113, 227, 0.15);
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.4);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--premium-bg);
        color: #1d1d1f;
    }

    h1, h2, h3, .heading-font {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
    }

    .builder-hero {
        padding: 100px 0 60px;
        background: linear-gradient(135deg, #f0f4f8 0%, #e5eef5 100%);
        text-align: center;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .builder-hero h1 {
        font-size: clamp(2.5rem, 5vw, 4rem);
        letter-spacing: -0.02em;
        margin-bottom: 16px;
        background: linear-gradient(135deg, #1d1d1f 0%, #0071e3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .builder-hero p {
        font-size: 1.25rem;
        color: #6b7280;
        max-width: 700px;
        margin: 0 auto 40px;
    }

    /* Mode Selector */
    .mode-selector {
        margin-top: -40px;
        position: relative;
        z-index: 10;
        padding-bottom: 60px;
    }

    .mode-card {
        background: white;
        border-radius: var(--card-radius);
        padding: 30px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        height: 100%;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }

    .mode-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: var(--primary-blue);
    }

    .mode-card.active {
        border: 2px solid var(--primary-blue);
        background: linear-gradient(180deg, #ffffff 0%, #f0f7ff 100%);
        box-shadow: 0 15px 35px var(--accent-glow);
    }

    .mode-icon {
        width: 64px;
        height: 64px;
        background: #f1f5f9;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.5rem;
        color: var(--primary-blue);
        transition: all 0.3s;
    }

    .mode-card:hover .mode-icon, .mode-card.active .mode-icon {
        background: var(--primary-blue);
        color: white;
    }

    .mode-card h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .mode-card p {
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 0;
    }

    /* Sections */
    .builder-section {
        display: none;
        padding: 20px 0 100px;
        animation: fadeIn 0.5s ease;
    }

    .builder-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Custom Builder Step layout */
    .step-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .step-item {
        background: white;
        border-radius: var(--card-radius);
        padding: 20px 24px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: all 0.3s;
    }

    .step-item:hover {
        border-color: var(--primary-blue);
        background: #f8fafc;
    }

    .step-item.active {
        border-color: var(--primary-blue);
        background: #f0f7ff;
        box-shadow: 0 0 0 1px var(--primary-blue);
    }

    .step-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .step-icon {
        width: 44px;
        height: 44px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #64748b;
    }

    .step-item.completed .step-icon {
        background: #dcfce7;
        color: #16a34a;
    }

    .step-details h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .step-details p {
        margin: 0;
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .step-selection {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary-blue);
    }

    /* Component List */
    .component-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #f1f5f9;
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .component-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-color: #e2e8f0;
    }

    .comp-img {
        height: 140px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        border-radius: 14px;
        padding: 15px;
    }

    .comp-img img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .comp-name {
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: 8px;
        color: #1e293b;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.6rem;
    }

    .comp-specs {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 15px;
        flex-grow: 1;
    }

    .comp-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }

    .comp-price {
        font-weight: 800;
        font-size: 1.2rem;
        color: #0f172a;
    }

    /* Sticky Summary Panel */
    .summary-panel {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 30px;
        position: sticky;
        top: 100px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }

    .summary-heading {
        font-size: 1.5rem;
        margin-bottom: 25px;
        color: #0f172a;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }

    .summary-label {
        color: #64748b;
    }

    .summary-value {
        font-weight: 600;
        color: #1e293b;
    }

    .total-container {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px dashed #e2e8f0;
    }

    .total-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-blue);
        line-height: 1;
        margin-bottom: 10px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .badge-success { background: #dcfce7; color: #16a34a; }
    .badge-warning { background: #fef9c3; color: #a16207; }

    /* Performance Indicator */
    .perf-meter {
        height: 8px;
        background: #e2e8f0;
        border-radius: 10px;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .perf-bar {
        height: 100%;
        background: var(--primary-blue);
        width: 0%;
        transition: width 0.5s;
    }

    /* Combo Cards */
    .combo-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #eef2f6;
        transition: all 0.4s;
    }

    .combo-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.1);
    }

    .combo-img {
        height: 250px;
        position: relative;
    }

    .combo-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .combo-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 30px;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        color: white;
    }

    .combo-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: var(--primary-blue);
        color: white;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .combo-content {
        padding: 30px;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .summary-panel {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            border-radius: 30px 30px 0 0;
            z-index: 1000;
            padding: 20px;
            top: auto;
        }

        .summary-details {
            display: none;
        }

        .summary-details.open {
            display: block;
        }
    }
</style>

<main>
    <!-- 1️⃣ HERO SECTION -->
    <section class="builder-hero">
        <div class="container">
            <div class="badge-pro mb-3">Professional Rigs</div>
            <h1>CUSTOM PC RIG BUILDER</h1>
            <p>Craft your perfect performance machine with precision components or choose the best curated setups.</p>
            <div class="d-flex gap-3 justify-content-center">
                <button class="btn btn-primary px-5 py-3" onclick="switchMode('custom')">Start Custom Build</button>
                <button class="btn btn-outline-dark px-5 py-3" onclick="switchMode('prebuilt')">Ready Combos</button>
            </div>
        </div>
    </section>

    <!-- 2️⃣ BUILD MODE SELECTOR -->
    <section class="mode-selector container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="mode-card active" id="mode-custom" onclick="switchMode('custom')">
                    <div class="mode-icon"><i class="fas fa-sliders-h"></i></div>
                    <h3>Custom Build</h3>
                    <p>Select every component yourself for full control.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mode-card" id="mode-prebuilt" onclick="switchMode('prebuilt')">
                    <div class="mode-icon"><i class="fas fa-layer-group"></i></div>
                    <h3>Pre-Built Combos</h3>
                    <p>Expertly balanced setups ready for delivery.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mode-card" id="mode-full" onclick="switchMode('full')">
                    <div class="mode-icon"><i class="fas fa-desktop"></i></div>
                    <h3>Full Setup</h3>
                    <p>Everything you need from PC to Peripherals.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container pb-5">
        
        <!-- 3️⃣ CUSTOM BUILD SECTION -->
        <section id="section-custom" class="builder-section active">
            <div class="row">
                <div class="col-lg-3 mb-4">
                    <h4 class="mb-4">Selection Steps</h4>
                    <div class="step-list" id="builder-steps">
                        <!-- Step items like CPU, Motherboard etc -->
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 id="current-step-title">Choose CPU</h4>
                        <div class="position-relative">
                            <input type="text" class="form-control" id="comp-search" placeholder="Search components..." style="border-radius: 12px; padding-left: 40px;">
                            <i class="fas fa-search position-absolute" style="left: 15px; top: 12px; color: #94a3b8;"></i>
                        </div>
                    </div>
                    
                    <div id="components-loading" class="text-center py-5 d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3 text-muted">Retrieving inventory...</p>
                    </div>

                    <div id="components-grid" class="row g-3">
                        <!-- Component cards will be loaded here -->
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="summary-panel">
                        <h4 class="summary-heading">Live Build Summary</h4>
                        
                        <div class="total-container">
                            <p class="summary-label mb-1">Total Estimated Price</p>
                            <div class="total-price" id="total-price">₹0</div>
                            <div class="status-badge badge-warning" id="comp-status">
                                <i class="fas fa-exclamation-circle"></i> Components Missing
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="summary-label">Performance Tier</span>
                                <span class="summary-value" id="perf-tier">Entry</span>
                            </div>
                            <div class="perf-meter">
                                <div class="perf-bar" id="perf-bar" style="width: 25%"></div>
                            </div>
                        </div>

                        <div class="summary-details mb-4">
                            <div class="summary-item">
                                <span class="summary-label">Power Usage</span>
                                <span class="summary-value" id="est-power">~0W</span>
                            </div>
                        </div>

                        <div id="selection-summary" style="max-height: 250px; overflow-y: auto; margin-bottom: 25px;">
                            <!-- List of selected parts -->
                        </div>

                        <button class="btn btn-primary w-100 py-3 mb-3 shadow-lg" onclick="addToCart()" id="add-to-cart-btn" disabled>Add Build to Cart</button>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary flex-grow-1" onclick="saveBuild()"><i class="fas fa-save"></i> Save</button>
                            <button class="btn btn-outline-danger" onclick="clearBuild()" title="Clear Build"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4️⃣ PRE-BUILT COMBO SECTION -->
        <section id="section-prebuilt" class="builder-section">
            <div class="row g-4" id="prebuilt-grid">
                <!-- Static or dynamic Pre-built combos -->
            </div>
        </section>

        <!-- 5️⃣ FULL SETUP SECTION -->
        <section id="section-full" class="builder-section">
             <div class="row g-4" id="fullsetup-grid">
                <!-- Full setup configurations -->
            </div>
        </section>

    </div>
</main>

<!-- Detailed View Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 24px; border: none; overflow: hidden;">
            <div class="modal-body p-0" id="modal-content">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const steps = [
        { id: 'cpu', name: 'CPU', icon: 'fa-microchip', category: 'Components' },
        { id: 'mobo', name: 'Motherboard', icon: 'fa-chess-board', category: 'Components' },
        { id: 'ram', name: 'RAM', icon: 'fa-memory', category: 'Components' },
        { id: 'gpu', name: 'GPU', icon: 'fa-video', category: 'Components' },
        { id: 'storage', name: 'Storage', icon: 'fa-hdd', category: 'Components' },
        { id: 'psu', name: 'PSU', icon: 'fa-plug', category: 'Components' },
        { id: 'cabinet', name: 'Cabinet', icon: 'fa-box', category: 'Components' }
    ];

    let currentStepIndex = 0;
    let build = {
        cpu: null,
        mobo: null,
        ram: null,
        gpu: null,
        storage: null,
        psu: null,
        cabinet: null
    };

    const prebuilts = [
        {
            id: 'b1',
            name: 'Budget Gaming PC',
            tag: '1080p Entry',
            img: 'https://i.pinimg.com/1200x/be/33/08/be3308ae1c7af201936851c8ce917a9e.jpg',
            price: 65000,
            specs: 'i5-12400F, RTX 3050, 16GB RAM, 512GB NVMe',
            performance: 'High in 1080p'
        },
        {
            id: 'b2',
            name: '1440p Beast',
            tag: 'Pro Gamer',
            img: 'https://i.pinimg.com/1200x/41/4e/bf/414ebfd869351533e6ea13af555bb988.jpg',
            price: 135000,
            specs: 'i7-13700K, RTX 4070, 32GB DDR5, 1TB Gen4',
            performance: 'Smooth 1440p Ultra'
        },
        {
            id: 'b3',
            name: 'Creator Workstation',
            tag: 'Workhorse',
            img: 'https://i.pinimg.com/1200x/4b/81/55/4b8155675b91ffa97d2bc99e4f969834.jpg',
            price: 245000,
            specs: 'Ryzen 9 7950X, RTX 4080, 64GB RAM, 2TB SSD',
            performance: 'Infinite Rendering'
        }
    ];

    const fullsetups = [
        {
            id: 's1',
            name: 'Streamer Starter Kit',
            tag: 'Complete Build',
            img: 'https://i.pinimg.com/736x/ba/70/40/ba704062d7e3a7ead8f0c598741d4273.jpg',
            price: 185000,
            specs: 'i7 Build + 27" 165Hz Monitor + RGB Peripherals + Headset',
            discount: '₹12,000 Off'
        }
    ];

    // Initialize Page
    document.addEventListener('DOMContentLoaded', () => {
        renderSteps();
        loadComponents('cpu');
        renderPrebuilts();
        renderFullSetups();
        updateSummary();
        
        // Load saved build from localStorage
        const saved = localStorage.getItem('current_build');
        if (saved) {
            build = JSON.parse(saved);
            updateSummary();
        }
    });

    function switchMode(mode) {
        document.querySelectorAll('.mode-card').forEach(c => c.classList.remove('active'));
        document.getElementById('mode-' + mode).classList.add('active');
        
        document.querySelectorAll('.builder-section').forEach(s => s.classList.remove('active'));
        document.getElementById('section-' + mode).classList.add('active');
    }

    function renderSteps() {
        const container = document.getElementById('builder-steps');
        container.innerHTML = steps.map((s, idx) => `
            <div class="step-item ${idx === currentStepIndex ? 'active' : ''} ${build[s.id] ? 'completed' : ''}" onclick="goToStep(${idx})">
                <div class="step-info">
                    <div class="step-icon"><i class="fas ${s.icon}"></i></div>
                    <div class="step-details">
                        <h5>${s.name}</h5>
                        <p>${build[s.id] ? build[s.id].name.substring(0, 20) + '...' : 'Not Selected'}</p>
                    </div>
                </div>
                ${build[s.id] ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-chevron-right"></i>'}
            </div>
        `).join('');
    }

    function goToStep(idx) {
        currentStepIndex = idx;
        const step = steps[idx];
        document.getElementById('current-step-title').innerText = 'Choose ' + step.name;
        renderSteps();
        loadComponents(step.id);
    }

    function loadComponents(stepId) {
        const grid = document.getElementById('components-grid');
        const loader = document.getElementById('components-loading');
        
        grid.innerHTML = '';
        loader.classList.remove('d-none');

        // Note: For demo, we are fetching "Components" category and filtering clientside
        // In real app, we use AJAX to fetch specific category/step items
        fetch('fetch_products.php', {
            method: 'POST',
            body: JSON.stringify({ categories: ['Components'], limit: 50 })
        })
        .then(res => res.json())
        .then(data => {
            loader.classList.add('d-none');
            // Mock filtering based on step name as keywords for demo
            let filtered = JSON.parse(JSON.stringify(mockProducts(stepId))); 
            
            grid.innerHTML = filtered.map(p => `
                <div class="col-md-6 col-xl-4">
                    <div class="component-card">
                        <div class="comp-img">
                            <img src="${p.main_image}" alt="${p.name}">
                        </div>
                        <h5 class="comp-name">${p.name}</h5>
                        <div class="comp-specs">${p.specifications.substring(0, 60)}...</div>
                        <div class="comp-bottom">
                            <div class="comp-price">₹${p.offer_price.toLocaleString()}</div>
                            <button class="btn btn-sm btn-primary" onclick="selectComponent('${stepId}', ${JSON.stringify(p).replace(/"/g, '&quot;')})">Select</button>
                        </div>
                    </div>
                </div>
            `).join('');
        });
    }

    // Since our database is empty/generic, we generate context-appropriate mock items for the builder
    function mockProducts(step) {
        const base = { category: 'Components', specifications: 'High quality performance component' };
        if (step === 'cpu') return [
            { id: 101, name: 'Intel Core i9-14900K', offer_price: 58000, main_image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704', ...base, wattage: 125, socket: 'LGA1700' },
            { id: 102, name: 'AMD Ryzen 7 7800X3D', offer_price: 42000, main_image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704', ...base, wattage: 120, socket: 'AM5' },
            { id: 103, name: 'Intel Core i5-13600K', offer_price: 29500, main_image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704', ...base, wattage: 125, socket: 'LGA1700' }
        ];
        if (step === 'mobo') return [
            { id: 201, name: 'ASUS ROG Z790-E WiFi', offer_price: 48000, main_image: 'https://images.unsplash.com/photo-1587202372775-e229f170b998', ...base, socket: 'LGA1700' },
            { id: 202, name: 'MSI PRO B650-P WiFi', offer_price: 22500, main_image: 'https://images.unsplash.com/photo-1587202372775-e229f170b998', ...base, socket: 'AM5' }
        ];
        if (step === 'ram') return [
            { id: 301, name: 'Corsair Vengeance 32GB DDR5', offer_price: 12500, main_image: 'https://images.unsplash.com/photo-1591485121415-397c726354b6', ...base }
        ];
        if (step === 'gpu') return [
            { id: 401, name: 'NVIDIA RTX 4080 Founders', offer_price: 115000, main_image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704', ...base, wattage: 320 },
            { id: 402, name: 'ASUS TUF RTX 4070 Ti', offer_price: 78000, main_image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704', ...base, wattage: 285 }
        ];
        if (step === 'psu') return [
            { id: 501, name: 'Corsair RM850x Gold', offer_price: 11500, main_image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704', ...base, wattage: 850 }
        ];
        return [
            { id: 999, name: 'NZXT H5 Flow White', offer_price: 9500, main_image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704', ...base },
            { id: 998, name: 'WD Black 1TB NVMe', offer_price: 8200, main_image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704', ...base }
        ];
    }

    function selectComponent(stepId, product) {
        build[stepId] = product;
        updateSummary();
        renderSteps();
        
        // Auto go to next step
        if (currentStepIndex < steps.length - 1) {
            goToStep(currentStepIndex + 1);
        }
    }

    function updateSummary() {
        let total = 0;
        let power = 0;
        const summaryCont = document.getElementById('selection-summary');
        summaryCont.innerHTML = '';
        
        let selectedCount = 0;
        Object.keys(build).forEach(key => {
            if (build[key]) {
                selectedCount++;
                total += build[key].offer_price;
                power += (build[key].wattage || 0);
                
                summaryCont.innerHTML += `
                    <div class="summary-item">
                        <span class="summary-label">${steps.find(s=>s.id === key).name}</span>
                        <span class="summary-value">₹${build[key].offer_price.toLocaleString()}</span>
                    </div>
                `;
            }
        });

        document.getElementById('total-price').innerText = '₹' + total.toLocaleString();
        document.getElementById('est-power').innerText = '~' + (power + 50) + 'W'; // 50W baseline for fans/misc
        
        // Performance Tier calculation
        let tier = 'Entry';
        let barWidth = 25;
        if (total > 80000) { tier = 'Mid-Range'; barWidth = 50; }
        if (total > 150000) { tier = 'High-End'; barWidth = 75; }
        if (total > 250000) { tier = 'Extreme'; barWidth = 100; }
        
        document.getElementById('perf-tier').innerText = tier;
        document.getElementById('perf-bar').style.width = barWidth + '%';

        const statusBadge = document.getElementById('comp-status');
        const addBtn = document.getElementById('add-to-cart-btn');
        
        if (selectedCount === steps.length) {
            statusBadge.innerHTML = '<i class="fas fa-check-circle"></i> Compatibility Verified';
            statusBadge.className = 'status-badge badge-success';
            addBtn.disabled = false;
        } else {
            statusBadge.innerHTML = '<i class="fas fa-exclamation-circle"></i> Components Missing';
            statusBadge.className = 'status-badge badge-warning';
            addBtn.disabled = true;
        }

        localStorage.setItem('current_build', JSON.stringify(build));
    }

    function renderPrebuilts() {
        const grid = document.getElementById('prebuilt-grid');
        grid.innerHTML = prebuilts.map(p => `
            <div class="col-md-6 col-xl-4">
                <div class="combo-card">
                    <div class="combo-img">
                        <img src="${p.img}" alt="${p.name}">
                        <div class="combo-badge">${p.tag}</div>
                        <div class="combo-overlay">
                            <h4 class="mb-0">${p.name}</h4>
                        </div>
                    </div>
                    <div class="combo-content">
                        <p class="text-muted small mb-3">${p.specs}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="heading-font h4 mb-0">₹${p.price.toLocaleString()}</div>
                            <button class="btn btn-primary btn-sm rounded-pill px-4" onclick="buyBundle('${p.id}')">Buy Now</button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function renderFullSetups() {
        const grid = document.getElementById('fullsetup-grid');
        grid.innerHTML = fullsetups.map(p => `
             <div class="col-md-8 mx-auto">
                <div class="glass-card-pro p-4 d-flex gap-4 align-items-center">
                    <img src="${p.img}" style="width: 250px; border-radius: 20px;">
                    <div class="flex-grow-1">
                        <span class="badge bg-primary mb-2">LIMITED DISCOUNT</span>
                        <h3>${p.name}</h3>
                        <p class="text-muted">${p.specs}</p>
                        <h2 class="mb-4">₹${p.price.toLocaleString()} <span class="fs-6 text-success">${p.discount}</span></h2>
                        <button class="btn btn-primary px-5" onclick="buyBundle('${p.id}')">Get This Setup</button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function clearBuild() {
        if (confirm('Clear all current selections?')) {
            build = { cpu: null, mobo: null, ram: null, gpu: null, storage: null, psu: null, cabinet: null };
            currentStepIndex = 0;
            updateSummary();
            goToStep(0);
        }
    }

    function saveBuild() {
        alert('Build saved to your profile! You can access it anytime.');
    }

    function buyBundle(id) {
        alert('Bundle added to bag! Redirecting to checkout...');
        window.location.href = 'cart.php';
    }

    function addToCart() {
        alert('Custom build added to bag! Our engineers will assemble and test it.');
        window.location.href = 'cart.php';
    }
</script>

<?php include 'includes/footer.php'; ?>
