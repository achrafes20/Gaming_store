document.addEventListener('DOMContentLoaded', function() {
                // Initialize AOS animation library
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true
                });

                // Add pulse animation to user accordions periodically
                setInterval(function() {
                    const accordions = document.querySelectorAll('.cyber-user-accordion');
                    accordions.forEach((accordion, index) => {
                        setTimeout(() => {
                            accordion.style.boxShadow = '0 0 20px rgba(0, 240, 255, 0.3)';
                            setTimeout(() => {
                                accordion.style.boxShadow =
                                    '0 5px 15px rgba(0, 240, 255, 0.1)';
                            }, 1000);
                        }, index * 300);
                    });
                }, 10000);
            });
