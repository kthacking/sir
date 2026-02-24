<?php 
include_once 'includes/config.php';
include 'includes/header.php'; 

$product = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();

    if ($product) {
        // Fetch Additional Images
        $img_stmt = $pdo->prepare("SELECT image_url FROM product_images WHERE product_id = ?");
        $img_stmt->execute([$product['id']]);
        $extra_images = $img_stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Add main image to the list for gallery
        $gallery = array_merge([$product['main_image']], $extra_images);

        // Fetch Ratings Summary
        $rating_stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = ?");
        $rating_stmt->execute([$product['id']]);
        $stats = $rating_stmt->fetch();
        $avg_rating = round($stats['avg_rating'] ?? 0, 1);
        $total_reviews = $stats['total_reviews'];

        // Fetch Reviews
        $rev_stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
        $rev_stmt->execute([$product['id']]);
        $reviews = $rev_stmt->fetchAll();
    }
}

// Redirect or show error if product doesn't exist
if (!$product) {
?>
    <main style="padding-top: 150px; text-align: center; min-height: 80vh;">
        <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: var(--accent); margin-bottom: 20px;"></i>
        <h2 style="font-family: 'Manrope'; font-weight: 800;">Product Not Found</h2>
        <p style="color: var(--text-muted); margin-top: 10px;">The product you are looking for might have been removed or moved.</p>
        <a href="products.php" class="btn-buy" style="margin-top: 30px; display: inline-block; width: auto; padding: 12px 32px;">Continue Shopping</a>
    </main>
<?php
    include 'includes/footer.php';
    exit();
}

$is_out_of_stock = ($product['stock'] <= 0);
?>

<style>
    /* Product Details Page Styles */
    .pd-main {
        padding-top: 108px;
        background: var(--bg-white);
        min-height: 100vh;
    }

    .pd-grid-overlay {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0,0,0,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,0.02) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
        z-index: 0;
    }

    .pd-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 48px 24px 96px;
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 64px;
        align-items: start;
    }

    /* LEFT: Gallery Wrapper */
    .pd-gallery-section {
        position: sticky;
        top: 132px;
    }

    .pd-main-image-wrap {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        height: 540px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px;
        overflow: hidden;
        margin-bottom: 20px;
        transition: border-color 0.3s var(--ease);
    }

    .pd-main-image-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.6s var(--ease);
    }

    .pd-thumbs {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .pd-thumb-item {
        width: 64px;
        height: 64px;
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        cursor: pointer;
        padding: 8px;
        transition: all 0.25s var(--ease);
    }

    .pd-thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .pd-thumb-item.active {
        border-color: var(--text-dark);
        border-width: 2px;
        background: var(--bg);
    }

    /* RIGHT: Product Info */
    .pd-brand-badge {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--text-muted);
        margin-bottom: 12px;
        display: block;
    }

    .pd-title {
        font-family: 'Manrope', sans-serif;
        font-size: 42px;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1.1;
        color: var(--text-dark);
        margin-bottom: 16px;
    }

    .pd-meta-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .pd-rating-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--bg);
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 700;
    }

    .pd-rating-pill i { color: #FF9500; }

    .pd-stock-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 100px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pd-stock-in { background: #E1F5FE; color: #0288D1; }
    .pd-stock-out { background: #FFEBEE; color: #D32F2F; }

    .pd-price-section {
        margin-bottom: 32px;
    }

    .pd-price-main {
        font-family: 'Manrope', sans-serif;
        font-size: 32px;
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.02em;
    }

    .pd-price-old {
        font-size: 16px;
        text-decoration: line-through;
        color: var(--text-muted);
        margin-left: 12px;
    }

    .pd-description {
        font-size: 15px;
        color: var(--text-mid);
        line-height: 1.7;
        margin-bottom: 40px;
    }

    /* Specs Grid */
    .pd-specs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 48px;
    }

    .pd-spec-item {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pd-spec-item i {
        font-size: 16px;
        color: var(--accent);
    }

    .pd-spec-item div {
        display: flex;
        flex-direction: column;
    }

    .pd-spec-item span:first-child {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 1px;
    }

    .pd-spec-item span:last-child {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-dark);
    }

    .pd-actions {
        display: flex;
        gap: 16px;
    }

    .pd-btn-buy {
        flex: 1;
        background: var(--text-dark);
        color: white;
        text-align: center;
        padding: 18px;
        border-radius: var(--radius-md);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 14px;
        letter-spacing: 1px;
        text-decoration: none;
        transition: all 0.3s var(--ease);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .pd-btn-buy:hover { background: var(--accent); color: #fff; transform: translateY(-2px); }

    .pd-btn-fav {
        width: 56px;
        border: 1.5px solid var(--border);
        background: var(--bg-white);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .pd-btn-fav:hover { border-color: var(--text-dark); color: var(--accent); }

    /* REVIEWS */
    .pd-reviews-section {
        background: var(--bg);
        border-top: 1px solid var(--border);
        padding: 96px 24px;
    }

    .pd-reviews-container {
        max-width: 1100px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 0.8fr 1.2fr;
        gap: 80px;
    }

    .review-summary-card {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        padding: 40px;
        text-align: center;
    }

    .review-score {
        font-size: 64px;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1;
        margin-bottom: 8px;
    }

    .review-stars { color: #FF9500; font-size: 20px; margin-bottom: 12px; }

    .review-item {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 24px;
        margin-bottom: 16px;
    }

    @media (max-width: 991px) {
        .pd-container, .pd-reviews-container {
            grid-template-columns: 1fr;
            gap: 48px;
        }
        .pd-gallery-section { position: relative; top: 0; }
        .pd-main-image-wrap { height: 400px; }
        .pd-title { font-size: 32px; }
    }
</style>

<div class="pd-main">
    <div class="pd-grid-overlay"></div>
    
    <div class="pd-container">
        <!-- Gallery -->
        <div class="pd-gallery-section">
            <div class="pd-main-image-wrap">
                <img id="mainImage" src="<?php echo htmlspecialchars($gallery[0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            
            <?php if (count($gallery) > 1): ?>
            <div class="pd-thumbs">
                <?php foreach($gallery as $i => $img): ?>
                <div onclick="updateMainImage('<?php echo htmlspecialchars($img); ?>', this)" 
                     class="pd-thumb-item <?php echo $i === 0 ? 'active' : ''; ?>">
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="">
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="pd-info-section">
            <span class="pd-brand-badge"><?php echo htmlspecialchars($product['brand']); ?> • <?php echo htmlspecialchars($product['condition']); ?> Premium Build</span>
            <h1 class="pd-title"><?php echo htmlspecialchars($product['name']); ?></h1>

            <div class="pd-meta-row">
                <div class="pd-rating-pill">
                    <i class="fas fa-star"></i>
                    <span><?php echo $avg_rating; ?></span>
                    <span style="color: var(--text-muted); font-weight: 500;">(<?php echo $total_reviews; ?>)</span>
                </div>
                
                <?php if($is_out_of_stock): ?>
                    <span class="pd-stock-badge pd-stock-out">Depleted</span>
                <?php else: ?>
                    <span class="pd-stock-badge pd-stock-in"><?php echo $product['stock']; ?> Units Available</span>
                <?php endif; ?>
            </div>

            <div class="pd-price-section">
                <span class="pd-price-main">₹<?php echo number_format($product['offer_price']); ?></span>
                <?php if($product['price'] > $product['offer_price']): ?>
                    <span class="pd-price-old">₹<?php echo number_format($product['price']); ?></span>
                <?php endif; ?>
            </div>

            <div class="pd-description">
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            </div>

            <div class="pd-specs-grid">
                <?php 
                $specs = explode("\n", $product['specifications']);
                $spec_icons = ['fa-microchip', 'fa-memory', 'fa-hdd', 'fa-video'];
                $count = 0;
                foreach($specs as $spec):
                    if(trim($spec) != "" && $count < 4):
                ?>
                    <div class="pd-spec-item">
                        <i class="fas <?php echo $spec_icons[$count % 4]; ?>"></i>
                        <div>
                            <span>Hardware</span>
                            <span><?php echo htmlspecialchars(trim($spec)); ?></span>
                        </div>
                    </div>
                <?php 
                    $count++;
                    endif;
                endforeach; 
                ?>
            </div>

            <div class="pd-actions">
                <?php if(!$is_out_of_stock): ?>
                    <a href="cart.php?add=<?php echo $product['id']; ?>" class="pd-btn-buy">
                        <i class="fas fa-shopping-bag"></i> Add to Collection
                    </a>
                <?php else: ?>
                    <button class="pd-btn-buy" style="background: var(--text-muted); opacity: 0.5; cursor: not-allowed;" disabled>
                        Out of Stock
                    </button>
                <?php endif; ?>
                <button class="pd-btn-fav"><i class="far fa-heart"></i></button>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="pd-reviews-section">
        <div class="pd-reviews-container">
            <div>
                <div class="review-summary-card">
                    <h3 style="font-family: 'Manrope'; font-weight: 800; font-size: 20px; margin-bottom: 24px;">Quality Index</h3>
                    <div class="review-score"><?php echo $avg_rating; ?></div>
                    <div class="review-stars">
                        <?php 
                        for($i=1; $i<=5; $i++) {
                            echo ($i <= floor($avg_rating)) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>
                    <p style="font-size: 13px; color: var(--text-muted);">From <?php echo $total_reviews; ?> unique reviews</p>

                    <div style="margin-top: 32px; border-top: 1px solid var(--border); padding-top: 32px;">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <h5 style="margin-bottom: 16px; font-weight: 700; text-align: left;">Share your expert opinion</h5>
                            <form action="submit-review.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <div style="display: flex; gap: 8px; font-size: 18px; color: #E0E0E0; margin-bottom: 16px;">
                                    <?php for($k=5; $k>=1; $k--): ?>
                                        <input type="radio" name="rating" value="<?php echo $k; ?>" id="rate<?php echo $k; ?>" <?php echo $k==5?'checked':''; ?> hidden>
                                        <label for="rate<?php echo $k; ?>" class="fas fa-star" style="cursor: pointer;"></label>
                                    <?php endfor; ?>
                                </div>
                                <textarea name="review_text" placeholder="Detailed engineering feedback..." required style="width: 100%; border-radius: 12px; border: 1px solid var(--border); padding: 16px; font-size: 13px; margin-bottom: 16px; min-height: 100px; font-family: inherit; resize: none;"></textarea>
                                <button type="submit" class="btn-buy" style="width: 100%; padding: 12px; font-size: 13px;">Publish Review</button>
                            </form>
                        <?php else: ?>
                            <div style="background: var(--bg); padding: 20px; border-radius: 12px; border: 1px dashed var(--border);">
                                <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Authenticator required to write reviews. <a href="login.php" style="color: var(--accent); font-weight: 700; text-decoration: none;">Log In</a></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div>
                <h3 style="font-family: 'Manrope'; font-weight: 800; font-size: 24px; margin-bottom: 32px;">Client Feedback</h3>
                <?php if (empty($reviews)): ?>
                    <div style="text-align: center; padding: 64px 0; border: 1px dashed var(--border); border-radius: 20px;">
                        <i class="far fa-comment-dots" style="font-size: 32px; color: var(--text-muted); margin-bottom: 16px;"></i>
                        <p style="color: var(--text-muted); font-size: 14px;">No feedback logs recorded for this build.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($reviews as $rev): ?>
                    <div class="review-item">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; background: var(--bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Manrope'; font-weight: 700; font-size: 12px; color: var(--text-dark);">
                                    <?php echo strtoupper(substr($rev['reviewer_name'], 0, 1)); ?>
                                </div>
                                <span style="font-weight: 700; font-size: 14px;"><?php echo htmlspecialchars($rev['reviewer_name']); ?></span>
                            </div>
                            <div style="color: #FF9500; font-size: 10px;">
                                <?php for($i=1; $i<=5; $i++) echo ($i <= $rev['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; ?>
                            </div>
                        </div>
                        <p style="color: var(--text-mid); font-size: 14px; line-height: 1.6; margin-bottom: 12px;"><?php echo nl2br(htmlspecialchars($rev['review_text'])); ?></p>
                        <div style="font-size: 11px; color: var(--text-muted);"><?php echo date('F d, Y', strtotime($rev['created_at'])); ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function updateMainImage(src, thumb) {
        const main = document.getElementById('mainImage');
        main.style.opacity = '0';
        main.style.transform = 'scale(0.98)';
        
        setTimeout(() => {
            main.src = src;
            main.style.opacity = '1';
            main.style.transform = 'scale(1)';
        }, 300);

        document.querySelectorAll('.pd-thumb-item').forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
    }
</script>

<?php include 'includes/footer.php'; ?>
