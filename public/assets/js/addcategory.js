document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('photo');
    const filePreview = document.getElementById('cyber-file-preview');

    if (fileInput && filePreview) {
        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    filePreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                    filePreview.style.display = 'block';
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    }


    setInterval(function () {
        const inputs = document.querySelectorAll('.cyber-input, .cyber-textarea');
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
