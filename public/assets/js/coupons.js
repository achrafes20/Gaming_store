 document.addEventListener('DOMContentLoaded', function() {
                // Initialize AOS animation library
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true
                });

                // Add pulse animation to coupon accordions periodically
                setInterval(function() {
                    const accordions = document.querySelectorAll('.cyber-coupon-accordion');
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

                // Set minimum date for expiration date fields
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);

                const minDate = tomorrow.toISOString().split('T')[0];
                document.querySelectorAll('input[type="date"]').forEach(dateInput => {
                    dateInput.min = minDate;
                });
            });
