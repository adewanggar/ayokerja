// Mobile Menu
const mobileMenu = () => {
    const toggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('.nav');
    const body = document.body;
    
    if (!toggle || !nav) return;

    toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        nav.classList.toggle('active');
        toggle.classList.toggle('active');
        body.classList.toggle('nav-active');
        toggle.setAttribute('aria-expanded', nav.classList.contains('active'));
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (nav.classList.contains('active') && !nav.contains(e.target) && !toggle.contains(e.target)) {
            nav.classList.remove('active');
            toggle.classList.remove('active');
            body.classList.remove('nav-active');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Close menu when clicking a link
    nav.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            nav.classList.remove('active');
            toggle.classList.remove('active');
            body.classList.remove('nav-active');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });

    // Close menu when scrolling
    window.addEventListener('scroll', () => {
        if (nav.classList.contains('active')) {
            nav.classList.remove('active');
            toggle.classList.remove('active');
            body.classList.remove('nav-active');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
};

// Smooth Scroll
const smoothScroll = () => {
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    mobileMenu();
    smoothScroll();
});