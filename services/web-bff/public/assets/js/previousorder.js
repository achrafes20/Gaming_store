document.addEventListener('DOMContentLoaded', function () {

    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });


    const assistantBtn = document.getElementById('cyberAssistant');
    const assistantModal = document.getElementById('assistantModal');
    const closeModal = document.querySelector('.cyber-modal-close');

    assistantBtn.addEventListener('click', function () {
        assistantModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });

    closeModal.addEventListener('click', function () {
        assistantModal.style.display = 'none';
        document.body.style.overflow = '';
    });


    window.addEventListener('click', function (event) {
        if (event.target === assistantModal) {
            assistantModal.style.display = 'none';
            document.body.style.overflow = '';
        }
    });


    const productImages = document.querySelectorAll('.cyber-product-img img');
    productImages.forEach(img => {
        img.addEventListener('mouseenter', function () {
            this.style.transform = 'scale(1.05)';
            this.style.filter = 'brightness(1.2) saturate(1.2)';
        });

        img.addEventListener('mouseleave', function () {
            this.style.transform = '';
            this.style.filter = '';
        });
    });


    setInterval(function () {
        const orderAccordions = document.querySelectorAll('.cyber-order-accordion');
        orderAccordions.forEach((accordion, index) => {
            setTimeout(() => {
                accordion.style.boxShadow = '0 0 20px rgba(0, 255, 136, 0.3)';
                setTimeout(() => {
                    accordion.style.boxShadow = '';
                }, 1000);
            }, index * 300);
        });
    }, 10000);


});

