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
            <a href=carrito.php id="carrito"><img src="../img/menu/carritoPedido.png" alt="Opciones" height="30px"></a>
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
                <input type="text" placeholder="Buscar productos">
                <button>Buscar</button>
            </div>
            <div class="menu-categories">
                <button class="active" data-categoria="todos">Todos</button>
                <button data-categoria="Platos principales">Platos principales</button>
                <button data-categoria="Bebidas">Bebidas</button>
                <button data-categoria="Postres">Postres</button>
            </div>
        </div>

        <div class="menu-container-productos">
            <?php
            // Simulación de la obtención de productos desde una "base de datos"
            $productos = [
                ['id' => 1, 'nombre' => 'Hamburguesa Clásica', 'descripcion' => 'Con carne, lechuga, tomate y queso', 'precio' => '85.00', 'imagen' => '../img/productos/hamburguesa.png', 'categoria' => 'Platos principales'],
                ['id' => 2, 'nombre' => 'Papas Fritas Grandes', 'descripcion' => 'Porción grande y crujiente', 'precio' => '45.00', 'imagen' => '../img/productos/papasFritas.png', 'categoria' => 'Platos principales'],
                ['id' => 3, 'nombre' => 'Coca-Cola 600ml', 'descripcion' => 'Refrescante bebida gaseosa', 'precio' => '25.00', 'imagen' => '../img/productos/cocaCola.png', 'categoria' => 'Bebidas'],
                ['id' => 4, 'nombre' => 'Agua Natural 600ml', 'descripcion' => 'Botella de agua purificada', 'precio' => '15.00', 'imagen' => '../img/productos/agua.png', 'categoria' => 'Bebidas'],
                ['id' => 5, 'nombre' => 'Pastel de Chocolate', 'descripcion' => 'Delicioso pastel de chocolate', 'precio' => '60.00', 'imagen' => '../img/productos/pastelChocolate.png', 'categoria' => 'Postres'],
            ];

            // Plantilla para mostrar cada producto
            function mostrarProducto($producto) {
                echo '<div class="menu-item" data-product-id="' . $producto['id'] . '">';
                echo '<img src="' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
                echo '<div class="item-details">';
                echo '<h3>' . $producto['nombre'] . '</h3>';
                echo '<p>' . $producto['descripcion'] . '</p>';
                echo '<p class="item-price">$' . $producto['precio'] . ' MXN</p>';
                echo '</div>';
                echo '<button class="add-buttons">+</button>';
                echo '</div>';
            }

            // Mostrar todos los productos agrupados por categoría
            $categorias = array_unique(array_column($productos, 'categoria'));

            echo '<div id="todos-section" class="menu-section">';
            foreach ($categorias as $categoria) {
                echo '<h2>' . $categoria . '</h2>';
                echo '<div class="menu-items">';
                foreach ($productos as $producto) {
                    if ($producto['categoria'] === $categoria) {
                        mostrarProducto($producto);
                    }
                }
                echo '</div>';
            }
            echo '</div>';

            echo '<div id="platos-principales-section" class="menu-section" style="display: none;">';
            echo '<h2>Platos principales</h2>';
            echo '<div class="menu-items">';
            foreach ($productos as $producto) {
                if ($producto['categoria'] === 'Platos principales') {
                    mostrarProducto($producto);
                }
            }
            echo '</div>';
            echo '</div>';

            echo '<div id="bebidas-section" class="menu-section" style="display: none;">';
            echo '<h2>Bebidas</h2>';
            echo '<div class="menu-items">';
            foreach ($productos as $producto) {
                if ($producto['categoria'] === 'Bebidas') {
                    mostrarProducto($producto);
                }
            }
            echo '</div>';
            echo '</div>';

            echo '<div id="postres-section" class="menu-section" style="display: none;">';
            echo '<h2>Postres</h2>';
            echo '<div class="menu-items">';
            foreach ($productos as $producto) {
                if ($producto['categoria'] === 'Postres') {
                    mostrarProducto($producto);
                }
            }
            echo '</div>';
            echo '</div>';
            ?>
        </div>
    </div>

    <div id="modalDetallesProducto" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 id="nombre-producto-detalle"></h2>
            <img id="imagen-producto-detalle" src="" alt="" style="max-width: 20%; height: auto;">
            <p id="descripcion-producto-detalle"></p>
            <p class="item-price" id="precio-producto-detalle"></p>
            <button class="add-to-cart">Añadir al carrito</button>
        </div>
    </div>
    <script src="../js/menuOpciones.js"></script>
    <script src="../js/menu.js"></script>
</body>
</html>