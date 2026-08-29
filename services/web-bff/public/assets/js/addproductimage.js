document.addEventListener('DOMContentLoaded', function () {

    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });


    const fileInput = document.getElementById('photos');
    const filePreview = document.getElementById('cyber-upload-preview');

    if (fileInput && filePreview) {
        fileInput.addEventListener('change', function () {
            filePreview.innerHTML = '';

            if (this.files && this.files.length > 0) {
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'Preview ' + (i + 1);
                            img.style.maxWidth = '100px';
                            img.style.maxHeight = '100px';
                            img.style.margin = '5px';
                            img.style.borderRadius = '5px';
                            img.style.border = '2px solid var(--cyber-primary)';
                            filePreview.appendChild(img);
                        }

                        reader.readAsDataURL(file);
                    }
                }
            }
        });
    }


    setInterval(function () {
        const uploadBtn = document.querySelector('.cyber-upload-btn');
        if (uploadBtn) {
            uploadBtn.style.boxShadow = '0 0 20px rgba(0, 255, 136, 0.5)';
            setTimeout(() => {
                uploadBtn.style.boxShadow = '0 5px 15px rgba(0, 255, 136, 0.3)';
            }, 1000);
        }
    }, 5000);
});
