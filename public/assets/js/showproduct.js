 // Synchronisation entre le carousel et les miniatures
        const productCarousel = document.getElementById('productCarousel');
        const thumbnails = document.querySelectorAll('.thumb-img');

        // Écouter les événements de changement de slide du carousel
        productCarousel.addEventListener('slid.bs.carousel', function (event) {
            const activeIndex = event.to;

            // Mettre à jour l'état actif des miniatures
            thumbnails.forEach((thumb, index) => {
                if (index === activeIndex) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        });

        // Gestion du clic sur les miniatures
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const slideTo = this.getAttribute('data-bs-slide-to');

                // Activer la diapositive correspondante
                const carousel = bootstrap.Carousel.getInstance(productCarousel);
                carousel.to(slideTo);

                // Mettre à jour l'état actif des miniatures
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Glitch effect on hover for cyberpunk elements
        document.querySelectorAll('.cyber-font, .neon-text-pink, .neon-text-blue').forEach(el => {
            el.addEventListener('mouseenter', function() {
                this.classList.add('glitch-effect');
            });

            el.addEventListener('mouseleave', function() {
                this.classList.remove('glitch-effect');
            });
        });
