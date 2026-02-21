<?php
include_once 'includes/config.php';
session_destroy();
redirect('login.php');
?>
