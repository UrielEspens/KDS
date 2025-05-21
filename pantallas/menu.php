<?php
require_once('procedimientos/conexion.php');
require_once('procedimientos/crudProductos.php'); 
require_once('procedimientos/claseInfoProducto.php');
require_once('procedimientos/claseCategoria.php');

$crudProductos = new CrudProductos();
$categorias = $crudProductos->obtenerCategorias(); // Obtener todas las categorías de la DB
$todosLosProductos = $crudProductos->obtenerTodosLosProductos(); // Obtener todos los productos de la DB


?>

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
            <a href="carrito.php" id="carrito"><img src="../img/menu/carritoPedido.png" alt="Opciones" height="30px"></a>
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu"> Ver menú</a></li>
                    <hr>
                    <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
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
                <?php foreach ($categorias as $categoria): ?>
                    <button data-categoria="<?php echo $categoria->getNombre(); ?>" data-categoria-id="<?php echo $categoria->getIdCategoria(); ?>">
                        <?php echo $categoria->getNombre(); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="menu-container-productos">
            <div id="todos-section" class="menu-section">
                <?php foreach ($categorias as $categoria): ?>
                    <h2><?php echo $categoria->getNombre(); ?></h2>
                    <div class="menu-items">
                        <?php foreach ($todosLosProductos as $producto): ?>
                            <?php if ($producto->getIdCategoria() == $categoria->getIdCategoria()): ?>
                                <?php $crudProductos->mostrarProducto($producto); // ¡Ahora se llama como método! ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php foreach ($categorias as $categoria): ?>
                <div id="<?php echo strtolower(str_replace(' ', '-', $categoria->getNombre())); ?>-section" class="menu-section" style="display: none;">
                    <h2><?php echo $categoria->getNombre(); ?></h2>
                    <div class="menu-items">
                        <?php foreach ($todosLosProductos as $producto): ?>
                            <?php if ($producto->getIdCategoria() == $categoria->getIdCategoria()): ?>
                                <?php $crudProductos->mostrarProducto($producto); // ¡Ahora se llama como método! ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
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