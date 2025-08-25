 document.addEventListener('DOMContentLoaded', function() {
                // Initialize Isotope
                const grid = document.getElementById('productsGrid');
                if (grid) {
                    const iso = new Isotope(grid, {
                        itemSelector: '.col-lg-3',
                        layoutMode: 'fitRows'
                    });

                    // Filter items on button click
                    document.querySelectorAll('.category-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            // Remove active class from all buttons
                            document.querySelectorAll('.category-btn').forEach(btn => {
                                btn.classList.remove('active');
                            });

                            // Add active class to clicked button
                            this.classList.add('active');

                            // Filter items
                            const filterValue = this.getAttribute('data-filter');
                            iso.arrange({
                                filter: filterValue
                            });
                        });
                    });
                }

                // Glitch effect on hover for cyberpunk elements
                document.querySelectorAll('.cyber-font, .neon-text-pink, .neon-text-blue').forEach(el => {
                    el.addEventListener('mouseenter', function() {
                        this.classList.add('glitch-effect');
                    });

                    el.addEventListener('mouseleave', function() {
                        this.classList.remove('glitch-effect');
                    });
                });
            });
