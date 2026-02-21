<?php include 'includes/header.php'; ?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 class="animate-fade-up">Components</h1>
            <p class="animate-fade-up">Processors, GPUs, RAM, and more for your next build.</p>
        </div>

        <div class="grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM products WHERE category = 'Components' OR category_id = 4 ORDER BY id DESC");
            $products = $stmt->fetchAll();
            if(count($products) > 0):
                foreach($products as $p):
            ?>
            <div class="glass-card animate-fade-up">
                 <div style="height: 250px; background: #fff; border-radius: 12px; margin-bottom: 20px; text-align: center; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img src="<?php echo $p['main_image']; ?>" alt="<?php echo $p['name']; ?>" style="max-width: 90%; max-height: 90%; object-fit: contain;">
                </div>
                <span style="font-size: 12px; color: var(--text-grey); font-weight: 500; text-transform: uppercase;">Hardware</span>
                <h3 style="margin: 5px 0; font-size: 20px;"><?php echo $p['name']; ?></h3>
                <p style="font-size: 14px; margin-bottom: 20px; color: var(--text-grey);"><?php echo $p['brand']; ?></p>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-weight: 700; font-size: 22px;">₹<?php echo number_format($p['offer_price']); ?></span>
                    <a href="product-details.php?id=<?php echo $p['id']; ?>" class="btn btn-primary">Details</a>
                </div>
            </div>
            <?php 
                endforeach;
            else:
            ?>
                <p style="text-align: center; grid-column: 1/-1;">No components found in the database.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
