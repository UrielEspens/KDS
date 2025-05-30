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
            <div class="title">MODIFICAR MENÚ</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu"> Ver menú</a></li>
                    <hr>
                    <li><a href="historialPedidos.php"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="menu-body">
        <div class="menu-options">
            <div class="categorias-container">
                <?php foreach ($categorias as $categoria): ?>
                    <a href="#<?php echo $categoria->getNombre(); ?>">
                        <button class="btn botonCategoria" data-categoria="<?php echo $categoria->getNombre(); ?>" data-categoria-id="<?php echo $categoria->getIdCategoria(); ?>">
                            <?php echo $categoria->getNombre(); ?>
                        </button>
                    </a>
                <?php endforeach; ?>
            </div>
            <a href="eliminarCategoria.php" class="menu-categorias"><button class="btn botonEliminar">Eliminar categoría &nbsp;</button></a>
            <a href="agregarCategoria.php" class="menu-categorias"><button class="btn botonAgregar">Agregar categoría</button></a>
            <a href="agregarProducto.php" class="menu-categorias"><button class="btn botonAgregar">Agregar producto</button></a>
        </div>

        <div class="categorias-container-productos">
            <div id="todos-section" class="menu-section active-section">
                <?php foreach ($categorias as $categoria): ?>
                    <a name="<?php echo $categoria->getNombre(); ?>"></a>
                    <h2><?php echo $categoria->getNombre(); ?></h2>
                    <div class="menu-items">
                        <?php foreach ($todosLosProductos as $producto): ?>
                            <?php if ($producto->getIdCategoria() == $categoria->getIdCategoria()): ?>
                                <?php $crudProductos->mostrarProductoEditable($producto); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script src="../js/menuOpciones.js"></script>
    <script src="../js/menu.js" defer></script>
</body>
</html>