const categoryButtons = document.querySelectorAll('.categorias-container button');

categoryButtons.forEach(button => {
    button.addEventListener('click', function() {
        categoryButtons.forEach(btn => btn.classList.remove('botonActivo'));
        this.classList.add('botonActivo');
        showSection(this.dataset.categoria);
    });
});
