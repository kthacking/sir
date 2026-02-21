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
