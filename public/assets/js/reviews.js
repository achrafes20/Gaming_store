document.addEventListener('DOMContentLoaded', function () {

    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });


    const inputs = document.querySelectorAll('.cyber-input-group input, .cyber-textarea-group textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function () {
            this.parentNode.querySelector('label').style.color = 'var(--cyber-primary)';
        });

        input.addEventListener('blur', function () {
            if (!this.value) {
                this.parentNode.querySelector('label').style.color =
                    'rgba(224, 224, 255, 0.7)';
            }
        });
    });


    const testimonialCards = document.querySelectorAll('.cyber-testimonial-card');
    testimonialCards.forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.querySelector('.cyber-testimonial-glow').style.opacity = '1';
        });

        card.addEventListener('mouseleave', function () {
            this.querySelector('.cyber-testimonial-glow').style.opacity = '0';
        });
    });
});
