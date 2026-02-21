<?php include 'includes/header.php'; ?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 class="animate-fade-up">Custom PC Builder</h1>
            <p class="animate-fade-up">Build your performance rig with real-time compatibility checks.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 350px; gap: 40px; max-width: 1200px; margin: 0 auto;">
            <!-- Builder Steps -->
            <div>
                <!-- Category Tabs -->
                <div style="display: flex; gap: 10px; margin-bottom: 30px; overflow-x: auto; padding-bottom: 15px;">
                    <button class="btn btn-primary" style="padding: 10px 20px; font-size: 14px;">CPU</button>
                    <button class="btn glass-card" style="padding: 10px 20px; font-size: 14px;">Motherboard</button>
                    <button class="btn glass-card" style="padding: 10px 20px; font-size: 14px;">GPU</button>
                    <button class="btn glass-card" style="padding: 10px 20px; font-size: 14px;">RAM</button>
                    <button class="btn glass-card" style="padding: 10px 20px; font-size: 14px;">Cabinet</button>
                </div>

                <!-- Component Selection List -->
                <div class="grid" style="grid-template-columns: 1fr;">
                    <?php
                    // Dynamic fetch for components (CPU category for now)
                    $stmt = $pdo->query("SELECT * FROM products WHERE category = 'Components' LIMIT 5");
                    $components = $stmt->fetchAll();
                    foreach($components as $c):
                    ?>
                    <div class="glass-card" style="display: flex; align-items: center; gap: 20px; padding: 20px; margin-bottom: 15px;">
                        <div style="width: 80px; height: 80px; background: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="<?php echo $c['main_image']; ?>" alt="<?php echo $c['name']; ?>" style="max-width: 80%; max-height: 80%; object-fit: contain;">
                        </div>
                        <div style="flex: 1;">
                            <h4 style="margin-bottom: 4px;"><?php echo $c['name']; ?></h4>
                            <p style="font-size: 12px; color: var(--text-grey);"><?php echo $c['brand']; ?></p>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; margin-bottom: 10px;">₹<?php echo number_format($c['offer_price']); ?></div>
                            <button class="btn btn-primary" style="padding: 6px 16px; font-size: 13px;">Add</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Build Summary Panel -->
            <aside>
                <div class="glass-card" style="position: sticky; top: 100px; padding: 30px;">
                    <h3 style="margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px;">Your Build</h3>
                    
                    <div style="margin-bottom: 30px;">
                        <ul style="list-style: none; display: flex; flex-direction: column; gap: 15px;">
                            <li style="display: flex; justify-content: space-between; font-size: 14px;">
                                <span style="color: var(--text-grey);">CPU</span>
                                <span style="font-weight: 500;">Select...</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; font-size: 14px;">
                                <span style="color: var(--text-grey);">Motherboard</span>
                                <span style="font-weight: 500;">Select...</span>
                            </li>
                            <li style="display: flex; justify-content: space-between; font-size: 14px;">
                                <span style="color: var(--text-grey);">GPU</span>
                                <span style="font-weight: 500;">Select...</span>
                            </li>
                        </ul>
                    </div>

                    <div style="background: rgba(0, 113, 227, 0.05); padding: 20px; border-radius: 12px; margin-bottom: 30px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span style="font-size: 14px;">Total Price</span>
                            <span style="font-weight: 700; font-size: 24px; color: var(--primary);">₹0</span>
                        </div>
                        <div style="font-size: 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-exclamation-circle" style="color: var(--text-grey);"></i>
                            <span>Waiting for selection...</span>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button class="btn btn-primary" style="width: 100%;" disabled>Add to Cart</button>
                        <button class="btn btn-outline" style="width: 100%;"><i class="fas fa-file-pdf" style="margin-right: 8px;"></i> Download PDF</button>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
