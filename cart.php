<?php
include_once 'includes/config.php';

// Handle Add to Cart
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    redirect('cart.php');
}

// Handle Remove from Cart
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    redirect('cart.php');
}

include 'includes/header.php';

// Fetch products in cart
$cart_products = [];
$subtotal = 0;
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    
    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        $p['qty'] = $qty;
        $p['total'] = $p['offer_price'] * $qty;
        $subtotal += $p['total'];
        $cart_products[] = $p;
    }
}
?>

<style>
    .cart-page {
        padding-top: 108px;
        background: var(--bg-white);
        min-height: 100vh;
        position: relative;
    }

    .cart-overlay {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0,0,0,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,0.02) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
    }

    .cart-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 48px 24px 96px;
        position: relative;
        z-index: 10;
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 64px;
        align-items: start;
    }

    .cart-title {
        font-family: 'Manrope', sans-serif;
        font-size: 42px;
        font-weight: 800;
        letter-spacing: -0.04em;
        margin-bottom: 48px;
        color: var(--text-dark);
    }

    .cart-item {
        display: flex;
        gap: 32px;
        padding: 32px 0;
        border-bottom: 1px solid var(--border);
        align-items: center;
        transition: transform 0.3s var(--ease);
    }

    .cart-img-wrap {
        width: 140px;
        height: 140px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        overflow: hidden;
    }

    .cart-img-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-info h3 {
        font-family: 'Manrope', sans-serif;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--text-dark);
    }

    .cart-item-info p {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 16px;
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .cart-qty-badge {
        font-size: 12px;
        font-weight: 700;
        background: var(--bg);
        padding: 4px 12px;
        border-radius: 100px;
        color: var(--text-dark);
        border: 1px solid var(--border);
    }

    .cart-remove-btn {
        font-size: 12px;
        font-weight: 700;
        color: var(--accent);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: color 0.2s;
    }

    .cart-remove-btn:hover { color: var(--text-dark); }

    .cart-item-price {
        font-family: 'Manrope', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: var(--text-dark);
        text-align: right;
    }

    /* Sidebar */
    .cart-summary {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        padding: 40px;
        position: sticky;
        top: 132px;
        box-shadow: var(--shadow-md);
    }

    .summary-title {
        font-family: 'Manrope', sans-serif;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 32px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .summary-row span:first-child { color: var(--text-muted); }
    .summary-row span:last-child { font-weight: 600; color: var(--text-dark); }

    .summary-total {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .summary-total span:first-child {
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: 18px;
    }

    .summary-total span:last-child {
        font-family: 'Manrope', sans-serif;
        font-weight: 800;
        font-size: 28px;
        color: var(--accent);
        line-height: 1;
    }

    .checkout-btn {
        width: 100%;
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
        display: block;
        margin-top: 40px;
        transition: all 0.3s var(--ease);
    }

    .checkout-btn:hover { background: var(--accent); transform: translateY(-2px); }

    .empty-cart {
        padding: 96px 0;
        text-align: center;
    }

    .empty-cart i {
        font-size: 64px;
        color: var(--bg);
        margin-bottom: 24px;
    }

    @media (max-width: 900px) {
        .cart-container { grid-template-columns: 1fr; gap: 48px; }
        .cart-summary { position: static; }
    }
</style>

<main class="cart-page">
    <div class="cart-overlay"></div>
    
    <div class="cart-container">
        <div>
            <h1 class="cart-title">Your Collection</h1>

            <?php if (empty($cart_products)): ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-bag"></i>
                    <h3 style="font-family: 'Manrope'; font-weight: 800; font-size: 24px; margin-bottom: 12px;">Your bag is empty</h3>
                    <p style="color: var(--text-muted); margin-bottom: 32px;">The laboratory awaits your selection.</p>
                    <a href="products.php" class="checkout-btn" style="display: inline-block; width: auto; padding: 14px 40px;">Explore Hardware</a>
                </div>
            <?php else: ?>
                <div class="cart-list">
                    <?php foreach($cart_products as $p): ?>
                    <div class="cart-item">
                        <div class="cart-img-wrap">
                            <img src="<?php echo $p['main_image']; ?>" alt="">
                        </div>
                        <div class="cart-item-info">
                            <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                            <p><?php echo htmlspecialchars($p['brand']); ?> • <?php echo htmlspecialchars($p['condition']); ?></p>
                            <div class="cart-item-actions">
                                <span class="cart-qty-badge">QTY: <?php echo $p['qty']; ?></span>
                                <a href="cart.php?remove=<?php echo $p['id']; ?>" class="cart-remove-btn">Discard</a>
                            </div>
                        </div>
                        <div class="cart-item-price">
                            ₹<?php echo number_format($p['total']); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($cart_products)): ?>
        <aside>
            <div class="cart-summary animate-fade-up">
                <h3 class="summary-title">Procurement Summary</h3>
                
                <div class="summary-row">
                    <span>Artifact Total</span>
                    <span>₹<?php echo number_format($subtotal); ?></span>
                </div>
                <div class="summary-row">
                    <span>Logistics</span>
                    <span style="color: #34c759; font-weight: 700; text-transform: uppercase;">Complimentary</span>
                </div>
                <div class="summary-row">
                    <span>Estimated Tax</span>
                    <span>Calculated at checkout</span>
                </div>

                <div class="summary-total">
                    <span>Grand Total</span>
                    <span>₹<?php echo number_format($subtotal); ?></span>
                </div>

                <a href="checkout.php" class="checkout-btn">Proceed to Verification</a>
                
                <p style="text-align: center; margin-top: 20px; font-size: 11px; color: var(--text-muted); line-height: 1.5;">
                    Secure transaction encrypted with 256-bit protocol. Guaranteed delivery within 3-5 operational days.
                </p>
            </div>
        </aside>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
