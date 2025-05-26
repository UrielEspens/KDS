<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}
require_once('procedimientos/conexion.php');
require_once('procedimientos/claseProducto.php');
require_once('procedimientos/crud.Producto.php');
require_once('procedimientos/claseCategoria.php');
require_once('procedimientos/crudCategoria.php');

$crudProductos = new CrudProducto();
$crudCategorias = new CrudCategoria();

$categorias = $crudCategorias->obtenerListaCategorias();
$todosLosProductos = $crudProductos->obtenerTodosLosProductos();

$mostrarResultadosBusqueda = false;
$textoBusqueda = '';
$resultadosBusqueda = [];

if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $textoBusqueda = $_GET['buscar'];
    $resultadosBusqueda = $crudProductos->buscarProductos($textoBusqueda);
    $mostrarResultadosBusqueda = true;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Menú / Productos</title>
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
                <form action="modificarMenu.php" method="get">
                    <input type="text" name="buscar" placeholder="Buscar productos" value="<?php echo htmlspecialchars($textoBusqueda); ?>">
                    <button class="btn" type="submit">Buscar</button>
                </form>
            </div>
            <div class="menu-categories">
                <?php if (!$mostrarResultadosBusqueda): ?>
                    <button class="btn active" data-categoria="todos">Todos</button>
                    <?php foreach ($categorias as $categoria): ?>
                        <button class="btn" data-categoria="<?php echo htmlspecialchars($categoria->getNombre()); ?>" data-categoria-id="<?php echo $categoria->getIdCategoria(); ?>">
                            <?php echo htmlspecialchars($categoria->getNombre()); ?>
                        </button>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="modificarMenu.php" class="menu-categorias"><button>Todos</button></a>
                <?php endif; ?>
            </div>
            <div class="add-product-btn-container">
                <a href="agregarProducto.php" class="btn btn-add-product">Agregar Producto</a>
            </div>
        </div>

        <div class="menu-container-productos">
            <?php if ($mostrarResultadosBusqueda): ?>
                <div id="resultados-busqueda-section" class="menu-section active-section">
                    <h2>Resultados de Búsqueda para "<?php echo htmlspecialchars($textoBusqueda); ?>"</h2>
                    <div class="menu-items">
                        <?php if (!empty($resultadosBusqueda)): ?>
                            <?php foreach ($resultadosBusqueda as $producto): ?>
                                <?php $crudProductos->mostrarProductoModificar($producto); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No se encontraron productos que coincidan con su búsqueda.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div id="todos-section" class="menu-section active-section">
                    <?php foreach ($categorias as $categoria): ?>
                        <h2><?php echo htmlspecialchars($categoria->getNombre()); ?></h2>
                        <div class="menu-items">
                            <?php foreach ($todosLosProductos as $producto): ?>
                                <?php if ($producto->getIdCategoria() == $categoria->getIdCategoria()): ?>
                                    <?php $crudProductos->mostrarProductoModificar($producto); ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($categorias as $categoria): ?>
                    <div id="<?php echo strtolower(str_replace(' ', '-', $categoria->getNombre())); ?>-section" class="menu-section" style="display: none;">
                        <h2><?php echo htmlspecialchars($categoria->getNombre()); ?></h2>
                        <div class="menu-items">
                            <?php foreach ($todosLosProductos as $producto): ?>
                                <?php if ($producto->getIdCategoria() == $categoria->getIdCategoria()): ?>
                                    <?php $crudProductos->mostrarProductoModificar($producto); ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="../js/menuOpciones.js"></script>
    <script src="../js/menu.js"></script>
</body>
</html>