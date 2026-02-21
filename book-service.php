<?php 
include_once 'includes/config.php';
requireLogin();

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_type = $_POST['device_type'];
    $description = $_POST['description'];
    $pickup_date = $_POST['pickup_date'];
    $repair_id = generateRepairID();
    $user_id = $_SESSION['user_id'];
    
    // In a real app, handle image upload here
    $image_path = ""; 
    
    $stmt = $pdo->prepare("INSERT INTO service_requests (user_id, repair_id, device_type, description, status) VALUES (?, ?, ?, ?, 'received')");
    if ($stmt->execute([$user_id, $repair_id, $device_type, $description])) {
        $success = "Repair booked successfully! Your Repair ID is: <strong>$repair_id</strong>. Please save this for tracking.";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}

include 'includes/header.php'; 
?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <div style="max-width: 700px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 36px; margin-bottom: 15px;">Book a Repair</h1>
                <p style="color: var(--text-grey);">Schedule a professional service or repair for your device.</p>
            </div>

            <?php if ($success): ?>
                <div style="background: rgba(52, 199, 89, 0.1); color: #2e7d32; padding: 20px; border-radius: 12px; margin-bottom: 30px; text-align: center; border: 1px solid rgba(52, 199, 89, 0.2);">
                    <?php echo $success; ?>
                    <div style="margin-top: 20px;">
                        <a href="track-repair.php?repair_id=<?php echo $repair_id; ?>" class="btn btn-primary">Track Status Now</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="glass-card" style="padding: 40px;">
                    <form action="book-service.php" method="POST" enctype="multipart/form-data">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Device Type</label>
                                <select name="device_type" required style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none; background: white;">
                                    <option value="Laptop">Laptop</option>
                                    <option value="Desktop">Desktop / Workstation</option>
                                    <option value="Printer">Printer</option>
                                    <option value="Other">Other Peripherals</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Preferred Pickup Date</label>
                                <input type="date" name="pickup_date" required style="width: 100%; padding: 11px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Problem Description</label>
                            <textarea name="description" placeholder="Please describe the issue in detail..." rows="5" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd; outline: none; font-family: inherit;"></textarea>
                        </div>

                        <div style="margin-bottom: 30px;">
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Upload Image (Optional)</label>
                            <input type="file" name="device_image" style="width: 100%; padding: 10px; border: 1px dashed #ccc; border-radius: 10px;">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 16px;">Confirm Booking</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
