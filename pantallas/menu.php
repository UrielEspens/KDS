<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}
require_once('procedimientos/conexion.php');
require_once('procedimientos/busquedaMenu.php');
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
                <form action="menu.php" method="get"> <input type="text" name="buscar" placeholder="Buscar productos" value="">
                    <button class="btn" type="submit" value="inicio">Buscar</button>
                </form>
            </div>
            <div class="categorias-container">
                <?php if (!$mostrarResultadosBusqueda): ?>
                    <?php foreach ($categorias as $categoria): ?>
                        <a href="#<?php echo $categoria->getNombre(); ?>"> 
                            <button class="btn botonCategoria" data-categoria="<?php echo $categoria->getNombre(); ?>" data-categoria-id="<?php echo $categoria->getIdCategoria(); ?>">
                                <?php echo $categoria->getNombre(); ?>
                            </button>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="menu.php" class="menu-categorias"><button>Todos</button></a>
                <?php endif; ?>
            </div>
        </div>

        <div class="categorias-container-productos">
            <?php if ($mostrarResultadosBusqueda): ?>
                <div id="resultados-busqueda-section" class="menu-section active-section">
                    <h2>Resultados de Búsqueda para "<?php echo ($textoBusqueda); ?>"</h2>
                    <div class="menu-items">
                        <?php if (!empty($resultadosBusqueda)): ?>
                            <?php foreach ($resultadosBusqueda as $producto): ?>
                                <?php $crudProductos->mostrarProducto($producto); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No se encontraron productos que coincidan con su búsqueda.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div id="todos-section" class="menu-section active-section">
                    <?php foreach ($categorias as $categoria): ?>
                        <a name="<?php echo $categoria->getNombre(); ?>"></a>
                        <h2><?php echo $categoria->getNombre(); ?></h2>
                        <div class="menu-items">
                            <?php foreach ($todosLosProductos as $producto): ?>
                                <?php if ($producto->getIdCategoria() == $categoria->getIdCategoria()): ?>
                                    <?php $crudProductos->mostrarProducto($producto); ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <script src="../js/menuOpciones.js"></script>
</body>
</html>