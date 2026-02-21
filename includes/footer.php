<footer style="background: white; padding: 100px 40px; border-top: 1px solid #e5e5e5;">
    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 60px;">
        <div>
            <h3 style="margin-bottom: 25px; font-weight: 700;">Need4IT</h3>
            <p style="font-size: 15px; color: var(--text-grey); line-height: 1.6;">The ultimate platform for premium computing hardware and certified professional services. Dedicated to quality and performance.</p>
        </div>
        <div>
            <h4 style="margin-bottom: 25px; font-weight: 600;">Shop & Build</h4>
            <ul style="list-style: none; line-height: 2.2;">
                <li><a href="<?php echo $base_url; ?>shop/index.php" style="color: var(--text-grey); text-decoration: none; font-size: 14px;">Browse Store</a></li>
                <li><a href="<?php echo $base_url; ?>shop/index.php?cat=laptops" style="color: var(--text-grey); text-decoration: none; font-size: 14px;">Laptops</a></li>
                <li><a href="<?php echo $base_url; ?>shop/index.php?cat=workstations" style="color: var(--text-grey); text-decoration: none; font-size: 14px;">Workstations</a></li>
                <li><a href="<?php echo $base_url; ?>pc-builder/" style="color: var(--text-grey); text-decoration: none; font-size: 14px;">Custom PC Builder</a></li>
            </ul>
        </div>
        <div>
            <h4 style="margin-bottom: 25px; font-weight: 600;">Service Hub</h4>
            <ul style="list-style: none; line-height: 2.2;">
                <li><a href="<?php echo $base_url; ?>services/track.php" style="color: var(--text-grey); text-decoration: none; font-size: 14px;">Track Repair</a></li>
                <li><a href="<?php echo $base_url; ?>services/book.php" style="color: var(--text-grey); text-decoration: none; font-size: 14px;">Book a Service</a></li>
                <li><a href="<?php echo $base_url; ?>services/index.php" style="color: var(--text-grey); text-decoration: none; font-size: 14px;">AMC Information</a></li>
                <li><a href="<?php echo $base_url; ?>get-quote.php" style="color: var(--text-grey); text-decoration: none; font-size: 14px;">Bulk Order Quote</a></li>
            </ul>
        </div>
        <div>
            <h4 style="margin-bottom: 25px; font-weight: 600;">Newsletter</h4>
            <p style="font-size: 13px; color: var(--text-grey); margin-bottom: 20px;">Stay updated with latest tech news and offers.</p>
            <form style="display: flex; gap: 8px;">
                <input type="email" placeholder="Email address" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #ddd; outline: none; font-size: 13px;">
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer;"><i class="fas fa-paper-plane"></i></button>
            </form>
            <div style="display: flex; gap: 20px; margin-top: 30px;">
                <a href="#" style="color: var(--text-dark); transition: color 0.3s;"><i class="fab fa-facebook-f"></i></a>
                <a href="#" style="color: var(--text-dark); transition: color 0.3s;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: var(--text-dark); transition: color 0.3s;"><i class="fab fa-twitter"></i></a>
                <a href="#" style="color: var(--text-dark); transition: color 0.3s;"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
    <div style="max-width: 1200px; margin: 80px auto 0 auto; padding-top: 30px; border-top: 1px solid #eee; text-align: center;">
        <p style="font-size: 12px; color: var(--text-grey);">© 2026 Need4IT Premium Computers. All rights reserved. Prices are in INR. Apple and iMac are trademarks of Apple Inc.</p>
    </div>
</footer>

<!-- Modern AI Chatbot UI -->
<div id="ai-chat-btn" style="position: fixed; bottom: 30px; left: 30px; background: var(--text-dark); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: var(--shadow-heavy); z-index: 1001; cursor: pointer; transition: transform 0.3s ease;">
    <i class="fas fa-robot"></i>
</div>

<div id="ai-chat-box" style="position: fixed; bottom: 100px; left: 30px; width: 350px; height: 500px; background: white; border-radius: 20px; box-shadow: var(--shadow-heavy); z-index: 1001; display: none; flex-direction: column; overflow: hidden; border: 1px solid #eee;">
    <div style="background: var(--primary); padding: 20px; color: white; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4 style="margin: 0;">Need4IT AI Support</h4>
            <span style="font-size: 12px; opacity: 0.8;">Online & Ready to Help</span>
        </div>
        <button onclick="toggleChat()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">&times;</button>
    </div>
    <div style="flex: 1; padding: 20px; overflow-y: auto; background: #f9f9f9;" id="chat-messages">
        <div style="background: white; padding: 12px; border-radius: 12px 12px 12px 0; max-width: 80%; margin-bottom: 15px; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            Hello! I'm your Need4IT assistant. How can I help you with your computer or service needs today?
        </div>
    </div>
    <div style="padding: 15px; border-top: 1px solid #eee; display: flex; gap: 10px;">
        <input type="text" placeholder="Type your message..." style="flex: 1; padding: 12px; border-radius: 10px; border: 1px solid #ddd; outline: none; font-size: 14px;">
        <button style="background: var(--primary); color: white; border: none; width: 45px; border-radius: 10px; cursor: pointer;"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<!-- WhatsApp Floating Icon -->
<a href="https://wa.me/1234567890" style="position: fixed; bottom: 30px; right: 30px; background: #25d366; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; box-shadow: var(--shadow-heavy); z-index: 1001; text-decoration: none;">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="<?php echo $base_url; ?>js/main.js"></script>
<script>
    const chatBtn = document.getElementById('ai-chat-btn');
    const chatBox = document.getElementById('ai-chat-box');
    
    chatBtn.addEventListener('click', toggleChat);
    
    function toggleChat() {
        if (chatBox.style.display === 'flex') {
            chatBox.style.display = 'none';
            chatBtn.style.transform = 'rotate(0deg)';
        } else {
            chatBox.style.display = 'flex';
            chatBtn.style.transform = 'rotate(360deg)';
        }
    }
</script>
</body>
</html>
