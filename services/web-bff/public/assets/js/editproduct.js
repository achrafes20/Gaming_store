document.addEventListener('DOMContentLoaded', function () {

    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });


    const fileInput = document.getElementById('photo');
    const filePreview = document.querySelector('.cyber-file-preview');

    if (fileInput && filePreview) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = filePreview.querySelector('img');
                    if (img) {
                        img.src = e.target.result;
                    } else {
                        filePreview.innerHTML = '<img src="' + e.target.result +
                            '" alt="Preview" class="cyber-current-img">';
                    }
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    }


    setInterval(function () {
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
