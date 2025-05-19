document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.menu-categories button');
    const menuSections = document.querySelectorAll('.menu-section');

    function showSection(category) {
        menuSections.forEach(section => {
            section.style.display = 'none';
        });
        document.getElementById(category.toLowerCase().replace(' ', '-') + '-section').style.display = 'block';
    }

    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            showSection(this.dataset.categoria);
        });
    });

    // Mostrar la sección "Todos" al cargar la página
    showSection(document.querySelector('.menu-categories .active').dataset.categoria);
});
