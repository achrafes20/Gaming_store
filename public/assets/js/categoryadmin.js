 document.addEventListener('DOMContentLoaded', function() {
                // Initialize AOS animation library
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true
                });

                // Add pulse animation to category accordions periodically
                setInterval(function() {
                    const accordions = document.querySelectorAll('.cyber-category-accordion');
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

                // Image preview functionality
                document.querySelectorAll('.cyber-file-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const previewId = 'cyber-file-preview-' + this.id.split('-')[1];
                        const previewDiv = document.getElementById(previewId);

                        if (this.files && this.files[0]) {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                if (!previewDiv) {
                                    const newPreview = document.createElement('div');
                                    newPreview.id = previewId;
                                    newPreview.className = 'cyber-file-preview';
                                    newPreview.innerHTML = '<img src="' + e.target.result +
                                        '" alt="Preview">';
                                    input.closest('.cyber-file-container').appendChild(newPreview);
                                } else {
                                    previewDiv.innerHTML = '<img src="' + e.target.result +
                                        '" alt="Preview">';
                                }
                            }

                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                });
            });
