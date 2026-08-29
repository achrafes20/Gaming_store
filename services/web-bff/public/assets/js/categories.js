document.addEventListener('DOMContentLoaded', function () {

    const grid = document.getElementById('productsGrid');
    if (grid) {
        const iso = new Isotope(grid, {
            itemSelector: '.col-lg-3',
            layoutMode: 'fitRows'
        });


        document.querySelectorAll('.category-btn').forEach(button => {
            button.addEventListener('click', function () {

                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.classList.remove('active');
                });


                this.classList.add('active');


                const filterValue = this.getAttribute('data-filter');
                iso.arrange({
                    filter: filterValue
                });
            });
        });
    }


    document.querySelectorAll('.cyber-font, .neon-text-pink, .neon-text-blue').forEach(el => {
        el.addEventListener('mouseenter', function () {
            this.classList.add('glitch-effect');
        });

        el.addEventListener('mouseleave', function () {
            this.classList.remove('glitch-effect');
        });
    });
});
