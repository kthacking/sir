document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');
    
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Animate elements on scroll using GSAP
    gsap.registerPlugin(ScrollTrigger);

    gsap.utils.toArray('.animate-fade-up').forEach(element => {
        gsap.from(element, {
            opacity: 0,
            y: 30,
            duration: 1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: element,
                start: "top 85%",
                toggleActions: "play none none none"
            }
        });
    });

    // 3D Tilt Effect for Apple Product Showcase (Desktop simulation)
    const imacFrame = document.querySelector('.imac-frame');
    if (imacFrame) {
        document.addEventListener('mousemove', (e) => {
            const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
            imacFrame.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
        
        document.addEventListener('mouseleave', () => {
            imacFrame.style.transform = `rotateY(0deg) rotateX(10deg)`;
        });
    }

    // Typewriter effect for hero title
    const heroTitle = document.querySelector('.hero-title-type');
    if (heroTitle) {
        const text = heroTitle.innerText;
        heroTitle.innerText = '';
        let i = 0;
        function type() {
            if (i < text.length) {
                heroTitle.innerHTML += text.charAt(i);
                i++;
                setTimeout(type, 100);
            }
        }
        type();
    }
});
