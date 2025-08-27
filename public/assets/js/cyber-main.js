document.addEventListener('DOMContentLoaded', function () {

    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });


    window.addEventListener('load', function () {
        const loader = document.querySelector('.cyber-loader');
        if (loader) {
            loader.style.opacity = '0';
            loader.style.visibility = 'hidden';
            setTimeout(() => {
                loader.remove();
            }, 500);
        }
    });


    const mobileToggle = document.querySelector('.cyber-mobile-toggle');
    const navMenu = document.querySelector('.cyber-nav');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function () {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }


    const searchBtn = document.querySelector('.cyber-search-btn');
    const searchContainer = document.querySelector('.cyber-search-container');
    const searchClose = document.querySelector('.cyber-search-close');

    if (searchBtn && searchContainer) {
        searchBtn.addEventListener('click', function () {
            searchContainer.classList.add('active');
        });
    }

    if (searchClose) {
        searchClose.addEventListener('click', function () {
            searchContainer.classList.remove('active');
        });
    }


    const backToTop = document.querySelector('.cyber-back-to-top');

    window.addEventListener('scroll', function () {
        if (backToTop) {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        }
    });

    if (backToTop) {
        backToTop.addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }


    const header = document.getElementById('cyberHeader');
    if (header) {
        let lastScroll = 0;

        window.addEventListener('scroll', function () {
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
    }


    const floatingOrbs = document.querySelectorAll('.cyber-orb');

    setInterval(function () {
        floatingOrbs.forEach(orb => {
            const randomX = Math.random() * 20 - 10;
            const randomY = Math.random() * 20 - 10;
            orb.style.transform = `translate(${randomX}px, ${randomY}px)`;
        });
    }, 3000);


    const dropdownItems = document.querySelectorAll('.cyber-nav-dropdown');

    dropdownItems.forEach(item => {
        const link = item.querySelector('.cyber-nav-link');
        const dropdown = item.querySelector('.cyber-dropdown-menu');

        if (link && dropdown) {
            link.addEventListener('click', function (e) {

                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    item.classList.toggle('active');
                }
            });
        }
    });


    document.addEventListener('click', function (e) {
        if (!e.target.closest('.cyber-nav-dropdown')) {
            dropdownItems.forEach(item => {
                item.classList.remove('active');
            });
        }
    });


    const navLinks = document.querySelectorAll('.cyber-nav-link');
    const currentUrl = window.location.pathname;

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentUrl) {
            link.classList.add('active');
        }
    });
});
