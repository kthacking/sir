<?php include 'includes/header.php'; ?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 class="animate-fade-up">Printers & Peripherals</h1>
            <p class="animate-fade-up">Complete your setup with high-quality peripherals.</p>
        </div>

        <div class="container-wide">
            <div class="grid-main">
                <?php
                $stmt = $pdo->query("SELECT * FROM products WHERE category = 'Printers & Peripherals' OR category_id = 5 ORDER BY id DESC");
                $products = $stmt->fetchAll();
                if(count($products) > 0):
                    foreach($products as $p):
                        $is_out_of_stock = ($p['stock'] <= 0);
                ?>
                <div class="glass-card fade-in-up" style="display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="height: 240px; background: #fff; border-radius: var(--border-radius-md); margin-bottom: 24px; text-align: center; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; border: 1px solid #f0f0f0;">
                            <img src="<?php echo htmlspecialchars($p['main_image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" style="width: 85%; height: 85%; object-fit: contain;">
                            <?php if ($is_out_of_stock): ?>
                                <div style="position: absolute; top: 16px; right: 16px; background: #ff3b30; color: white; padding: 6px 14px; border-radius: 40px; font-size: 10px; font-weight: 800; letter-spacing: 1px;">SOLD OUT</div>
                            <?php endif; ?>
                        </div>
                        <div style="margin-bottom: 20px;">
                            <div class="badge-pro" style="margin-bottom: 12px; font-size: 10px; padding: 4px 12px;"><?php echo htmlspecialchars($p['brand']); ?></div>
                            <h3 style="margin-bottom: 12px; font-size: 20px; line-height: 1.3; height: 52px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?php echo htmlspecialchars($p['name']); ?>
                            </h3>
                            <p style="font-size: 14px; color: var(--text-grey); line-height: 1.6; height: 45px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; margin-bottom: 0;">
                                <?php echo htmlspecialchars($p['description'] ?? ''); ?>
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
                    <p style="text-align: center; grid-column: 1/-1;">No peripherals found in the database.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
