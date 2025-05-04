<!DOCTYPE html>
<html>
<head>
    <title>Menú / Creación de pedidos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="img/hogar.png" alt="Home" height="30px"></a>
            <div class="title">MENÚ</div>
        </div>
            
        <div class="menu-container">
            <button id="carrito">
                <img src="img/carritoPedido.png" alt="Opciones" height="30px">
            </button>
            <div id="carritoPedido">
                <ul>
                    <li>No. Pedido: 1</li>
                    <li>Cliente: nombreCliente</li>
                    <li>Precio: $100.00</li>
                    <li> ...</li>
                    <li> ...</li>
                    <li> ...</li>
                    <li> ...</li>
                    <li> ...</li>
                </ul>
            </div>
            
            <button id="toggleMenu">
                <img src="img/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <li><a href="menu.php"><img src="img/menuRestaurante.png" alt="menuComida" class="icono-menu"> Ver menú</a></li>
                    <li><a href="historialPedidos.php"><img src="img/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <li><a href="configuracion.php"><img src="img/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
            
            <script>
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
            </script>
        </div>
    </div>

    <div class="menu-body">
        <div class="menu-options">
            <div class="menu-search-container">
                <input type="text" placeholder="Ingrese el nombre del producto">
                <button>Buscar</button>
            </div>
            <div class="menu-categories">
                <button class="active">Platos principales</button>
                <button>Bebidas</button>
                <button>Postres</button>
            </div>
        </div>

        <div class="menu-section">
            <h2>Platos principales</h2>
            <div class="menu-items">
                <div class="menu-item">
                    <img src="img/hamburguesa.jpg" alt="Hamburguesa">
                    <div class="item-details">
                        <h3>Hamburguesa</h3>
                        <p>Hamburguesa con carne, lechuga, jitomate y queso</p>
                        <p class="item-price">$80.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="img/papas_fritas.jpg" alt="Papas francesas">
                    <div class="item-details">
                        <h3>Papas francesas</h3>
                        <p>Ordenes de 100g de papas francesas</p>
                        <p class="item-price">$15.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2>Bebidas</h2>
            <div class="menu-items">
                <div class="menu-item">
                    <img src="img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="img/agua.jpg" alt="Agua">
                    <div class="item-details">
                        <h3>Agua</h3>
                        <p>Botella de agua 600 ml</p>
                        <p class="item-price">$15.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2>Bebidas</h2>
            <div class="menu-items">
                <div class="menu-item">
                    <img src="img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="img/agua.jpg" alt="Agua">
                    <div class="item-details">
                        <h3>Agua</h3>
                        <p>Botella de agua 600 ml</p>
                        <p class="item-price">$15.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="img/agua.jpg" alt="Agua">
                    <div class="item-details">
                        <h3>Agua</h3>
                        <p>Botella de agua 600 ml</p>
                        <p class="item-price">$15.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="img/agua.jpg" alt="Agua">
                    <div class="item-details">
                        <h3>Agua</h3>
                        <p>Botella de agua 600 ml</p>
                        <p class="item-price">$15.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
            </div>
        </div>

        <div class="menu-section">
            <h2>Bebidas</h2>
            <div class="menu-items">
                <div class="menu-item">
                    <img src="img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="img/agua.jpg" alt="Agua">
                    <div class="item-details">
                        <h3>Agua</h3>
                        <p>Botella de agua 600 ml</p>
                        <p class="item-price">$15.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>