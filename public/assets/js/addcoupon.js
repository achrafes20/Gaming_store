 document.addEventListener('DOMContentLoaded', function() {
                // Add pulse animation to form inputs periodically
                setInterval(function() {
                    const inputs = document.querySelectorAll('.cyber-input, .cyber-textarea, .cyber-select');
                    inputs.forEach((input, index) => {
                        setTimeout(() => {
                            input.style.boxShadow = '0 0 10px rgba(0, 240, 255, 0.3)';
                            setTimeout(() => {
                                input.style.boxShadow = '';
                            }, 1000);
                        }, index * 300);
                    });
                }, 8000);

                // Set minimum date for expiration date
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);

                const minDate = tomorrow.toISOString().split('T')[0];
                document.getElementById('expires_at').min = minDate;
            });
