document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS animation
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Remove loader when page is loaded
    window.addEventListener('load', function() {
        const loader = document.querySelector('.cyber-loader');
        loader.style.opacity = '0';
        loader.style.visibility = 'hidden';
        setTimeout(() => {
            loader.remove();
        }, 500);
    });

    // Mobile menu toggle
    const mobileToggle = document.querySelector('.cyber-mobile-toggle');
    const navMenu = document.querySelector('.cyber-nav');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }

    // Search functionality
    const searchBtn = document.querySelector('.cyber-search-btn');
    const searchContainer = document.querySelector('.cyber-search-container');
    const searchClose = document.querySelector('.cyber-search-close');

    if (searchBtn && searchContainer) {
        searchBtn.addEventListener('click', function() {
            searchContainer.classList.add('active');
        });
    }

    if (searchClose) {
        searchClose.addEventListener('click', function() {
            searchContainer.classList.remove('active');
        });
    }

    // Back to top button
    const backToTop = document.querySelector('.cyber-back-to-top');

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('active');
        } else {
            backToTop.classList.remove('active');
        }
    });

    if (backToTop) {
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Header scroll effect
    const header = document.getElementById('cyberHeader');
    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;

        if (currentScroll <= 0) {
            header.classList.remove('scroll-up');
        }

        if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-up');
            header.classList.add('scroll-down');
        }

        if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
            header.classList.remove('scroll-down');
            header.classList.add('scroll-up');
        }

        lastScroll = currentScroll;
    });

    // Floating elements animation
    const floatingOrbs = document.querySelectorAll('.cyber-orb');

    setInterval(function() {
        floatingOrbs.forEach(orb => {
            const randomX = Math.random() * 20 - 10;
            const randomY = Math.random() * 20 - 10;
            orb.style.transform = `translate(${randomX}px, ${randomY}px)`;
        });
    }, 3000);

    // Dropdown menu functionality
    const dropdownItems = document.querySelectorAll('.cyber-nav-dropdown');

    dropdownItems.forEach(item => {
        const link = item.querySelector('.cyber-nav-link');
        const dropdown = item.querySelector('.cyber-dropdown-menu');

        link.addEventListener('click', function(e) {
            e.preventDefault();
            dropdown.classList.toggle('active');

            // Close other dropdowns
            dropdownItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.querySelector('.cyber-dropdown-menu').classList.remove('active');
                }
            });
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.cyber-nav-dropdown')) {
            dropdownItems.forEach(item => {
                item.querySelector('.cyber-dropdown-menu').classList.remove('active');
            });
        }
    });

    // Add active class to current page link
    const navLinks = document.querySelectorAll('.cyber-nav-link');
    const currentUrl = window.location.pathname;

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentUrl) {
            link.classList.add('active');
        }
    });
});
