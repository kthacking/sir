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
        <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: var(--primary); margin-bottom: 20px;"></i>
        <h2>Product Not Found</h2>
        <p style="color: var(--text-grey); margin-top: 10px;">The product you are looking for might have been removed or moved.</p>
        <a href="index.php" class="btn btn-primary" style="margin-top: 30px;">Continue Shopping</a>
    </main>
<?php
    include 'includes/footer.php';
    exit();
}

$is_out_of_stock = ($product['stock'] <= 0);
?>

<main style="padding-top: 120px;">
    <section class="section-padding">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 80px; max-width: 1200px; margin: 0 auto; align-items: start;">
            
            <!-- Product Image Section -->
            <div class="animate-fade-up">
                <div class="glass-card" style="padding: 40px; background: #fff; text-align: center; border-radius: 24px;">
                    <div style="height: 450px; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; overflow: hidden;">
                        <img id="mainImage" src="<?php echo htmlspecialchars($gallery[0]); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             style="max-width: 100%; max-height: 100%; object-fit: contain; transition: all 0.5s ease;">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <?php if (count($gallery) > 1): ?>
                    <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                        <?php foreach($gallery as $i => $img): ?>
                        <div onclick="updateMainImage('<?php echo htmlspecialchars($img); ?>', this)" 
                             class="thumb-box <?php echo $i === 0 ? 'active' : ''; ?>"
                             style="width: 70px; height: 70px; border: 2px solid #eee; border-radius: 12px; cursor: pointer; overflow: hidden; transition: all 0.3s;">
                            <img src="<?php echo htmlspecialchars($img); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="animate-fade-up" style="transition-delay: 0.2s;">
                <div style="margin-bottom: 30px;">
                    <span style="font-size: 14px; color: var(--text-grey); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                        <?php echo htmlspecialchars($product['brand']); ?> | <?php echo htmlspecialchars($product['condition']); ?>
                    </span>
                    <h1 style="font-size: 42px; margin: 15px 0 10px 0; letter-spacing: -1px; line-height: 1.1;">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h1>

                    <!-- Rating Highlights -->
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <div style="color: #ffb400; font-size: 18px;">
                            <?php 
                            for($i=1; $i<=5; $i++) {
                                if($i <= floor($avg_rating)) echo '<i class="fas fa-star"></i>';
                                elseif($i - $avg_rating < 1) echo '<i class="fas fa-star-half-alt"></i>';
                                else echo '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span style="font-weight: 700; font-size: 17px;"><?php echo $avg_rating; ?></span>
                        <span style="color: var(--text-grey); font-size: 14px;">(<?php echo $total_reviews; ?> reviews)</span>
                    </div>
                    
                    <?php if($is_out_of_stock): ?>
                        <span style="display: inline-block; background: #ff3b30; color: white; padding: 6px 15px; border-radius: 8px; font-size: 14px; font-weight: 700; margin-bottom: 20px;">OUT OF STOCK</span>
                    <?php else: ?>
                        <span style="display: inline-block; background: #34c759; color: white; padding: 6px 15px; border-radius: 8px; font-size: 14px; font-weight: 700; margin-bottom: 20px;">IN STOCK (<?php echo $product['stock']; ?> UNITS)</span>
                    <?php endif; ?>

                    <div style="margin-top: 30px;">
                        <span style="font-size: 32px; font-weight: 700;">₹<?php echo number_format($product['offer_price']); ?></span>
                        <?php if($product['price'] > $product['offer_price']): ?>
                            <span style="font-size: 18px; text-decoration: line-through; color: var(--text-grey); margin-left: 15px;">₹<?php echo number_format($product['price']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="margin-bottom: 40px; color: #444; line-height: 1.6; font-size: 17px;">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <!-- Specifications Requirement -->
                <div style="margin-bottom: 40px;">
                    <h3 style="margin-bottom: 20px;">Specifications</h3>
                    <ul style="list-style: none; display: grid; grid-template-columns: 1fr; gap: 12px;">
                        <?php 
                        $specs = explode("\n", $product['specifications']);
                        foreach($specs as $spec):
                            if(trim($spec) != ""):
                        ?>
                            <li style="display: flex; align-items: center; gap: 12px; font-size: 15px; background: rgba(0,0,0,0.03); padding: 12px 20px; border-radius: 12px;">
                                <i class="fas fa-check-circle" style="color: var(--primary);"></i>
                                <span><?php echo htmlspecialchars(trim($spec)); ?></span>
                            </li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>

                <div style="display: flex; gap: 20px;">
                    <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn btn-primary" style="flex: 1; padding: 18px;" <?php echo $is_out_of_stock ? 'disabled' : ''; ?>>
                        <i class="fas fa-shopping-cart" style="margin-right: 10px;"></i> Add to Cart
                    </a>
                    <button class="btn btn-outline" style="padding: 18px 25px;"><i class="far fa-heart"></i></button>
                </div>
            </div>

        </div>
    </section>
</main>

<!-- Reviews Section -->
    <section class="section-padding" style="background: #fff; margin-top: 60px; border-top: 1px solid #eee;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 80px;">
                
                <!-- Ratings Summary & Form -->
                <div>
                    <h2 style="margin-bottom: 30px;">Customer Reviews</h2>
                    <div style="background: #fafafa; padding: 40px; border-radius: 24px; border: 1px solid #eee;">
                        <div style="text-align: center; margin-bottom: 40px;">
                            <div style="font-size: 64px; font-weight: 800; line-height: 1;"><?php echo $avg_rating; ?></div>
                            <div style="color: #ffb400; font-size: 24px; margin: 15px 0;">
                                <?php 
                                for($i=1; $i<=5; $i++) {
                                    echo ($i <= floor($avg_rating)) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                }
                                ?>
                            </div>
                            <div style="color: var(--text-grey); font-weight: 500;">Based on <?php echo $total_reviews; ?> reviews</div>
                        </div>

                        <!-- Review Form -->
                        <div style="border-top: 1px solid #eee; padding-top: 30px;">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <h4 style="margin-bottom: 20px;">Share your thoughts</h4>
                                <form action="submit-review.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <div style="margin-bottom: 15px;">
                                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Your Rating</label>
                                        <div class="star-rating" style="display: flex; gap: 8px; font-size: 24px; color: #ccc;">
                                            <input type="radio" name="rating" value="5" id="s5" required hidden><label for="s5" class="fas fa-star" title="Perfect"></label>
                                            <input type="radio" name="rating" value="4" id="s4" hidden><label for="s4" class="fas fa-star" title="Good"></label>
                                            <input type="radio" name="rating" value="3" id="s3" hidden><label for="s3" class="fas fa-star" title="Average"></label>
                                            <input type="radio" name="rating" value="2" id="s2" hidden><label for="s2" class="fas fa-star" title="Fair"></label>
                                            <input type="radio" name="rating" value="1" id="s1" hidden><label for="s1" class="fas fa-star" title="Poor"></label>
                                        </div>
                                    </div>
                                    <textarea name="review_text" placeholder="Write your review here..." required style="width: 100%; border-radius: 12px; border: 1px solid #ddd; padding: 15px; font-size: 14px; margin-bottom: 15px; min-height: 100px; outline: none;"></textarea>
                                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px;">Post Review</button>
                                </form>
                            <?php else: ?>
                                <div style="text-align: center; padding: 20px; background: #fff; border-radius: 12px; border: 1px dashed #ccc;">
                                    <p style="font-size: 14px; color: var(--text-grey);">Please <a href="login.php" style="color: var(--primary); font-weight: 600;">login</a> to submit a review.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div>
                    <?php if (empty($reviews)): ?>
                        <div style="text-align: center; padding: 100px 0;">
                            <i class="far fa-comments" style="font-size: 40px; color: #eee; margin-bottom: 20px;"></i>
                            <p style="color: var(--text-grey);">No reviews yet. Be the first to share your experience!</p>
                        </div>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 30px;">
                            <?php foreach($reviews as $rev): ?>
                            <div class="animate-fade-up" style="border-bottom: 1px solid #eee; padding-bottom: 25px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <div>
                                        <span style="font-weight: 700; font-size: 16px;"><?php echo htmlspecialchars($rev['reviewer_name']); ?></span>
                                        <?php if ($rev['is_admin_review']): ?>
                                            <span style="background: var(--primary-light); color: var(--primary); font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 4px; margin-left: 8px; text-transform: uppercase;">Verified Admin</span>
                                        <?php endif; ?>
                                    </div>
                                    <div style="color: #ffb400; font-size: 12px;">
                                        <?php 
                                        for($i=1; $i<=5; $i++) {
                                            echo ($i <= $rev['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p style="color: #555; line-height: 1.6; font-size: 15px;"><?php echo nl2br(htmlspecialchars($rev['review_text'])); ?></p>
                                <div style="margin-top: 10px; font-size: 12px; color: var(--text-grey);">
                                    <?php echo date('d M Y', strtotime($rev['created_at'])); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

<script>
    function updateMainImage(src, thumb) {
        const main = document.getElementById('mainImage');
        main.style.opacity = '0';
        main.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            main.src = src;
            main.style.opacity = '1';
            main.style.transform = 'scale(1)';
        }, 300);

        // Update active thumb
        document.querySelectorAll('.thumb-box').forEach(t => {
            t.classList.remove('active');
            t.style.borderColor = '#eee';
        });
        thumb.classList.add('active');
        thumb.style.borderColor = 'var(--primary)';
    }
</script>

<style>
    .btn[disabled] { opacity: 0.5; pointer-events: none; background: #888; border-color: #888; }
    .thumb-box.active { border-color: var(--primary) !important; box-shadow: 0 4px 15px rgba(0, 113, 227, 0.2); }
    
    /* Star Rating Interaction */
    .star-rating label { cursor: pointer; transition: color 0.2s; }
    .star-rating label:hover, .star-rating label:hover ~ label { color: #ffb400 !important; }
    .star-rating input:checked ~ label { color: #ffb400 !important; }
</style>

<?php include 'includes/footer.php'; ?>
