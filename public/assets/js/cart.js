 document.addEventListener('DOMContentLoaded', function() {
                // Initialize AOS animation library
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true
                });

                // Show/hide mini cart on scroll
                const miniCart = document.querySelector('.cyber-mini-cart');
                let lastScrollPosition = window.scrollY;

                window.addEventListener('scroll', function() {
                    const currentScrollPosition = window.scrollY;

                    if (currentScrollPosition > 200) {
                        miniCart.classList.add('visible');

                        if (currentScrollPosition < lastScrollPosition) {
                            // Scrolling up
                            miniCart.style.transform = 'translateY(0)';
                        } else {
                            // Scrolling down
                            miniCart.style.transform = 'translateY(70px)';
                        }
                    } else {
                        miniCart.classList.remove('visible');
                    }

                    lastScrollPosition = currentScrollPosition;
                });

                // Quantity buttons functionality
                document.querySelectorAll('.cyber-qty-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const itemId = this.getAttribute('data-item');
                        const isPlus = this.classList.contains('plus');
                        const qtyElement = this.parentElement.querySelector('.cyber-qty-value');
                        let qty = parseInt(qtyElement.textContent);

                        if (isPlus) {
                            qty++;
                        } else if (qty > 1) {
                            qty--;
                        }

                        qtyElement.textContent = qty;

                        // In a real app, you would send an AJAX request here to update the cart
                        // Example:
                        // fetch(`/update-cart/${itemId}`, {
                        //     method: 'POST',
                        //     headers: {
                        //         'Content-Type': 'application/json',
                        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        //     },
                        //     body: JSON.stringify({ quantity: qty })
                        // })
                        // .then(response => response.json())
                        // .then(data => {
                        //     // Update totals
                        // });
                    });
                });

                // Add hover effect to cart items
                const cartItems = document.querySelectorAll('.cyber-cart-item');
                cartItems.forEach(item => {
                    item.addEventListener('mouseenter', function() {
                        const img = this.querySelector('img');
                        if (img) {
                            img.style.transform = 'scale(1.05)';
                            img.style.filter = 'brightness(1.1) saturate(1.1)';
                        }
                    });

                    item.addEventListener('mouseleave', function() {
                        const img = this.querySelector('img');
                        if (img) {
                            img.style.transform = '';
                            img.style.filter = '';
                        }
                    });
                });

                // Pulse animation for cart items
                setInterval(function() {
                    const items = document.querySelectorAll('.cyber-cart-item');
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.boxShadow = '0 0 15px rgba(0, 240, 255, 0.2)';
                            setTimeout(() => {
                                item.style.boxShadow = '';
                            }, 1000);
                        }, index * 200);
                    });
                }, 8000);
            });
