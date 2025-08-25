 document.addEventListener('DOMContentLoaded', function() {
                // Main image preview
                const fileInput = document.getElementById('photo');
                const filePreview = document.getElementById('cyber-file-preview');

                if (fileInput && filePreview) {
                    fileInput.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();

                            reader.onload = function(e) {
                                filePreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                                filePreview.style.display = 'block';
                            }

                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                }

                // Multiple images preview
                const multiFileInput = document.getElementById('photos');
                const multiFilePreview = document.getElementById('cyber-multi-preview');

                if (multiFileInput && multiFilePreview) {
                    multiFileInput.addEventListener('change', function() {
                        multiFilePreview.innerHTML = ''; // Clear previous previews

                        if (this.files && this.files.length > 0) {
                            for (let i = 0; i < this.files.length; i++) {
                                const file = this.files[i];
                                if (file.type.startsWith('image/')) {
                                    const reader = new FileReader();

                                    reader.onload = function(e) {
                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.alt = 'Preview ' + (i + 1);
                                        img.style.maxWidth = '100px';
                                        img.style.maxHeight = '100px';
                                        img.style.borderRadius = '5px';
                                        img.style.border = '1px solid var(--cyber-primary)';
                                        multiFilePreview.appendChild(img);
                                    }

                                    reader.readAsDataURL(file);
                                }
                            }
                        }
                    });
                }

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
            });
