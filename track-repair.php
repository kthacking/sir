<?php 
include_once 'includes/config.php';
include 'includes/header.php'; 

$repair = null;
$error = "";

if (isset($_GET['repair_id'])) {
    $repair_id = $_GET['repair_id'];
    $stmt = $pdo->prepare("SELECT * FROM service_requests WHERE repair_id = ?");
    $stmt->execute([$repair_id]);
    $repair = $stmt->fetch();
    
    if (!$repair) {
        $error = "Repair ID not found. Please check and try again.";
    }
}
?>

<main style="padding-top: 100px;">
    <section class="section-padding">
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h1 style="font-size: 36px; margin-bottom: 15px;">Track Your Repair</h1>
                <p style="color: var(--text-grey);">Enter your unique Repair ID to see the latest status of your device.</p>
            </div>

            <div class="glass-card" style="padding: 40px; margin-bottom: 40px;">
                <form action="track-repair.php" method="GET" style="display: flex; gap: 10px;">
                    <input type="text" name="repair_id" placeholder="e.g. NIT-X7R2P9" required value="<?php echo isset($_GET['repair_id']) ? htmlspecialchars($_GET['repair_id']) : ''; ?>" style="flex: 1; padding: 15px; border-radius: 12px; border: 1px solid #ddd; outline: none; font-size: 16px;">
                    <button type="submit" class="btn btn-primary" style="padding: 0 30px;">Track</button>
                </form>
                <?php if ($error): ?>
                    <p style="color: #ff3b30; font-size: 14px; margin-top: 15px; text-align: center;"><?php echo $error; ?></p>
                <?php endif; ?>
            </div>

            <?php if ($repair): ?>
                <div class="glass-card animate-fade-up" style="padding: 40px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                        <div>
                            <span style="font-size: 12px; color: var(--text-grey); font-weight: 600; text-transform: uppercase;">Repair ID</span>
                            <h3 style="margin-top: 5px;"><?php echo htmlspecialchars($repair['repair_id']); ?></h3>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 12px; color: var(--text-grey); font-weight: 600; text-transform: uppercase;">Current Status</span>
                            <div style="margin-top: 5px; color: var(--primary); font-weight: 700; font-size: 18px;"><?php echo ucfirst($repair['status']); ?></div>
                        </div>
                    </div>

                    <!-- Status Timeline -->
                    <div style="position: relative; padding-left: 30px;">
                        <?php 
                        $statuses = ['received', 'in repair', 'completed', 'delivered'];
                        $current_idx = array_search(strtolower($repair['status']), $statuses);
                        
                        foreach ($statuses as $idx => $s): 
                            $is_done = $idx <= $current_idx;
                            $is_current = $idx == $current_idx;
                        ?>
                            <div style="margin-bottom: 30px; position: relative;">
                                <div style="position: absolute; left: -30px; top: 0; width: 20px; height: 20px; border-radius: 50%; background: <?php echo $is_done ? 'var(--primary)' : '#eee'; ?>; z-index: 2; display: flex; align-items: center; justify-content: center;">
                                    <?php if ($is_done): ?>
                                        <i class="fas fa-check" style="color: white; font-size: 10px;"></i>
                                    <?php endif; ?>
                                </div>
                                <?php if ($idx < 3): ?>
                                    <div style="position: absolute; left: -21px; top: 20px; width: 2px; height: 30px; background: <?php echo $idx < $current_idx ? 'var(--primary)' : '#eee'; ?>; z-index: 1;"></div>
                                <?php endif; ?>
                                <h4 style="font-size: 16px; color: <?php echo $is_done ? 'var(--text-dark)' : 'var(--text-grey)'; ?>;"><?php echo ucfirst($s); ?></h4>
                                <p style="font-size: 13px; color: var(--text-grey);"><?php echo $is_current ? 'Our team is currently working on this phase.' : ($is_done ? 'This phase has been successfully completed.' : 'Estimated phase pending.'); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
