<?php
include_once 'includes/config.php';
requireLogin();

// Handle direct buy from pc-builder
if (isset($_GET['direct_buy'])) {
    $pid = (int)$_GET['direct_buy'];
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][$pid] = 1;
}

if (empty($_SESSION['cart'])) {
    redirect('cart.php');
}

// Fetch items in cart for display and processing
$cart_products = [];
$total_amount = 0;
$ids = array_keys($_SESSION['cart']);
$placeholders = str_repeat('?,', count($ids) - 1) . '?';
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

foreach ($products as $p) {
    $qty = $_SESSION['cart'][$p['id']];
    $p['qty'] = $qty;
    $p['subtotal'] = $p['offer_price'] * $qty;
    $total_amount += $p['subtotal'];
    $cart_products[] = $p;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ' - ' . $_POST['postcode'];
    $payment_method = $_POST['payment'];
    
    try {
        $pdo->beginTransaction();
        
        // 1. Create Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, address, payment_method, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$user_id, $total_amount, $address, $payment_method]);
        $order_id = $pdo->lastInsertId();
        
        // 2. Add Order Items
        $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_products as $p) {
            $item_stmt->execute([$order_id, $p['id'], $p['qty'], $p['offer_price']]);
        }
        
        $pdo->commit();
        
        // 3. Clear cart
        unset($_SESSION['cart']);
        $_SESSION['order_success'] = true;
        redirect('order-success.php');
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Order processing failed: " . $e->getMessage();
    }
}

include 'includes/header.php';
?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <h1 style="margin-bottom: 40px; text-align: center;">Checkout</h1>

        <form action="checkout.php" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 380px; gap: 60px; max-width: 1200px; margin: 0 auto;">
                <!-- Billing Details -->
                <div>
                    <?php if (isset($error)): ?>
                        <div style="background: #fee2e2; color: #ef4444; padding: 15px; border-radius: 10px; margin-bottom: 25px;">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <div class="glass-card" style="padding: 40px;">
                        <h3 style="margin-bottom: 30px;">Shipping Information</h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">First Name</label>
                                <input type="text" name="first_name" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Last Name</label>
                                <input type="text" name="last_name" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Street Address</label>
                            <input type="text" name="address" placeholder="House number and street name" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; margin-bottom: 10px;">
                            <input type="text" name="address2" placeholder="Apartment, suite, unit, etc. (optional)" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">City</label>
                                <input type="text" name="city" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">State</label>
                                <input type="text" name="state" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Postcode</label>
                                <input type="text" name="postcode" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Phone</label>
                            <input type="tel" name="phone" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
                        </div>
                    </div>

                    <div class="glass-card" style="padding: 40px; margin-top: 30px;">
                        <h3 style="margin-bottom: 30px;">Payment Method</h3>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; border: 1px solid var(--primary); border-radius: 12px; background: rgba(0, 113, 227, 0.05);">
                                <input type="radio" name="payment" value="card" checked>
                                <span style="font-weight: 500;">Credit / Debit Card</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; border: 1px solid #eee; border-radius: 12px;">
                                <input type="radio" name="payment" value="upi">
                                <span style="font-weight: 500;">UPI / Net Banking</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; border: 1px solid #eee; border-radius: 12px;">
                                <input type="radio" name="payment" value="cod">
                                <span style="font-weight: 500;">Cash on Delivery</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <aside>
                    <div class="glass-card" style="padding: 40px; position: sticky; top: 120px;">
                        <h3 style="margin-bottom: 25px;">Your Order</h3>
                        <div style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; max-height: 400px; overflow-y: auto;">
                            <?php foreach($cart_products as $p): ?>
                            <div style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 15px;">
                                <span style="flex:1;"><?php echo htmlspecialchars($p['name']); ?> × <?php echo $p['qty']; ?></span>
                                <span style="font-weight:600;">₹<?php echo number_format($p['subtotal']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <span style="color: var(--text-grey);">Subtotal</span>
                            <span>₹<?php echo number_format($total_amount); ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <span style="color: var(--text-grey);">Shipping</span>
                            <span style="color: #34c759;">FREE</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-top: 1px solid #eee; padding-top: 20px; margin-bottom: 30px;">
                            <span style="font-weight: 700; font-size: 18px;">Total</span>
                            <span style="font-weight: 700; font-size: 22px; color: var(--primary);">₹<?php echo number_format($total_amount); ?></span>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; font-size: 16px;">Place Order</button>
                    </div>
                </aside>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
