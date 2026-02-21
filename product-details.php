<?php 
include_once 'includes/config.php';
include 'includes/header.php'; 

$product = null;
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();
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
                <div class="glass-card" style="padding: 40px; background: #fff; text-align: center; border-radius: 24px; position: sticky; top: 120px;">
                    <?php 
                    $img_src = $product['main_image'];
                    if (empty($img_src)) {
                        $img_src = 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef';
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($img_src); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         style="width: 100%; height: auto; max-height: 500px; object-fit: contain;">
                </div>
            </div>

            <!-- Product Info Section -->
            <div class="animate-fade-up" style="transition-delay: 0.2s;">
                <div style="margin-bottom: 30px;">
                    <span style="font-size: 14px; color: var(--text-grey); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                        <?php echo htmlspecialchars($product['brand']); ?> | <?php echo htmlspecialchars($product['condition']); ?>
                    </span>
                    <h1 style="font-size: 42px; margin: 15px 0; letter-spacing: -1px; line-height: 1.1;">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h1>
                    
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

<style>
    .btn[disabled] { opacity: 0.5; pointer-events: none; background: #888; border-color: #888; }
</style>

<?php include 'includes/footer.php'; ?>
