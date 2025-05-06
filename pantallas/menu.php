<!DOCTYPE html>
<html>
<head>
    <title>Menú / Creación de pedidos</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/menu.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">MENÚ</div>
        </div>
            
        <div class="menu-container">
            <button id="carrito">
                <img src="../img/menu/carritoPedido.png" alt="Opciones" height="30px">
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
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                    <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
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
                    <img src="../img/hamburguesa.jpg" alt="Hamburguesa">
                    <div class="item-details">
                        <h3>Hamburguesa</h3>
                        <p>Hamburguesa con carne, lechuga, jitomate y queso</p>
                        <p class="item-price">$80.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="../img/papas_fritas.jpg" alt="Papas francesas">
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
                    <img src="../img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="../img/agua.jpg" alt="Agua">
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
                    <img src="../img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="../img/agua.jpg" alt="Agua">
                    <div class="item-details">
                        <h3>Agua</h3>
                        <p>Botella de agua 600 ml</p>
                        <p class="item-price">$15.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="../img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="../img/agua.jpg" alt="Agua">
                    <div class="item-details">
                        <h3>Agua</h3>
                        <p>Botella de agua 600 ml</p>
                        <p class="item-price">$15.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="../img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="../img/agua.jpg" alt="Agua">
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
                    <img src="../img/coca_cola.jpg" alt="Coca cola">
                    <div class="item-details">
                        <h3>Coca cola</h3>
                        <p>Coca cola 600 ml</p>
                        <p class="item-price">$25.00 MXN</p>
                    </div>
                    <button class="add-buttons">+</button>
                </div>
                <div class="menu-item">
                    <img src="../img/agua.jpg" alt="Agua">
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
    <script src="../js/menu.js"></script>
</body>
</html>