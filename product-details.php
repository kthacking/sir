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

<main style="padding-top: 80px;">
    <section class="section-padding" style="padding-top: 40px; padding-bottom: 40px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; max-width: 1200px; margin: 0 auto; align-items: start;">
            
            <!-- Product Image Section -->
            <div class="animate-fade-up">
                <div class="glass-card-pro" style="padding: 24px; background: rgba(255, 255, 255, 0.4); border-radius: 32px; backdrop-filter: blur(40px); border: 1px solid rgba(255,255,255,0.4); box-shadow: 0 20px 50px rgba(0,0,0,0.05);">
                    <div style="height: 380px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; overflow: hidden; border-radius: 20px; background: rgba(255,255,255,0.3);">
                        <img id="mainImage" src="<?php echo htmlspecialchars($gallery[0]); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             style="max-width: 85%; max-height: 85%; object-fit: contain; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1)); transition: all 0.5s ease;">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <?php if (count($gallery) > 1): ?>
                    <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                        <?php foreach($gallery as $i => $img): ?>
                        <div onclick="updateMainImage('<?php echo htmlspecialchars($img); ?>', this)" 
                             class="thumb-box <?php echo $i === 0 ? 'active' : ''; ?>"
                             style="width: 54px; height: 54px; border: 2px solid rgba(0,0,0,0.05); border-radius: 12px; cursor: pointer; overflow: hidden; transition: all 0.3s; background: white;">
                            <img src="<?php echo htmlspecialchars($img); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="animate-fade-up" style="transition-delay: 0.1s;">
                <div style="margin-bottom: 20px;">
                    <span style="font-size: 11px; color: var(--text-grey); font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.7;">
                        <?php echo htmlspecialchars($product['brand']); ?> | <?php echo htmlspecialchars($product['condition']); ?>
                    </span>
                    <h1 style="font-size: 32px; margin: 8px 0 6px 0; letter-spacing: -0.03em; line-height: 1.1; font-weight: 800; color: #1d1d1f;">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h1>

                    <!-- Rating Highlights -->
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <div style="color: #ffb400; font-size: 14px;">
                            <?php 
                            for($i=1; $i<=5; $i++) {
                                if($i <= floor($avg_rating)) echo '<i class="fas fa-star"></i>';
                                elseif($i - $avg_rating < 1) echo '<i class="fas fa-star-half-alt"></i>';
                                else echo '<i class="far fa-star"></i>';
                            }
                            ?>
                        </div>
                        <span style="font-weight: 700; font-size: 14px;"><?php echo $avg_rating; ?></span>
                        <span style="color: var(--text-grey); font-size: 12px; margin-left: -5px;">(<?php echo $total_reviews; ?>)</span>
                    </div>
                    
                    <?php if($is_out_of_stock): ?>
                        <span style="display: inline-flex; align-items: center; background: #fff1f0; color: #ff4d4f; padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; border: 1px solid #ffccc7;">OUT OF STOCK</span>
                    <?php else: ?>
                        <span style="display: inline-flex; align-items: center; background: #f6ffed; color: #52c41a; padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; border: 1px solid #b7eb8f;">IN STOCK (<?php echo $product['stock']; ?> UNITS)</span>
                    <?php endif; ?>

                    <div style="margin-top: 20px;">
                        <span style="font-size: 28px; font-weight: 800; color: var(--primary);">₹<?php echo number_format($product['offer_price']); ?></span>
                        <?php if($product['price'] > $product['offer_price']): ?>
                            <span style="font-size: 16px; text-decoration: line-through; color: #86868b; margin-left: 12px;">₹<?php echo number_format($product['price']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="margin-bottom: 30px; color: #424245; line-height: 1.5; font-size: 15px; max-width: 500px;">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <!-- Specifications Requirement -->
                <div style="margin-bottom: 30px;">
                    <h4 style="margin-bottom: 12px; font-size: 16px; font-weight: 700;">Key Specs</h4>
                    <ul style="list-style: none; display: grid; grid-template-columns: 1fr; gap: 8px;">
                        <?php 
                        $specs = explode("\n", $product['specifications']);
                        $count = 0;
                        foreach($specs as $spec):
                            if(trim($spec) != "" && $count < 4):
                                $count++;
                        ?>
                            <li style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #1d1d1f; background: rgba(0,0,0,0.03); padding: 8px 16px; border-radius: 10px; border: 1px solid rgba(0,0,0,0.02);">
                                <i class="fas fa-check" style="color: #34c759; font-size: 10px;"></i>
                                <span><?php echo htmlspecialchars(trim($spec)); ?></span>
                            </li>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </ul>
                </div>

                <div style="display: flex; gap: 15px;">
                    <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn btn-primary" style="flex: 1; padding: 14px 24px; font-size: 15px;" <?php echo $is_out_of_stock ? 'disabled' : ''; ?>>
                        <i class="fas fa-shopping-bag" style="margin-right: 8px; font-size: 14px;"></i> Buy Now
                    </a>
                    <button class="btn btn-outline" style="padding: 14px 20px;"><i class="far fa-heart" style="font-size: 16px;"></i></button>
                </div>
            </div>

        </div>
    </section>
</main>

<!-- Reviews Section -->
    <section class="section-padding" style="background: #fafafa; margin-top: 40px; border-top: 1px solid #f1f1f4; padding-top: 60px;">
        <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
            <div style="display: grid; grid-template-columns: 350px 1fr; gap: 60px;">
                
                <!-- Ratings Summary & Form -->
                <div>
                    <h3 style="margin-bottom: 20px; font-size: 20px; font-weight: 800;">Community Reviews</h3>
                    <div class="glass-card-pro" style="background: white; padding: 30px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f1f1f4;">
                        <div style="text-align: center; margin-bottom: 30px;">
                            <div style="font-size: 48px; font-weight: 800; line-height: 1; color: #1d1d1f;"><?php echo $avg_rating; ?></div>
                            <div style="color: #ffb400; font-size: 18px; margin: 10px 0;">
                                <?php 
                                for($i=1; $i<=5; $i++) {
                                    echo ($i <= floor($avg_rating)) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                }
                                ?>
                            </div>
                            <div style="color: #86868b; font-weight: 500; font-size: 13px;">Based on <?php echo $total_reviews; ?> reviews</div>
                        </div>

                        <!-- Review Form -->
                        <div style="border-top: 1px solid #f1f1f4; padding-top: 25px;">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <h5 style="margin-bottom: 15px; font-size: 15px;">Write a review</h5>
                                <form action="submit-review.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <div style="margin-bottom: 12px;">
                                        <div class="star-rating" style="display: flex; gap: 6px; font-size: 18px; color: #e5e5e5;">
                                            <input type="radio" name="rating" value="5" id="s5" required hidden><label for="s5" class="fas fa-star"></label>
                                            <input type="radio" name="rating" value="4" id="s4" hidden><label for="s4" class="fas fa-star"></label>
                                            <input type="radio" name="rating" value="3" id="s3" hidden><label for="s3" class="fas fa-star"></label>
                                            <input type="radio" name="rating" value="2" id="s2" hidden><label for="s2" class="fas fa-star"></label>
                                            <input type="radio" name="rating" value="1" id="s1" hidden><label for="s1" class="fas fa-star"></label>
                                        </div>
                                    </div>
                                    <textarea name="review_text" placeholder="Your experience..." required style="width: 100%; border-radius: 12px; border: 1px solid #eee; padding: 12px; font-size: 13px; margin-bottom: 12px; min-height: 80px; outline: none; background: #f9f9f9;"></textarea>
                                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 13px;">Submit Review</button>
                                </form>
                            <?php else: ?>
                                <div style="text-align: center; padding: 15px; background: #f5f5f7; border-radius: 12px; border: 1px dashed #d1d1d6;">
                                    <p style="font-size: 12px; color: #86868b; margin: 0;">Please <a href="login.php" style="color: var(--primary); font-weight: 700; text-decoration: none;">login</a> to review.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reviews List -->
                <div style="padding-top: 10px;">
                    <?php if (empty($reviews)): ?>
                        <div style="text-align: center; padding: 60px 0;">
                            <i class="far fa-comment-dots" style="font-size: 32px; color: #e5e5e5; margin-bottom: 15px;"></i>
                            <p style="color: #86868b; font-size: 14px;">No reviews yet. Share yours!</p>
                        </div>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <?php foreach($reviews as $rev): ?>
                            <div class="animate-fade-up" style="background: white; padding: 20px; border-radius: 20px; border: 1px solid #f1f1f4; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 32px; height: 32px; background: #f5f5f7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: var(--primary);">
                                            <?php echo strtoupper(substr($rev['reviewer_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <span style="font-weight: 700; font-size: 14px; color: #1d1d1f;"><?php echo htmlspecialchars($rev['reviewer_name']); ?></span>
                                            <?php if ($rev['is_admin_review']): ?>
                                                <i class="fas fa-check-circle" style="color: var(--primary); font-size: 10px; margin-left: 4px;" title="Verified Store Rep"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div style="color: #ffb400; font-size: 10px;">
                                        <?php 
                                        for($i=1; $i<=5; $i++) {
                                            echo ($i <= $rev['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p style="color: #424245; line-height: 1.5; font-size: 13px; margin-bottom: 8px;"><?php echo nl2br(htmlspecialchars($rev['review_text'])); ?></p>
                                <div style="font-size: 11px; color: #86868b; font-weight: 500;">
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
