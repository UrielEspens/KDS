const btnCarrito = document.getElementById("carrito");
const menuCarrito = document.getElementById("carritoPedido");
const btnMenu = document.getElementById("toggleMenu");
const menuOpciones = document.getElementById("menu");

function closeOtherMenu(openMenu) {
    if (openMenu === menuCarrito) {
        menuOpciones.style.display = "none";
    } else if (openMenu === menuOpciones) {
        menuCarrito.style.display = "none";
    }
}

btnCarrito.addEventListener("click", () => {
    if (menuCarrito.style.display === "none" || menuCarrito.style.display === "") {
        menuCarrito.style.display = "block";
        closeOtherMenu(menuCarrito);
    } else {
        menuCarrito.style.display = "none";
    }
});

btnMenu.addEventListener("click", () => {
    if (menuOpciones.style.display === "none" || menuOpciones.style.display === "") {
        menuOpciones.style.display = "block";
        closeOtherMenu(menuOpciones);
    } else {
        menuOpciones.style.display = "none";
    }
});

// Cierra los menús si se hace clic fuera de ellos
document.addEventListener('click', (event) => {
    if (!btnCarrito.contains(event.target) && !menuCarrito.contains(event.target)) {
        menuCarrito.style.display = 'none';
    }
    if (!btnMenu.contains(event.target) && !menuOpciones.contains(event.target)) {
        menuOpciones.style.display = 'none';
    }
});