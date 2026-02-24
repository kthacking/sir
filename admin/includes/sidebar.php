<?php
// admin/includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <a href="dashboard.php" class="logo">Need4IT <span style="color: var(--primary);">Admin</span></a>
    <ul class="nav-menu">
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="manage-products.php" class="nav-link <?php echo $current_page == 'manage-products.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a href="manage-pc-builder.php" class="nav-link <?php echo $current_page == 'manage-pc-builder.php' ? 'active' : ''; ?>">
                <i class="fas fa-microchip"></i> PC Builder
            </a>
        </li>
        <li class="nav-item">
            <a href="manage-services.php" class="nav-link <?php echo $current_page == 'manage-services.php' ? 'active' : ''; ?>">
                <i class="fas fa-tools"></i> Services
            </a>
        </li>
        <li class="nav-item">
            <a href="manage-orders.php" class="nav-link <?php echo $current_page == 'manage-orders.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
        </li>
        <li class="nav-item">
            <a href="manage-users.php" class="nav-link <?php echo $current_page == 'manage-users.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
        <li class="nav-item">
            <a href="manage-banners.php" class="nav-link <?php echo $current_page == 'manage-banners.php' ? 'active' : ''; ?>">
                <i class="fas fa-image"></i> Banners
            </a>
        </li>
        <li class="nav-item">
            <a href="reports.php" class="nav-link <?php echo $current_page == 'reports.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i> Reports
            </a>
        </li>
    </ul>
    <div style="padding-top: 20px; border-top: 1px solid var(--border);">
        <a href="../logout.php" class="nav-link" style="color: #ff3b30;">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </a>
    </div>
</aside>

<!-- Global Custom Confirmation Modal -->
<div id="customConfirmModal">
    <div class="confirm-card">
        <div class="confirm-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="confirm-title" id="confirmTitle">Unsaved Changes</div>
        <div class="confirm-text" id="confirmMessage">You have unsaved changes. Do you want to discard them?</div>
        <div class="confirm-actions">
            <button class="confirm-btn-cancel" onclick="handleConfirmResponse(false)">Stay on Page</button>
            <button class="confirm-btn-danger" onclick="handleConfirmResponse(true)">Discard Changes</button>
        </div>
    </div>
</div>

<script>
    let confirmResolver = null;

    function customConfirm(title, message, btnCancel, btnDanger) {
        return new Promise((resolve) => {
            document.getElementById('confirmTitle').innerText = title || 'Unsaved Changes';
            document.getElementById('confirmMessage').innerText = message || 'You have unsaved changes. Do you want to discard them?';
            
            const cancelBtn = document.querySelector('.confirm-btn-cancel');
            const dangerBtn = document.querySelector('.confirm-btn-danger');
            
            if (btnCancel) cancelBtn.innerText = btnCancel;
            if (btnDanger) dangerBtn.innerText = btnDanger;
            
            document.getElementById('customConfirmModal').style.display = 'flex';
            confirmResolver = resolve;
        });
    }

    function handleConfirmResponse(response) {
        document.getElementById('customConfirmModal').style.display = 'none';
        if (confirmResolver) confirmResolver(response);
    }
</script>
