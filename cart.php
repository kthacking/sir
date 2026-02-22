<?php
include_once 'includes/config.php';

// Handle Add to Cart
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    // Simple logic: if exists, increment, else add 1
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

<main style="padding-top: 100px; min-height: 80vh;">
    <section class="section-padding">
        <div style="max-width: 1300px; margin: 0 auto; padding: 0 20px;">
            <h1 style="margin-bottom: 40px;">Your Bag</h1>

            <div style="display: grid; grid-template-columns: 1fr 380px; gap: 60px;">
                <!-- Cart Items -->
                <div>
                    <?php if (empty($cart_products)): ?>
                        <div style="text-align: center; padding: 60px;">
                            <i class="fas fa-shopping-bag" style="font-size: 60px; color: #eee; margin-bottom: 20px;"></i>
                            <h3>Your bag is empty</h3>
                            <p style="color: var(--text-grey); margin-top: 10px;">Explore our products and find something you love.</p>
                            <a href="index.php" class="btn btn-primary" style="margin-top: 20px;">Browse Store</a>
                        </div>
                    <?php else: ?>
                        <?php foreach($cart_products as $p): ?>
                        <div class="glass-card" style="display: flex; gap: 30px; padding: 30px; margin-bottom: 20px; align-items: center;">
                            <div style="width: 120px; height: 120px; background: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                <img src="<?php echo $p['main_image']; ?>" style="max-width: 90%; max-height: 90%; object-fit: contain;">
                            </div>
                            <div style="flex: 1;">
                                <h3 style="margin-bottom: 8px;"><?php echo $p['name']; ?></h3>
                                <p style="font-size: 14px; color: var(--text-grey); margin-bottom: 15px;"><?php echo $p['brand']; ?> - <?php echo $p['condition']; ?></p>
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <span style="font-size: 14px; color: var(--text-grey);">Quantity: <?php echo $p['qty']; ?></span>
                                    <a href="cart.php?remove=<?php echo $p['id']; ?>" style="background: none; border: none; color: #ff3b30; font-size: 13px; cursor: pointer; font-weight: 500; text-decoration: none;">Remove</a>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <span style="display: block; font-weight: 700; font-size: 20px;">₹<?php echo number_format($p['total']); ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Summary -->
                <?php if (!empty($cart_products)): ?>
                <aside>
                    <div class="glass-card" style="position: sticky; top: 120px; padding: 40px;">
                        <h3 style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">Order Summary</h3>
                        
                        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; font-size: 15px;">
                            <span style="color: var(--text-grey);">Subtotal</span>
                            <span>₹<?php echo number_format($subtotal); ?></span>
                        </div>
                        <div style="margin-bottom: 20px; display: flex; justify-content: space-between; font-size: 15px;">
                            <span style="color: var(--text-grey);">Shipping</span>
                            <span style="color: #34c759; font-weight: 600;">FREE</span>
                        </div>
                        <div style="margin-bottom: 30px; padding-top: 20px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 700; font-size: 20px;">Total</span>
                            <span style="font-weight: 700; font-size: 24px; color: var(--primary);">₹<?php echo number_format($subtotal); ?></span>
                        </div>

                        <a href="checkout.php" class="btn btn-primary" style="width: 100%; padding: 18px; text-align: center;">Check Out</a>
                    </div>
                </aside>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
