<?php
include_once 'includes/config.php';
include 'includes/header.php';

if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='my-orders.php';</script>";
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<script>window.location.href='my-orders.php';</script>";
    exit();
}

$status = $order['status'];
$statuses = ['Pending', 'Processing', 'Shipped', 'Delivered'];
$current_index = array_search($status, $statuses);
if ($current_index === false && $status == 'Cancelled') {
    $current_index = -1;
}
?>

<style>
    .track-main {
        padding-top: 108px;
        background: var(--bg-white);
        min-height: 100vh;
        position: relative;
    }

    .track-overlay {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0,0,0,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0,0,0,0.02) 1px, transparent 1px);
        background-size: 48px 48px;
        pointer-events: none;
    }

    .track-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 48px 24px 96px;
        position: relative;
        z-index: 10;
    }

    .track-header {
        text-align: center;
        margin-bottom: 64px;
    }

    .track-title {
        font-family: 'Manrope', sans-serif;
        font-size: 40px;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1.1;
        color: var(--text-dark);
        margin-bottom: 12px;
    }

    .track-badge {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 8px;
    }

    /* Distribution Progress Protocol */
    .protocol-wrap {
        background: var(--bg-white);
        border: 1px solid var(--border);
        border-radius: var(--radius-xl);
        padding: 64px 48px;
        margin-bottom: 48px;
        box-shadow: var(--shadow-sm);
    }

    .protocol-timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 40px 0;
    }

    .protocol-timeline::after {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--bg);
        z-index: 1;
    }

    .p-line {
        position: absolute;
        top: 24px;
        left: 0;
        height: 2px;
        background: var(--accent);
        z-index: 2;
        transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        width: <?php echo ($current_index >= 0) ? ($current_index / (count($statuses) - 1) * 100) : 0; ?>%;
    }

    .p-step {
        position: relative;
        z-index: 3;
        text-align: center;
        width: 100px;
    }

    .p-dot {
        width: 50px;
        height: 50px;
        background: var(--bg-white);
        border: 2px solid var(--border);
        border-radius: 50%;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: var(--text-muted);
        transition: all 0.4s var(--ease);
    }

    .p-step.active .p-dot {
        border-color: var(--accent);
        color: var(--accent);
        box-shadow: 0 0 0 6px var(--accent-muted);
        background: var(--bg-white);
    }

    .p-step.completed .p-dot {
        background: var(--accent);
        border-color: var(--accent);
        color: white;
    }

    .p-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        transition: color 0.3s;
    }

    .p-step.active .p-label, .p-step.completed .p-label { color: var(--text-dark); }

    /* Info Cards */
    .track-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }

    .details-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 32px;
    }

    .details-card h4 {
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-dark);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .details-card h4 i { color: var(--accent); font-size: 12px; }

    .details-content {
        font-size: 14px;
        color: var(--text-mid);
        line-height: 1.7;
    }

    .details-content strong { color: var(--text-dark); }

    .cancelled-state {
        text-align: center;
        padding: 64px 32px;
        background: #FFF1F0;
        border: 1px solid #FFA39E;
        border-radius: var(--radius-xl);
        color: #CF1322;
    }

    @media (max-width: 600px) {
        .protocol-wrap { padding: 48px 24px; }
        .protocol-timeline { flex-direction: column; gap: 32px; margin: 0; }
        .protocol-timeline::after, .p-line { display: none; }
        .p-step { width: 100%; display: flex; align-items: center; gap: 20px; text-align: left; }
        .p-dot { margin: 0; }
        .track-details-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="track-main">
    <div class="track-overlay"></div>
    
    <div class="track-container">
        <div class="track-header animate-fade-up">
            <span class="track-badge">Logistics Tracking System</span>
            <h1 class="track-title">Manifest #NIT-<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></h1>
        </div>

        <?php if ($status == 'Cancelled'): ?>
            <div class="cancelled-state animate-fade-up">
                <i class="fas fa-times-circle" style="font-size: 48px; margin-bottom: 24px;"></i>
                <h2 style="font-family: 'Manrope'; font-weight: 800; font-size: 28px; margin-bottom: 8px;">Manifest Terminated</h2>
                <p>This distribution cycle has been cancelled by the laboratory or client.</p>
                <a href="contact.php" class="btn-buy" style="margin-top: 32px; display: inline-block; width: auto; padding: 12px 32px; background: #CF1322;">Appeal Termination</a>
            </div>
        <?php else: ?>
            <div class="protocol-wrap animate-fade-up" style="transition-delay: 0.1s;">
                <div class="protocol-timeline">
                    <div class="p-line"></div>
                    <?php 
                    $icons = ['file-lines', 'microchip', 'truck-fast', 'box-check'];
                    foreach ($statuses as $index => $s): 
                        $class = '';
                        if ($index < $current_index) $class = 'completed';
                        elseif ($index == $current_index) $class = 'active';
                    ?>
                    <div class="p-step <?php echo $class; ?>">
                        <div class="p-dot">
                            <i class="fas fa-<?php echo ($index < $current_index) ? 'check' : $icons[$index]; ?>"></i>
                        </div>
                        <div class="p-label"><?php echo $s; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="track-details-grid animate-fade-up" style="transition-delay: 0.2s;">
            <div class="details-card">
                <h4><i class="fas fa-location-dot"></i> Distribution Point</h4>
                <div class="details-content">
                    <?php echo nl2br(htmlspecialchars($order['address'])); ?>
                </div>
            </div>

            <div class="details-card">
                <h4><i class="fas fa-barcode"></i> Registry Info</h4>
                <div class="details-content">
                    <div style="margin-bottom: 12px;">Protocol: <strong>Secure Electronic Transfer</strong></div>
                    <div style="margin-bottom: 12px;">Settlement: <strong><?php echo htmlspecialchars($order['payment_method']); ?></strong></div>
                    <div>Integrity Status: <strong style="color: #34c759;"><?php echo htmlspecialchars($order['payment_status']); ?></strong></div>
                </div>
            </div>
        </div>

        <div style="margin-top: 64px; text-align: center;" class="animate-fade-up">
            <p style="color: var(--text-muted); font-size: 13px;">Require direct technical assistance regarding this manifest? <a href="contact.php" style="color: var(--text-dark); font-weight: 800; text-decoration: none; border-bottom: 1px solid var(--text-dark);">Open Channel</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
