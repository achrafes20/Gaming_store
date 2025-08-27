document.addEventListener('DOMContentLoaded', function () {

    const productCards = document.querySelectorAll('.product-card');
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
                img.style.transform = '';
            }
        });
    });


    setInterval(function () {
        const products = document.querySelectorAll('.product-card');
        products.forEach((product, index) => {
            setTimeout(() => {
                product.style.boxShadow = '0 0 20px rgba(255, 42, 109, 0.3)';
                setTimeout(() => {
                    product.style.boxShadow = '';
                }, 1000);
            }, index * 200);
        });
    }, 8000);
});
