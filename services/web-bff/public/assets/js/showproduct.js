
const productCarousel = document.getElementById('productCarousel');
const thumbnails = document.querySelectorAll('.thumb-img');


productCarousel.addEventListener('slid.bs.carousel', function (event) {
    const activeIndex = event.to;


    thumbnails.forEach((thumb, index) => {
        if (index === activeIndex) {
            thumb.classList.add('active');
        } else {
            thumb.classList.remove('active');
        }
    });
});


thumbnails.forEach(thumb => {
    thumb.addEventListener('click', function () {
        const slideTo = this.getAttribute('data-bs-slide-to');


        const carousel = bootstrap.Carousel.getInstance(productCarousel);
        carousel.to(slideTo);


        thumbnails.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});


document.querySelectorAll('.cyber-font, .neon-text-pink, .neon-text-blue').forEach(el => {
    el.addEventListener('mouseenter', function () {
        this.classList.add('glitch-effect');
    });

    el.addEventListener('mouseleave', function () {
        this.classList.remove('glitch-effect');
    });
});
