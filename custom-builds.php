<?php include 'includes/header.php'; ?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <div style="text-align: center; margin-bottom: 60px;">
            <h1 class="animate-fade-up">Custom Builds</h1>
            <p class="animate-fade-up">Gaming PCs and Performance Rigs tailored for you.</p>
            <div style="margin-top: 20px;">
                <a href="<?php echo $base_url; ?>pc-builder.php" class="btn btn-primary">Start Custom Builder</a>
            </div>
        </div>

        <div class="grid">
            <div class="glass-card animate-fade-up">
                 <div style="height: 250px; background: #fff; border-radius: 12px; margin-bottom: 20px; text-align: center; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img src="https://images.unsplash.com/photo-1527443154391-507e9dc6c5cc" alt="Gaming" style="max-width: 90%; max-height: 90%; object-fit: contain;">
                </div>
                <span style="font-size: 12px; color: var(--text-grey); font-weight: 500;">GAMING</span>
                <h3 style="margin: 5px 0; font-size: 20px;">Titan Build v1</h3>
                <p style="font-size: 14px; margin-bottom: 20px;">AMD Ryzen 9 + RTX 4090. No compromises.</p>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <span style="font-weight: 700; font-size: 22px;">₹3,15,000</span>
                    <a href="product-details.php" class="btn btn-primary">Details</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
