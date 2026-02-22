<?php
include_once __DIR__ . '/config.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Need4IT | Premium Computer Sales & Service</title>
    <meta name="description" content="Premium Computer Sales, Service and Custom Builds. Apple inspired design, professional service.">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/index.css">
    <style>
        /* Hide Default Scrollbar */
        ::-webkit-scrollbar { display: none; }
        * { -ms-overflow-style: none; scrollbar-width: none; }

        /* Custom Floating Inside-Page Scrollbar */
        .custom-scroll-container {
            position: fixed;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(5px);
            border-radius: 10px;
            z-index: 9999;
            display: flex;
            justify-content: center;
        }
        .custom-scroll-thumb {
            position: absolute;
            top: 0;
            width: 4px;
            height: 40px;
            background: var(--primary, #0071e3);
            border-radius: 10px;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 113, 227, 0.4);
            transition: all 0.3s ease;
        }
        .custom-scroll-container:hover .custom-scroll-thumb {
            width: 6px;
            box-shadow: 0 0 15px rgba(0, 113, 227, 0.7);
            background: #00d2ff;
        }
        
        /* Ticker Animations */
        @keyframes ticker-rev { 0% { transform: translateX(-100%); } 100% { transform: translateX(0); } }

        /* Banner Hide Animation */
        #promoBanner {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s ease;
        }
        #promoBanner.hide-banner {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }
        #navbar {
            transition: top 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body>
<!-- Promotional Banner / Ad -->
<div id="promoBanner" style="background: var(--text-dark, #1d1d1f); color: white; padding: 12px 0; overflow: hidden; position: fixed; top: 0; width: 100%; z-index: 2000; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center;">
    <div class="ticker-wrap" style="display: flex; white-space: nowrap; animation: ticker-rev 50s linear infinite; flex-grow: 1;">
        <div style="padding: 0 60px; font-size: 13px; font-weight: 500; letter-spacing: 1px; color: #f5f5f7;">🔥 <span style="color: #0071e3;">FLASH SALE:</span> RTX 4090 Stocks Just Arrived! Shop Custom Rigs while stocks last.</div>
        <div style="padding: 0 60px; font-size: 13px; font-weight: 500; letter-spacing: 1px; color: #f5f5f7;">⚡ <span style="color: #0071e3;">SERVICE:</span> MacBook Chip-level repairs in 24h.</div>
        <div style="padding: 0 60px; font-size: 13px; font-weight: 500; letter-spacing: 1px; color: #f5f5f7;">🖥️ <span style="color: #0071e3;">RESTOCK:</span> Studio Display and Mac Studio now in stock.</div>
        <div style="padding: 0 60px; font-size: 13px; font-weight: 500; letter-spacing: 1px; color: #f5f5f7;">🔥 <span style="color: #0071e3;">FLASH SALE:</span> RTX 4090 Rigs ready for delivery!</div>
    </div>
    <button onclick="dismissBanner(true)" style="background: none; border: none; color: white; padding: 0 20px; cursor: pointer; font-size: 18px; z-index: 2001; opacity: 0.6; transition: opacity 0.3s;">&times;</button>
</div>

<nav id="navbar" style="top: 41px;">
    <div class="logo">
        <a href="<?php echo $base_url; ?>" style="text-decoration: none; color: inherit; font-weight: 700; font-size: 20px;">
            Need4<span style="color: var(--primary);">IT</span>
        </a>
    </div>
    <ul class="nav-links">
        <li><a href="<?php echo $base_url; ?>index.php">Home</a></li>
        <li><a href="<?php echo $base_url; ?>products.php">Shop All</a></li>
        <li><a href="<?php echo $base_url; ?>products.php?group=pcs">PCs</a></li>
        <li><a href="<?php echo $base_url; ?>products.php?category=Laptops">Laptops</a></li>
        <li><a href="<?php echo $base_url; ?>pc-builder.php">Custom Builds</a></li>
        <li><a href="<?php echo $base_url; ?>services.php">Services</a></li>
    </ul>
    <div style="display: flex; align-items: center; gap: 20px;">
        <div style="position: relative;">
            <a href="<?php echo $base_url; ?>cart.php" style="color: var(--text-dark); font-size: 18px;"><i class="fas fa-shopping-bag"></i></a>
            <?php 
            $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
            if($cart_count > 0): 
            ?>
            <span style="position: absolute; top: -8px; right: -8px; background: var(--primary); color: white; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700;"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 14px; font-weight: 500; color: var(--text-grey);">Hi, <?php echo $_SESSION['user_name']; ?></span>
                <a href="<?php echo $base_url; ?>logout.php" class="btn btn-outline" style="padding: 6px 15px; font-size: 12px;">Logout</a>
            </div>
        <?php else: ?>
            <a href="<?php echo $base_url; ?>login.php" class="btn btn-outline" style="padding: 8px 20px; font-size: 14px;">Log In</a>
        <?php endif; ?>
        <a href="<?php echo $base_url; ?>get-quote.php" class="btn btn-primary" style="padding: 8px 20px; font-size: 14px;">Get a Quote</a>
    </div>
</nav>

<!-- Custom Scroll Indicator -->
<div class="custom-scroll-container">
    <div class="custom-scroll-thumb" id="customThumb"></div>
</div>

<script>
    // 1. Custom Scrollbar Logic
    const thumb = document.getElementById('customThumb');
    const container = document.querySelector('.custom-scroll-container');
    
    let targetThumbTop = 0;
    let currentThumbTop = 0;

    function smoothUpdate() {
        if (!thumb || !container) return;
        const scrollPercent = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight);
        const maxTop = container.clientHeight - thumb.clientHeight;
        targetThumbTop = scrollPercent * maxTop;
        
        // Linear Interpolation (Lerp) for smoothness
        currentThumbTop += (targetThumbTop - currentThumbTop) * 0.15;
        thumb.style.transform = `translateY(${currentThumbTop}px)`;
        
        requestAnimationFrame(smoothUpdate);
    }

    if (thumb && container) {
        requestAnimationFrame(smoothUpdate);
        container.addEventListener('click', (e) => {
            if (e.target === thumb) return;
            const rect = container.getBoundingClientRect();
            const clickPos = (e.clientY - rect.top) / container.clientHeight;
            window.scrollTo({
                top: clickPos * (document.documentElement.scrollHeight - window.innerHeight),
                behavior: 'smooth'
            });
        });
    }

    // 2. Promotional Banner Logic
    const promoBanner = document.getElementById('promoBanner');
    const navbar = document.getElementById('navbar');

    function dismissBanner(permanent = false) {
        if (!promoBanner) return;
        
        promoBanner.classList.add('hide-banner');
        if (navbar) navbar.style.top = '0px';
        
        if (permanent) {
            localStorage.setItem('promo_dismissed', 'true');
        } else {
            sessionStorage.setItem('promo_session_hidden', 'true');
        }

        // Completely remove from layout after animation
        setTimeout(() => {
            if (promoBanner.classList.contains('hide-banner')) {
                promoBanner.style.display = 'none';
            }
        }, 600);
    }

    // Check visibility on load
    if (promoBanner) {
        if (localStorage.getItem('promo_dismissed') === 'true' || 
            sessionStorage.getItem('promo_session_hidden') === 'true') {
            promoBanner.style.display = 'none';
            if (navbar) navbar.style.top = '0px';
        } else {
            // Handle first scroll
            const handleFirstScroll = () => {
                if (window.scrollY > 10) {
                    dismissBanner(false);
                    window.removeEventListener('scroll', handleFirstScroll);
                }
            };
            window.addEventListener('scroll', handleFirstScroll);
        }
    }
</script>
