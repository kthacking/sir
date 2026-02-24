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
    <title>Need4IT | Premium Computer Sales &amp; Service</title>
    <meta name="description" content="Premium Computer Sales, Service and Custom Builds. Minimal editorial design, professional service.">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/index.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/service-pages.css">
    <style>
        /* ── Promo Banner ─────────────────────────────────────── */
        #promoBanner {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 2000;
            background: #111111;
            color: #f0f0f0;
            height: 41px;
            display: flex;
            align-items: center;
            overflow: hidden;
            border-bottom: 1px solid #222;
            transition: transform 0.5s cubic-bezier(0.4,0,0.2,1), opacity 0.4s ease;
        }

        #promoBanner.hide-banner {
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }

        .ticker-wrap {
            display: flex;
            white-space: nowrap;
            animation: ticker-rev 60s linear infinite;
            flex-grow: 1;
        }

        .ticker-item {
            padding: 0 56px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.70);
        }

        .ticker-accent { color: #FF3B30; }

        #navbar {
            transition: top 0.5s cubic-bezier(0.4,0,0.2,1), background 0.35s ease, box-shadow 0.35s ease;
        }

        /* ── Navigation ──────────────────────────────────────── */
        .logo a {
            text-decoration: none;
            font-family: 'Manrope', sans-serif;
            font-weight: 800;
            font-size: 18px;
            letter-spacing: -0.5px;
            color: #111;
        }

        .logo a .logo-accent { color: #FF3B30; }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-cart-link {
            color: #111;
            font-size: 16px;
            position: relative;
            text-decoration: none;
            transition: color 0.2s;
        }
        .nav-cart-link:hover { color: #FF3B30; }

        .cart-badge {
            position: absolute;
            top: -7px;
            right: -7px;
            background: #FF3B30;
            color: white;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 700;
        }

        .nav-user-name {
            font-size: 13px;
            font-weight: 700;
            color: #111;
        }

        .nav-orders-link {
            font-size: 11px;
            text-decoration: none;
            color: #FF3B30;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Custom Scrollbar ────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    </style>
</head>
<body>

<!-- Promotional Banner -->
<div id="promoBanner">
    <div class="ticker-wrap">
        <div class="ticker-item">🔥 <span class="ticker-accent">FLASH SALE:</span> RTX 4090 Stocks Just Arrived! Shop Custom Rigs while stocks last.</div>
        <div class="ticker-item">⚡ <span class="ticker-accent">SERVICE:</span> MacBook Chip-level repairs in 24h.</div>
        <div class="ticker-item">🖥️ <span class="ticker-accent">RESTOCK:</span> Studio Display and Mac Studio now in stock.</div>
        <div class="ticker-item">🔥 <span class="ticker-accent">FLASH SALE:</span> RTX 4090 Rigs ready for delivery!</div>
    </div>
    <button onclick="dismissBanner(true)" aria-label="Dismiss banner" style="background:none;border:none;color:rgba(255,255,255,0.5);padding:0 18px;cursor:pointer;font-size:17px;z-index:2001;flex-shrink:0;transition:color .2s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">&times;</button>
</div>

<nav id="navbar" style="top:41px;">
    <div class="logo">
        <a href="<?php echo $base_url; ?>">
            Need4<span class="logo-accent">IT</span>
        </a>
    </div>

    <ul class="nav-links">
        <li><a href="<?php echo $base_url; ?>index.php">Home</a></li>
        <li><a href="<?php echo $base_url; ?>products.php">Shop All</a></li>
        <li><a href="<?php echo $base_url; ?>products.php?group=pcs">PCs</a></li>
        <li><a href="<?php echo $base_url; ?>products.php?group=laptops">Laptops</a></li>
        <li><a href="<?php echo $base_url; ?>pc-builder.php">Custom Builds</a></li>
        <li><a href="<?php echo $base_url; ?>services.php">Services</a></li>
    </ul>

    <div class="nav-actions">
        <div style="position:relative;">
            <a href="<?php echo $base_url; ?>cart.php" class="nav-cart-link"><i class="fas fa-shopping-bag"></i></a>
            <?php
            $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
            if($cart_count > 0):
            ?>
            <span class="cart-badge"><?php echo $cart_count; ?></span>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="text-align:right;">
                    <div class="nav-user-name">Hi, <?php echo $_SESSION['user_name']; ?></div>
                    <a href="my-orders.php" class="nav-orders-link">My Orders</a>
                </div>
                <a href="<?php echo $base_url; ?>logout.php" class="btn btn-outline" style="padding:8px 18px;font-size:12px;height:36px;">Logout</a>
            </div>
        <?php else: ?>
            <a href="<?php echo $base_url; ?>login.php" class="btn btn-outline" style="padding:8px 18px;font-size:13px;height:36px;">Log In</a>
        <?php endif; ?>

        <a href="<?php echo $base_url; ?>get-quote.php" class="btn btn-primary" style="padding:8px 20px;font-size:13px;height:36px;">Get a Quote</a>
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
            window.scrollTo({ top: clickPos * (document.documentElement.scrollHeight - window.innerHeight), behavior: 'smooth' });
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
        setTimeout(() => {
            if (promoBanner.classList.contains('hide-banner')) {
                promoBanner.style.display = 'none';
            }
        }, 600);
    }

    if (promoBanner) {
        if (localStorage.getItem('promo_dismissed') === 'true' ||
            sessionStorage.getItem('promo_session_hidden') === 'true') {
            promoBanner.style.display = 'none';
            if (navbar) navbar.style.top = '0px';
        } else {
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
