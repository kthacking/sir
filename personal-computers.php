<?php 
include_once 'includes/config.php';
include 'includes/header.php'; 

$category_name = "Personal Computers";
$category_filter = ['Workstations', 'Desktops', 'All-in-One'];
?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 class="animate-fade-up"><?php echo $category_name; ?></h1>
            <p class="animate-fade-up">High-performance workstations and daily desktops.</p>
        </div>

        <div class="container-wide">
            <div class="grid-main">
                <?php
                // Fetch products for this category
                $stmt = $pdo->prepare("SELECT * FROM products WHERE category IN (?, ?, ?) ORDER BY id DESC");
                $stmt->execute($category_filter);
                $products = $stmt->fetchAll();

                if (count($products) > 0):
                    foreach ($products as $p):
                        $is_out_of_stock = ($p['stock'] <= 0);
                ?>
                    <div class="glass-card fade-in-up" style="display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="height: 240px; background: #fff; border-radius: var(--border-radius-md); margin-bottom: 24px; text-align: center; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; border: 1px solid #f0f0f0;">
                                <?php 
                                $img_src = $p['main_image'];
                                if (empty($img_src)) {
                                    $img_src = 'https://images.unsplash.com/photo-1593642702749-b7d2a804fbcf';
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($img_src); ?>" 
                                     alt="<?php echo htmlspecialchars($p['name']); ?>" 
                                     style="width: 85%; height: 85%; object-fit: contain; transition: transform 0.5s ease;">
                                
                                <?php if ($is_out_of_stock): ?>
                                    <div style="position: absolute; top: 16px; right: 16px; background: #ff3b30; color: white; padding: 6px 14px; border-radius: 40px; font-size: 10px; font-weight: 800; letter-spacing: 1px;">SOLD OUT</div>
                                <?php endif; ?>
                            </div>
                            
                            <div style="margin-bottom: 20px;">
                                <div class="badge-pro" style="margin-bottom: 12px; font-size: 10px; padding: 4px 12px;"><?php echo htmlspecialchars($p['brand']); ?> • <?php echo htmlspecialchars($p['condition']); ?></div>
                                <h3 style="margin-bottom: 12px; font-size: 20px; line-height: 1.3; height: 52px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    <?php echo htmlspecialchars($p['name']); ?>
                                </h3>
                                <p style="font-size: 14px; color: var(--text-grey); line-height: 1.6; height: 45px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 0;">
                                    <?php echo htmlspecialchars($p['description']); ?>
                                </p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                            <div>
                                <div style="font-weight: 700; font-size: 22px; color: var(--text-dark);">₹<?php echo number_format($p['offer_price']); ?></div>
                                <?php if ($p['price'] > $p['offer_price']): ?>
                                    <div style="font-size: 13px; text-decoration: line-through; color: var(--text-grey); opacity: 0.6;">₹<?php echo number_format($p['price']); ?></div>
                                <?php endif; ?>
                            </div>
                            <a href="product-details.php?id=<?php echo $p['id']; ?>" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px;">View</a>
                        </div>
                    </div>
                <?php 
                    endforeach;
            else:
            ?>
                <!-- Requirement: No products found message -->
                <div style="grid-column: 1/-1; text-align: center; padding: 100px 20px;">
                    <i class="fas fa-search-minus" style="font-size: 60px; color: #eee; margin-bottom: 20px;"></i>
                    <h2>No Products Available</h2>
                    <p style="color: var(--text-grey); margin-top: 10px;">We are currently updating our inventory. Please check back later.</p>
                    <a href="index.php" class="btn btn-outline" style="margin-top: 25px;">Back to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
    .glass-card:hover { transform: translateY(-10px); }
</style>

<?php include 'includes/footer.php'; ?>
