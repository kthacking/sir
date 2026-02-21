<?php
include_once 'includes/config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process order
    // 1. Create Order
    // 2. Add items to order_items
    // 3. Clear cart
    // 4. Redirect to success
    
    $_SESSION['order_success'] = true;
    redirect('order-success.php');
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
                    <div class="glass-card" style="padding: 40px;">
                        <h3 style="margin-bottom: 30px;">Shipping Information</h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">First Name</label>
                                <input type="text" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Last Name</label>
                                <input type="text" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Street Address</label>
                            <input type="text" placeholder="House number and street name" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none; margin-bottom: 10px;">
                            <input type="text" placeholder="Apartment, suite, unit, etc. (optional)" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">City</label>
                                <input type="text" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">State</label>
                                <input type="text" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Postcode</label>
                                <input type="text" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Phone</label>
                            <input type="tel" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                        </div>
                    </div>

                    <div class="glass-card" style="padding: 40px; margin-top: 30px;">
                        <h3 style="margin-bottom: 30px;">Payment Method</h3>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; border: 1px solid var(--primary); border-radius: 12px; background: rgba(0, 113, 227, 0.05);">
                                <input type="radio" name="payment" checked>
                                <span style="font-weight: 500;">Credit / Debit Card</span>
                                <div style="margin-left: auto;">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" style="height: 15px;">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" style="height: 15px; margin-left: 10px;">
                                </div>
                            </label>
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; border: 1px solid #eee; border-radius: 12px;">
                                <input type="radio" name="payment">
                                <span style="font-weight: 500;">UPI / Net Banking</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; padding: 15px; border: 1px solid #eee; border-radius: 12px;">
                                <input type="radio" name="payment">
                                <span style="font-weight: 500;">Cash on Delivery</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <aside>
                    <div class="glass-card" style="padding: 40px; position: sticky; top: 120px;">
                        <h3 style="margin-bottom: 25px;">Your Order</h3>
                        <div style="border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 10px;">
                                <span>Mac Studio M2 Ultra × 1</span>
                                <span>₹3,49,900</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 14px;">
                                <span>MacBook Air M2 × 1</span>
                                <span>₹99,900</span>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <span style="color: var(--text-grey);">Subtotal</span>
                            <span>₹4,49,800</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <span style="color: var(--text-grey);">Shipping</span>
                            <span style="color: #34c759;">FREE</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-top: 1px solid #eee; padding-top: 20px; margin-bottom: 30px;">
                            <span style="font-weight: 700; font-size: 18px;">Total</span>
                            <span style="font-weight: 700; font-size: 22px; color: var(--primary);">₹4,49,800</span>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; font-size: 16px;">Place Order</button>
                    </div>
                </aside>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
