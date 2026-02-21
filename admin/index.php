<?php
include_once '../includes/config.php';

if (isAdmin()) {
    redirect('admin/dashboard.php');
} else {
    redirect('login.php');
}
?>
