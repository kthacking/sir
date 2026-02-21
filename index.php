<?php 
include 'includes/header.php'; 

// Fetch Active Banners
$banner_stmt = $pdo->query("SELECT * FROM banners WHERE active = 1 ORDER BY id DESC");
$banners = $banner_stmt->fetchAll();

// Fetch Latest 8 Products
$latest_stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC LIMIT 8");
$latest_products = $latest_stmt->fetchAll();

// Fetch 3 Testimonials
$testimonial_stmt = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC LIMIT 3");
$testimonials = $testimonial_stmt->fetchAll();

// Use sample testimonials if none found in DB
if(count($testimonials) == 0) {
    $testimonials = [
        ['user_name' => 'Aditya Sharma', 'content' => 'The custom build they made for my video editing workflow is incredibly stable and fast. Best tech service in the city!', 'rating' => 5],
        ['user_name' => 'Meera Krishnan', 'content' => 'Got my MacBook screen replaced in just 2 days. Genuine parts and very professional behavior. Highly recommended.', 'rating' => 5],
        ['user_name' => 'Rahul Verma', 'content' => 'Competitive pricing and excellent guidance for first-time PC builders. They really know their hardware.', 'rating' => 4]
    ];
}
?>

<main>
    <!-- Flash Deal Ticker moved to Header -->
    <!-- Section 1: Hero Banner Carousel (Wider Layout) -->
    <section class="banner-section" style="padding-top: 100px; background: #fafafaff;">
        <div class="container-wide">
            <div class="banner-container" style="position: relative; height: 650px; overflow: hidden; border-radius: 0px 0px 40px 40px; background: #000; box-shadow: 0 40px 100px rgba(0,0,0,0.15);">
                <?php if (count($banners) > 0): ?>
                    <?php foreach ($banners as $index => $b): ?>
                        <div class="banner-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="position: absolute; width: 100%; height: 100%; display: <?php echo $index === 0 ? 'flex' : 'none'; ?>; align-items: center; justify-content: center; transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);">
                            <div style="position: absolute; width: 100%; height: 100%; z-index: 1;">
                                <img src="<?php echo $b['image_url']; ?>" alt="<?php echo $b['title']; ?>" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5;">
                                <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(transparent, rgba(0,0,0,0.8));"></div>
                            </div>
                            <div class="banner-content" style="position: relative; z-index: 2; text-align: center; color: white; max-width: 1000px; padding: 0 40px;">
                                <div class="badge-pro fade-in-up" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);"><?php echo htmlspecialchars($b['subtitle'] ?: 'Exclusive Tech Arrival'); ?></div>
                                <h1 style="color: white; margin-bottom: 30px; line-height: 1;" class="fade-in-up"><?php echo htmlspecialchars($b['title']); ?></h1>
                                <p style="font-size: 22px; margin-bottom: 45px; opacity: 0.9; font-weight: 300; max-width: 700px; margin-left: auto; margin-right: auto;" class="fade-in-up">Premium PCs & Expert Service Engineered for Perfection.</p>
                                
                                <div class="fade-in-up" style="display: flex; gap: 24px; justify-content: center;">
                                    <a href="<?php echo $b['link'] ?: 'personal-computers.php'; ?>" class="btn btn-primary" style="padding: 20px 50px;">Explore Store <i class="fas fa-shopping-cart"></i></a>
                                    <a href="get-quote.php" class="btn btn-outline" style="padding: 20px 50px; border-color: white; color: white;">Get a Quote <i class="fas fa-file-invoice"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="banner-slide active" style="position: absolute; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <div style="position: absolute; width: 100%; height: 100%; z-index: 1;">
                            <img src="https://i.pinimg.com/1200x/be/33/08/be3308ae1c7af201936851c8ce917a9e.jpg" alt="Default Hero" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.4;">
                        </div>
                        <div class="banner-content" style="position: relative; z-index: 2; text-align: center; color: white;">
                            <h1 style="color: white; margin-bottom: 25px;">Need for Intelligence.</h1>
                            <p style="font-size: 22px; opacity: 0.8; margin-bottom: 45px; font-weight: 300;">Premium PCs & Expert Service in One Place</p>
                            <a href="personal-computers.php" class="btn btn-primary" style="padding: 20px 60px;">Browse Collections</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Section 2: 🔥 Featured Categories Grid -->
    <section class="section-padding">
        <div class="container-wide">
            <div class="section-title-container">
                <div class="badge-pro">Elevate Your Setup</div>
                <h2 class="gradient-text">World Class Computing</h2>
                <p>Choose from our meticulously curated selection of high-end hardware and certified services.</p>
            </div>
            
            <div class="grid-main">
                <?php 
                $cats = [
                    ['name' => 'Workstations', 'file' => 'personal-computers.php', 'icon' => 'fa-desktop', 'img' => 'https://i.pinimg.com/1200x/be/33/08/be3308ae1c7af201936851c8ce917a9e.jpg', 'desc' => 'High-end workstation systems for professionals.'],
                    ['name' => 'Laptops', 'file' => 'laptops.php', 'icon' => 'fa-laptop', 'img' => 'https://i.pinimg.com/736x/9f/34/d7/9f34d789f664aba97f6e1326498eb2c3.jpg', 'desc' => 'Thin, light and powerful portable machines.'],
                    ['name' => 'Custom Builds', 'file' => 'custom-builds.php', 'icon' => 'fa-gamepad', 'img' => 'https://i.pinimg.com/1200x/41/4e/bf/414ebfd869351533e6ea13af555bb988.jpg', 'desc' => 'Tailored gaming rigs built for performance.'],
                    ['name' => 'Components', 'file' => 'components.php', 'icon' => 'fa-microchip', 'img' => 'https://i.pinimg.com/736x/b2/23/e0/b223e0871a72dcff75483a7758a46371.jpg', 'desc' => 'The best hardware to upgrade your rig.'],
                    ['name' => 'Services', 'file' => 'services.php', 'icon' => 'fa-tools', 'img' => 'https://i.pinimg.com/736x/aa/97/26/aa9726ec3cd7460f4d6aa428d07eb500.jpg', 'desc' => 'Expert repair and support for all devices.'],
                    ['name' => 'Peripherals', 'file' => 'printers-peripherals.php', 'icon' => 'fa-print', 'img' => 'https://i.pinimg.com/736x/e5/c5/fd/e5c5fd7ad7ccf53c35d3010f91e66e7c.jpg', 'desc' => 'Essential gear for your digital lifestyle.']
                ];
                foreach($cats as $index => $c):
                ?>
                <a href="<?php echo $c['file']; ?>" class="category-card fade-in-up" style="text-decoration: none; transition-delay: <?php echo ($index * 0.1); ?>s;">
                    <div class="glass-card-pro" style="padding: 0; overflow: hidden; height: 420px; position: relative;">
                        <img src="<?php echo $c['img']; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.165, 0.84, 0.44, 1);" class="cat-img">
                        <div style="position: absolute; bottom: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.95)); padding: 40px; color: white;">
                            <div style="width: 54px; height: 54px; background: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 25px; box-shadow: 0 10px 20px var(--primary-glow);">
                                <i class="fas <?php echo $c['icon']; ?>" style="font-size: 22px; color: white;"></i>
                            </div>
                            <h3 style="font-size: 26px; color: white; margin-bottom: 10px;"><?php echo $c['name']; ?></h3>
                            <p style="font-size: 15px; opacity: 0.8; color: white; line-height: 1.6;"><?php echo $c['desc']; ?></p>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Section 3: 🏆 Why Choose Us Section (Pro Branding Fixed) -->
    <section class="section-padding" style="background: #000; color: white;">
        <div class="container-wide">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0;">
                <div style="border-right: 1px solid rgba(255,255,255,0.1); padding: 50px;" class="fade-in-up">
                    <i class="fas fa-handshake" style="font-size: 36px; color: var(--primary); margin-bottom: 25px;"></i>
                    <h4 style="font-size: 18px; margin-bottom: 12px; color: white; font-weight: 700;">Authorized Partner</h4>
                    <p style="font-size: 14px; color: #a1a1a6; line-height: 1.5;">Direct partner with HP, Dell, and Apple.</p>
                </div>
                <div style="border-right: 1px solid rgba(255,255,255,0.1); padding: 50px;" class="fade-in-up" style="transition-delay: 0.1s;">
                    <i class="fas fa-shield-check" style="font-size: 36px; color: var(--primary); margin-bottom: 25px;"></i>
                    <h4 style="font-size: 18px; margin-bottom: 12px; color: white; font-weight: 700;">100% Genuine</h4>
                    <p style="font-size: 14px; color: #a1a1a6; line-height: 1.5;">Authentic hardware with official warranty.</p>
                </div>
                <div style="border-right: 1px solid rgba(255,255,255,0.1); padding: 50px;" class="fade-in-up" style="transition-delay: 0.2s;">
                    <i class="fas fa-microchip" style="font-size: 36px; color: var(--primary); margin-bottom: 25px;"></i>
                    <h4 style="font-size: 18px; margin-bottom: 12px; color: white; font-weight: 700;">Expert Integration</h4>
                    <p style="font-size: 14px; color: #a1a1a6; line-height: 1.5;">Certified engineers build your rigs.</p>
                </div>
                <div style="padding: 50px;" class="fade-in-up" style="transition-delay: 0.3s;">
                    <i class="fas fa-award" style="font-size: 36px; color: var(--primary); margin-bottom: 25px;"></i>
                    <h4 style="font-size: 18px; margin-bottom: 12px; color: white; font-weight: 700;">Best Quotes</h4>
                    <p style="font-size: 14px; color: #a1a1a6; line-height: 1.5;">Unbeatable prices for bulk and pro-builds.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- New: FPS Performance Section -->
    <section class="section-padding" style="background: white;">
        <div class="container-wide">
            <div style="display: flex; gap: 80px; align-items: center;">
                <div style="flex: 1;" class="fade-in-up">
                    <div class="badge-pro">Unmatched Power</div>
                    <h2 class="gradient-text">Pro Level Performance</h2>
                    <p style="margin-bottom: 40px;">Our builds are stress-tested with the most demanding workloads to ensure you stay at the top of your game.</p>
                    
                    <div style="background: var(--bg-light); padding: 30px; border-radius: 24px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-weight: 600;">Cyberpunk 2077 (4K Ultra RT)</span>
                            <span style="color: var(--primary); font-weight: 700;">120 FPS</span>
                        </div>
                        <div style="height: 6px; background: #ddd; border-radius: 10px; overflow: hidden;">
                            <div style="width: 95%; height: 100%; background: var(--primary);"></div>
                        </div>
                    </div>
                    <div style="background: var(--bg-light); padding: 30px; border-radius: 24px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-weight: 600;">Adobe Premiere Rendering</span>
                            <span style="color: var(--primary); font-weight: 700;">2x Faster</span>
                        </div>
                        <div style="height: 6px; background: #ddd; border-radius: 10px; overflow: hidden;">
                            <div style="width: 85%; height: 100%; background: var(--primary);"></div>
                        </div>
                    </div>
                </div>
                <div style="flex: 1;" class="fade-in-up">
                    <img src="https://i.pinimg.com/1200x/4b/81/55/4b8155675b91ffa97d2bc99e4f969834.jpg" style="width: 100%; border-radius: 32px; box-shadow: var(--shadow-heavy);">
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: 💻 Latest Products (Dynamic From DB) -->
    <section class="section-padding bg-light">
        <div class="container-wide">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 80px;">
                <div class="fade-in-up">
                    <div class="badge-pro">New Arrivals</div>
                    <h2 class="gradient-text">Latest in Inventory</h2>
                    <p>Experience the cutting-edge of performance computing.</p>
                </div>
                <a href="personal-computers.php" class="btn btn-outline fade-in-up">Browse All Gear <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="grid-main" style="grid-template-columns: repeat(4, 1fr);">
                <?php foreach($latest_products as $p): ?>
                <div class="glass-card fade-in-up" style="padding: 20px; border-radius: 30px;">
                    <div style="height: 260px; background: #fff; border-radius: 20px; margin-bottom: 25px; display: flex; align-items: center; justify-content: center; padding: 30px; overflow: hidden; position: relative;">
                        <?php if($p['stock'] < 5): ?>
                            <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,59,48,0.1); color: #ff3b30; padding: 4px 10px; border-radius: 8px; font-size: 11px; font-weight: 700;">LOW STOCK</div>
                        <?php endif; ?>
                        <img src="<?php echo $p['main_image']; ?>" style="max-height: 100%; max-width: 100%; object-fit: contain; transition: transform 0.6s var(--transition);" class="prod-img">
                    </div>
                    <div style="padding: 0 10px 10px 10px;">
                        <h3 style="font-size: 19px; margin-bottom: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 600;"><?php echo htmlspecialchars($p['name']); ?></h3>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-size: 12px; color: var(--text-grey); margin-bottom: 4px;"><?php echo htmlspecialchars($p['brand']); ?></div>
                                <div style="font-weight: 800; font-size: 24px; color: var(--text-dark);">₹<?php echo number_format($p['offer_price']); ?></div>
                            </div>
                            <a href="product-details.php?id=<?php echo $p['id']; ?>" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px; border-radius: 12px;">Shop <i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Section 5: 🎮 Custom PC Builder Section (Wide Pro) -->
    <section class="section-padding" style="background: white;">
        <div class="container-wide">
            <div class="builder-box fade-in-up">
                <div style="display: flex; align-items: center; gap: 80px;">
                    <div style="flex: 1.2; color: white;">
                        <div class="badge-pro" style="background: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.2);">Rig Configurator</div>
                        <h2 style="color: white; margin-bottom: 30px; font-size: clamp(40px, 6vw, 64px);">Build Your Dream Machine</h2>
                        <p style="color: #a1a1a6; font-size: 20px; font-weight: 300; line-height: 1.6; margin-bottom: 50px;">Precision engineered gaming rigs and workstations tailored exactly to your workload. We build, stress-test and deliver perfection.</p>
                        <div style="display: flex; gap: 24px;">
                            <a href="pc-builder.php" class="btn btn-primary" style="background: white; color: black; padding: 22px 60px;">Configure Now <i class="fas fa-sliders"></i></a>
                            <div style="display: flex; gap: 15px; align-items: center;">
                                <div style="width: 40px; height: 40px; border: 1.5px solid rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-check" style="font-size: 14px; color: var(--primary);"></i></div>
                                <span style="font-size: 15px; color: #a1a1a6;">Free Assembly</span>
                            </div>
                        </div>
                    </div>
                    <div style="flex: 1; text-align: right;">
                        <img src="https://i.pinimg.com/736x/ba/70/40/ba704062d7e3a7ead8f0c598741d4273.jpg" style="width: 100%; border-radius: 40px; transform: perspective(1500px) rotateY(-15deg) scale(1.1); box-shadow: 0 60px 120px rgba(0,0,0,0.6);" class="builder-image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 6: 🏆 Awards & Stats -->
    <section class="section-padding" style="background: #fafafa; border-bottom: 1px solid #eee;">
        <div class="container-wide">
            <div class="grid-main" style="grid-template-columns: repeat(4, 1fr); gap: 80px;">
                <div class="stat-item fade-in-up" style="text-align: center;">
                    <div class="stat-number" data-target="2400" style="font-size: 64px; font-weight: 800; letter-spacing: -3px; color: var(--text-dark);">0</div>
                    <div style="color: var(--text-grey); font-weight: 600; text-transform: uppercase; letter-spacing: 2px; font-size: 12px; margin-top: 10px;">Devices Fixed</div>
                </div>
                <div class="stat-item fade-in-up" style="transition-delay: 0.1s; text-align: center;">
                    <div class="stat-number" data-target="1500" style="font-size: 64px; font-weight: 800; letter-spacing: -3px; color: var(--text-dark);">0</div>
                    <div style="color: var(--text-grey); font-weight: 600; text-transform: uppercase; letter-spacing: 2px; font-size: 12px; margin-top: 10px;">Happy Clients</div>
                </div>
                <div class="stat-item fade-in-up" style="transition-delay: 0.2s; text-align: center;">
                    <div class="stat-number" data-target="600" style="font-size: 64px; font-weight: 800; letter-spacing: -3px; color: var(--text-dark);">0</div>
                    <div style="color: var(--text-grey); font-weight: 600; text-transform: uppercase; letter-spacing: 2px; font-size: 12px; margin-top: 10px;">Pro Builds</div>
                </div>
                <div class="stat-item fade-in-up" style="transition-delay: 0.3s; text-align: center;">
                    <div class="stat-number" data-target="20" style="font-size: 64px; font-weight: 800; letter-spacing: -3px; color: var(--text-dark);">0</div>
                    <div style="color: var(--text-grey); font-weight: 600; text-transform: uppercase; letter-spacing: 2px; font-size: 12px; margin-top: 10px;">Award Won</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 7: 📍 Action CTA -->
    <section class="section-padding" style="background: white;">
        <div class="container-wide">
            <div class="glass-card-pro" style="text-align: center; background: var(--bg-light); border: none; padding: 120px 80px;">
                <h2 class="gradient-text" style="margin-bottom: 25px;">Need Expert Consultation?</h2>
                <p style="font-size: 22px; max-width: 800px; margin: 0 auto 60px;">Whether it's a high-priority server fix or your first gaming workstation, we are here to help.</p>
                <div style="display: flex; gap: 24px; justify-content: center; flex-wrap: wrap;">
                    <a href="tel:+919876543210" class="btn btn-outline" style="min-width: 220px;"><i class="fas fa-phone"></i> Call Support</a>
                    <a href="https://wa.me/1234567890" class="btn btn-outline" style="min-width: 220px; border-color: #25d366; color: #25d366;"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    <a href="get-quote.php" class="btn btn-primary" style="min-width: 220px;">Request Quote <i class="fas fa-file-invoice"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Brand Matrix (Sleek Auto Slider) -->
    <section style="padding: 100px 0; background: white; overflow: hidden; border-top: 1px solid #eee;">
        <div class="logo-track" style="display: flex; width: calc(300px * 12); animation: scrollBrands 40s linear infinite;">
            <?php 
            $brands = ['APPLE', 'DELL', 'HP', 'ASUS', 'MSI', 'GIGABYTE', 'INTEL', 'AMD', 'NVIDIA', 'LENOVO', 'LOGITECH', 'LG'];
            foreach($brands as $brand):
            ?>
            <div class="logo-item" style="width: 300px; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 900; color: #eee; letter-spacing: 5px;"><?php echo $brand; ?></div>
            <?php endforeach; ?>
            <?php foreach($brands as $brand): ?>
            <div class="logo-item" style="width: 300px; display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 900; color: #eee; letter-spacing: 5px;"><?php echo $brand; ?></div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
    @keyframes scrollBrands { 0% { transform: translateX(0); } 100% { transform: translateX(calc(-300px * 12)); } }
    @keyframes ticker { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }
    .category-card:hover .cat-img { transform: scale(1.15) !important; }
    .product-card:hover .prod-img { transform: scale(1.1); }
    .banner-slide.active .banner-content h1 { animation: slideInUp 0.8s forwards; }
    @keyframes slideInUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    // Header Scroll Effect
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('nav');
        if (window.scrollY > 50) nav.classList.add('scrolled');
        else nav.classList.remove('scrolled');
    });

    // Simple Intersection Observer for Animations
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                
                // Stats Counter Logic
                if (entry.target.classList.contains('stat-number') && !entry.target.classList.contains('counted')) {
                    const target = parseInt(entry.target.getAttribute('data-target'));
                    let count = 0;
                    const duration = 2000;
                    const step = target / (duration / 40);
                    const timer = setInterval(() => {
                        count += step;
                        if (count >= target) {
                            entry.target.innerText = target + '+';
                            clearInterval(timer);
                            entry.target.classList.add('counted');
                        } else {
                            entry.target.innerText = Math.floor(count);
                        }
                    }, 40);
                }
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-in-up, .stat-number').forEach(el => io.observe(el));

    // Carousel Logic
    let cur = 0;
    const items = document.querySelectorAll('.banner-slide');
    function next() {
        if(items.length < 2) return;
        items[cur].style.display = 'none';
        items[cur].classList.remove('active');
        cur = (cur + 1) % items.length;
        items[cur].style.display = 'flex';
        items[cur].classList.add('active');
    }
    if(items.length > 1) setInterval(next, 8000);
</script>

<?php include 'includes/footer.php'; ?>
