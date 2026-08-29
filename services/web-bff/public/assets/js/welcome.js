
document.addEventListener('DOMContentLoaded', function () {

    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });


    const slides = document.querySelectorAll('.cyber-slide');
    let currentSlide = 0;

    function showSlide(n) {
        slides.forEach(slide => slide.classList.remove('active'));
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
    }


    showSlide(0);


    setInterval(() => {
        showSlide(currentSlide + 1);
    }, 5000);


    const productCards = document.querySelectorAll('.cyber-product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function () {
            const img = this.querySelector('img');
            if (img) {
                img.style.transform = 'scale(1.05)';
            }
        });

        card.addEventListener('mouseleave', function () {
            const img = this.querySelector('img');
            if (img) {
                img.style.transform = 'scale(1)';
            }
        });
    });


    gsap.to('.cyber-hologram', {
        y: 20,
        duration: 3,
        repeat: -1,
        yoyo: true,
        ease: "sine.inOut"
    });


    document.querySelectorAll('.btn-cyber-primary').forEach(btn => {
        if (btn.textContent.trim() === 'ADD TO CART') {
            btn.addEventListener('click', function (e) {
                e.preventDefault();


                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> ADDED';
                this.style.background = 'var(--cyber-accent)';


                const cartCount = document.querySelector('.cart-count');
                cartCount.textContent = parseInt(cartCount.textContent) + 1;


                const productCard = this.closest('.cyber-product-card');
                const productImage = productCard.querySelector('.cyber-product-image img')
                    .cloneNode();
                productImage.style.position = 'fixed';
                productImage.style.width = '50px';
                productImage.style.height = 'auto';
                productImage.style.zIndex = '1001';
                productImage.style.pointerEvents = 'none';
                productImage.style.transition = 'all 0.5s ease';

                const rect = productCard.getBoundingClientRect();
                productImage.style.left = rect.left + 'px';
                productImage.style.top = rect.top + 'px';
                document.body.appendChild(productImage);

                const cartRect = document.querySelector('.fa-shopping-cart').closest(
                    'button').getBoundingClientRect();

                setTimeout(() => {
                    productImage.style.left = (cartRect.left + cartRect.width / 2 -
                        25) + 'px';
                    productImage.style.top = (cartRect.top + cartRect.height / 2 -
                        25) + 'px';
                    productImage.style.opacity = '0';
                    productImage.style.transform = 'scale(0.5)';
                }, 50);


                setTimeout(() => {
                    productImage.remove();
                }, 550);


                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.background =
                        'linear-gradient(90deg, var(--cyber-primary), var(--cyber-accent))';
                }, 2000);
            });
        }
    });


    document.querySelectorAll('.cyber-font, .neon-text-primary, .neon-text-secondary').forEach(el => {
        el.addEventListener('mouseenter', function () {
            this.classList.add('glitch-effect');
        });

        el.addEventListener('mouseleave', function () {
            this.classList.remove('glitch-effect');
        });
    });
});
