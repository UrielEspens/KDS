document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item');
    const modal = document.getElementById('modalDetallesProducto');
    const closeButton = modal.querySelector('.close-button');
    const nombreDetalle = document.getElementById('nombre-producto-detalle');
    const imagenDetalle = document.getElementById('imagen-producto-detalle');
    const descripcionDetalle = document.getElementById('descripcion-producto-detalle');
    const precioDetalle = document.getElementById('precio-producto-detalle');
    const categoryButtons = document.querySelectorAll('.menu-categories button');
    const menuSections = document.querySelectorAll('.menu-section');
    const menuContainerProductos = document.querySelector('.menu-container-productos');

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

    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const productId = this.dataset.productId;
            if (productId) {
                fetch(`obtener_detalles_producto.php?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            nombreDetalle.textContent = data.nombre;
                            descripcionDetalle.textContent = data.descripcion;
                            precioDetalle.textContent = `$${data.precio} MXN`;
                            imagenDetalle.src = data.imagen;
                            modal.style.display = 'block';
                        } else {
                            alert('No se encontraron detalles para este producto.');
                        }
                    })
                    .catch(error => {
                        console.error('Error al obtener los detalles del producto:', error);
                        alert('Ocurrió un error al cargar los detalles.');
                    });
            }
        });
    });

    closeButton.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    // Mostrar la sección "Todos" al cargar la página
    showSection(document.querySelector('.menu-categories .active').dataset.categoria);
});
