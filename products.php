<?php
include 'includes/header.php';

// Initial counts and categories for sidebar
$cat_stmt = $pdo->query("SELECT main_category, subcategory, COUNT(*) as count FROM products GROUP BY main_category, subcategory ORDER BY main_category ASC");
$raw_categories = $cat_stmt->fetchAll();
$db_categories = [];
foreach ($raw_categories as $row) {
    if (!isset($db_categories[$row['main_category']])) {
        $db_categories[$row['main_category']] = [];
    }
    $db_categories[$row['main_category']][] = $row;
}

$brand_stmt = $pdo->query("SELECT brand, COUNT(*) as count FROM products GROUP BY brand");
$db_brands = $brand_stmt->fetchAll();
?>

<main style="padding-top: 108px; background: var(--bg); min-height: 100vh;">
    <div class="container" style="max-width: 1280px; margin: 0 auto; padding: 28px 20px 72px 48px;">
        
        <!-- Breadcrumb & Minimal Title Section -->
        <div style="margin-bottom: 32px;" class="fade-in-up">
            <div style="margin-bottom: 8px;">
                <ul style="display: flex; gap: 8px; list-style: none; font-size: 13px; color: #6b7280;">
                    <li><a href="index.php" style="color: #6b7280; text-decoration: none;">Home</a></li>
                    <li>/</li>
                    <li><span style="color: #111827; font-weight: 500;">Products</span></li>
                </ul>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #111827; margin-bottom: 4px;">Explore Gear</h1>
                    <p id="productCountDisplay" style="color: #6b7280; font-size: 14px;">Loading collection...</p>
                </div>
                
                <div style="display: flex; gap: 12px; align-items: center;">
                    <button id="mobileFilterBtn" onclick="toggleMobileSidebar()" style="display: none; background: white; border: 1px solid #e5e7eb; padding: 8px 16px; border-radius: 10px; font-weight: 600; cursor: pointer; align-items: center; gap: 8px; font-size: 14px;">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <select id="sortOrder" onchange="filterProducts()" style="padding: 8px 16px; border-radius: 10px; border: 1px solid #e5e7eb; background: white; font-size: 14px; color: #111827; outline: none; cursor: pointer; min-width: 160px;">
                        <option value="latest">Latest</option>
                        <option value="price_low">Price: Low - High</option>
                        <option value="price_high">Price: High - Low</option>
                        <option value="popular">Popularity</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 48px; align-items: start;">
            
            <!-- 📁 Compact Filter Sidebar -->
            <aside id="filterSidebar" class="filter-sidebar fade-in-up">
                <div style="background: white; border-radius: 22px; border: 1px solid #e5e7eb; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <span style="font-size: 16px; font-weight: 700; color: #111827;">Filters</span>
                        <button onclick="clearAllFilters()" style="background: none; border: none; color: #2563eb; font-size: 12px; font-weight: 600; cursor: pointer;">Clear All</button>
                    </div>

                    <!-- Categories -->
                    <?php foreach ($db_categories as $mainCat => $subs): ?>
                    <div class="filter-group">
                        <div class="filter-header" onclick="toggleFilterGroup(this)">
                            <span><?php echo htmlspecialchars($mainCat ?: 'Others'); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="filter-content scroll-mini show">
                            <?php foreach($subs as $sub_row): 
                                $sub = $sub_row['subcategory'];
                            ?>
                            <label class="filter-label">
                                <input type="checkbox" name="category" value="<?php echo htmlspecialchars($sub); ?>" onchange="filterProducts()">
                                <span><?php echo htmlspecialchars($sub); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- Price -->
                    <div class="filter-group">
                        <div class="filter-header" onclick="toggleFilterGroup(this)">
                            <span>Price Range</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="filter-content show">
                            <input type="range" id="priceRange" min="5000" max="250000" step="5000" value="250000" oninput="updatePriceLabel(this.value)" onchange="filterProducts()" style="width: 100%; accent-color: #2563eb; height: 4px; margin: 12px 0;">
                            <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #6b7280;">
                                <span>₹5k</span>
                                <span id="priceValue" style="color: #2563eb;">₹250k</span>
                            </div>
                        </div>
                    </div>

                    <!-- Brand -->
                    <div class="filter-group">
                        <div class="filter-header" onclick="toggleFilterGroup(this)">
                            <span>Brand</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="filter-content">
                            <?php foreach($db_brands as $brand_row): 
                                $brand = $brand_row['brand'];
                            ?>
                            <label class="filter-label">
                                <input type="checkbox" name="brand" value="<?php echo $brand; ?>" onchange="filterProducts()">
                                <span><?php echo $brand; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- RAM -->
                    <div class="filter-group">
                        <div class="filter-header" onclick="toggleFilterGroup(this)">
                            <span>RAM</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="filter-content">
                            <?php foreach(['8GB', '16GB', '32GB', '64GB'] as $ram): ?>
                            <label class="filter-label">
                                <input type="checkbox" name="ram" value="<?php echo $ram; ?>" onchange="filterProducts()">
                                <span><?php echo $ram; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="filter-group" style="border-bottom: none;">
                        <div class="filter-header" onclick="toggleFilterGroup(this)">
                            <span>Availability</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="filter-content">
                            <label class="filter-label">
                                <input type="checkbox" name="stock" value="in" onchange="filterProducts()">
                                <span>In Stock</span>
                            </label>
                            <label class="filter-label">
                                <input type="checkbox" name="stock" value="out" onchange="filterProducts()">
                                <span>Out of Stock</span>
                            </label>
                        </div>
                    </div>

                </div>
            </aside>

            <!-- 🛒 Lean Product Grid -->
            <div style="flex-grow: 1; min-width: 0;">
                <div id="productGrid" class="lean-grid">
                    <!-- Loading State -->
                    <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                        <i class="fas fa-circle-notch fa-spin" style="font-size: 32px; color: #2563eb;"></i>
                        <p style="margin-top: 16px; color: #6b7280; font-size: 14px;">Gathering professional hardware...</p>
                    </div>
                </div>

                <!-- Minimal Pagination -->
                <div id="paginationContainer" style="margin-top: 48px; display: flex; justify-content: center; gap: 8px;"></div>
            </div>

        </div>
    </div>
</main>

<script>
    let currentPage = 1;

    function updatePriceLabel(val) {
        document.getElementById('priceValue').innerText = '₹' + (val/1000).toFixed(0) + 'k';
    }

    function toggleFilterGroup(header) {
        const content = header.nextElementSibling;
        const icon = header.querySelector('i');
        content.classList.toggle('show');
        icon.style.transform = content.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function getSelectedCheckboxes(name) {
        return Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map(cb => cb.value);
    }

    function toggleMobileSidebar() {
        document.getElementById('filterSidebar').classList.toggle('mobile-open');
    }

    function filterProducts(page = 1) {
        document.getElementById('filterSidebar').classList.remove('mobile-open');
        currentPage = page;
        const grid = document.getElementById('productGrid');
        const pagination = document.getElementById('paginationContainer');
        const countDisplay = document.getElementById('productCountDisplay');

        grid.style.opacity = '0.5';

        const filters = {
            categories: getSelectedCheckboxes('category'),
            brands: getSelectedCheckboxes('brand'),
            ram: getSelectedCheckboxes('ram'),
            stock: getSelectedCheckboxes('stock'),
            price: document.getElementById('priceRange').value,
            sort: document.getElementById('sortOrder').value,
            page: page
        };

        fetch('fetch_products.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(filters)
        })
        .then(response => response.json())
        .then(data => {
            grid.innerHTML = data.html;
            pagination.innerHTML = data.pagination;
            countDisplay.innerText = data.total > 0 ? `Showing ${data.from}-${data.to} of ${data.total} Products` : 'No products found';
            grid.style.opacity = '1';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(error => {
            console.error('Error:', error);
            grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #ef4444; padding: 40px;">Failed to load products. Please refresh.</p>';
        });
    }

    function clearAllFilters() {
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.getElementById('priceRange').value = 250000;
        updatePriceLabel(250000);
        document.getElementById('sortOrder').value = 'latest';
        filterProducts();
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Handle URL parameters for categories and groups
        const urlParams = new URLSearchParams(window.location.search);
        
        // 1. Group-based Filtering (e.g., ?group=pcs)
        const groupParam = urlParams.get('group');
        if (groupParam === 'pcs') {
            // Flexible PC categories list - add any new subcategories here
            const pcCategories = ['Personal Computers', 'Workstations', 'Gaming PCs', 'Office PCs', 'All-in-One PCs'];
            
            document.querySelectorAll('input[name="category"]').forEach(cb => {
                if (pcCategories.map(c => c.toLowerCase()).includes(cb.value.toLowerCase())) {
                    cb.checked = true;
                    const content = cb.closest('.filter-content');
                    if (content) content.classList.add('show');
                }
            });
        } else if (groupParam === 'laptops') {
            const laptopCategories = ['Gaming Laptops', 'Business Laptops', 'Student Laptops', 'Creator Laptops'];
            
            document.querySelectorAll('input[name="category"]').forEach(cb => {
                if (laptopCategories.map(c => c.toLowerCase()).includes(cb.value.toLowerCase())) {
                    cb.checked = true;
                    const content = cb.closest('.filter-content');
                    if (content) content.classList.add('show');
                }
            });
        }

        // 3. Main Category Filtering (e.g., ?main_category=Components)
        const mainCatParam = urlParams.get('main_category');
        if (mainCatParam) {
            document.querySelectorAll('.filter-group').forEach(group => {
                const header = group.querySelector('.filter-header span');
                if (header && header.innerText.trim().toLowerCase() === mainCatParam.toLowerCase()) {
                    group.querySelectorAll('input[name="category"]').forEach(cb => {
                        cb.checked = true;
                    });
                    const content = group.querySelector('.filter-content');
                    if (content) content.classList.add('show');
                }
            });
        }

        // 2. Single Category Filtering (e.g., ?category=Laptops)
        const catParam = urlParams.get('category');
        if (catParam) {
            document.querySelectorAll('input[name="category"]').forEach(cb => {
                if (cb.value.toLowerCase() === catParam.toLowerCase()) {
                    cb.checked = true;
                    const content = cb.closest('.filter-content');
                    if (content) content.classList.add('show');
                }
            });
        }

        filterProducts();
    });
</script>

<style>
    /* ═══════════════════════════════════════════════════
       PRODUCTS PAGE — Minimal Editorial Theme
       ═══════════════════════════════════════════════════ */

    /* ── Filter Sidebar ────────────────────────────────── */
    .filter-sidebar {
        width: 240px;
        flex-shrink: 0;
        position: sticky;
        top: 108px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
        z-index: 10;
    }

    /* Sidebar scrollbar */
    .filter-sidebar::-webkit-scrollbar { width: 3px; }
    .filter-sidebar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

    /* Inner card */
    .filter-sidebar > div {
        background: var(--bg-white) !important;
        border-radius: var(--radius-lg) !important;
        border: 1px solid var(--border) !important;
        padding: 20px !important;
        box-shadow: none !important;
    }

    .filter-group {
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
        margin-bottom: 12px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-dark);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 4px 0;
        transition: color 0.2s;
    }

    .filter-header:hover { color: var(--accent); }

    .filter-header i {
        font-size: 9px;
        transition: transform 0.3s ease;
        color: var(--text-muted);
    }

    /* Clear All button */
    .filter-sidebar button[onclick="clearAllFilters()"] {
        font-size: 11px;
        font-weight: 700;
        color: var(--accent) !important;
        background: none;
        border: none;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s ease;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-content.show {
        max-height: 500px;
        padding-top: 10px;
    }

    .filter-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: var(--text-mid);
        cursor: pointer;
        transition: color 0.2s;
    }

    .filter-label:hover { color: var(--text-dark); }

    .filter-label input[type="checkbox"] {
        width: 15px;
        height: 15px;
        border-radius: 4px;
        accent-color: var(--accent);
        cursor: pointer;
    }

    /* Price range accent */
    input[type="range"] { accent-color: var(--text-dark) !important; }
    #priceValue { color: var(--accent) !important; font-weight: 700; }

    /* ── Mobile filter button ───────────────────────────── */
    #mobileFilterBtn {
        background: var(--bg-white) !important;
        border: 1px solid var(--border) !important;
        color: var(--text-dark) !important;
        border-radius: var(--radius-md) !important;
        font-family: 'Manrope', sans-serif !important;
        font-weight: 600 !important;
    }

    /* ── Sort dropdown ──────────────────────────────────── */
    #sortOrder {
        background: var(--bg-white) !important;
        border: 1px solid var(--border) !important;
        border-radius: var(--radius-md) !important;
        color: var(--text-dark) !important;
        font-family: 'Manrope', sans-serif !important;
        font-size: 13px !important;
        font-weight: 500;
        transition: border-color 0.2s;
    }

    #sortOrder:focus { border-color: var(--text-dark) !important; }

    /* ── Breadcrumb ─────────────────────────────────────── */
    .breadcrumb-list {
        display: flex;
        gap: 6px;
        list-style: none;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 16px;
    }
    .breadcrumb-list a { color: var(--text-muted); text-decoration: none; }
    .breadcrumb-list a:hover { color: var(--text-dark); }
    .breadcrumb-list .current { color: var(--text-dark); font-weight: 600; }

    /* ── Product Grid ───────────────────────────────────── */
    .lean-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 16px;
        width: 100%;
    }

    /* ── Product Card ───────────────────────────────────── */
    .product-card-premium {
        background: var(--bg-white);
        border-radius: var(--radius-lg);
        padding: 16px;
        border: 1px solid var(--border);
        transition: border-color 0.25s var(--ease), box-shadow 0.25s var(--ease), transform 0.25s var(--ease);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
        box-shadow: none;
    }

    .product-card-premium:hover {
        border-color: #333;
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
    }

    /* ── Product Image ──────────────────────────────────── */
    .premium-img-container {
        height: 170px;
        width: 100%;
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        margin-bottom: 0;
    }

    .premium-img-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.4s var(--ease);
    }

    .product-card-premium:hover .premium-img-container img {
        transform: scale(1.06);
    }

    /* ── Product Tag / Label ────────────────────────────── */
    .premium-tag {
        background: var(--bg);
        color: var(--text-muted);
        padding: 4px 10px;
        border-radius: var(--radius-sm);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid var(--border);
    }

    /* ── Product Action Button ──────────────────────────── */
    .premium-btn {
        background: var(--text-dark);
        color: white;
        height: 36px;
        border-radius: var(--radius-md);
        padding: 0 16px;
        font-size: 11px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: background 0.2s var(--ease), transform 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-family: 'Manrope', sans-serif;
    }

    .premium-btn:hover {
        background: var(--accent);
        transform: translateY(-1px);
        color: #fff;
    }

    .lean-btn-primary[disabled] {
        background: var(--border);
        color: var(--text-muted);
        cursor: not-allowed;
    }

    /* ── Pagination ─────────────────────────────────────── */
    .lean-page-btn {
        padding: 8px 14px;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        background: var(--bg-white);
        color: var(--text-dark);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s var(--ease);
        font-family: 'Manrope', sans-serif;
    }

    .lean-page-btn:hover { border-color: var(--text-dark); }

    .lean-page-btn.active {
        background: var(--text-dark);
        color: white;
        border-color: var(--text-dark);
    }

    /* ── Sidebar mini scrollbar ─────────────────────────── */
    .scroll-mini::-webkit-scrollbar { width: 3px; }
    .scroll-mini::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

    /* ── Loading spinner ────────────────────────────────── */
    .fa-circle-notch { color: var(--accent) !important; }

    /* ── Responsive ─────────────────────────────────────── */
    @media (max-width: 992px) {
        .filter-sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            height: 100vh;
            width: 280px;
            background: var(--bg-white);
            padding: 24px;
            transition: left 0.35s var(--ease);
            box-shadow: var(--shadow-lg);
            overflow-y: auto;
            max-height: 100vh;
            z-index: 1100;
        }
        .filter-sidebar.mobile-open { left: 0; }
        #mobileFilterBtn { display: flex !important; }
    }

    @media (max-width: 640px) {
        .lean-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 420px) {
        .lean-grid { grid-template-columns: 1fr; }
    }
</style>

<?php include 'includes/footer.php'; ?>
