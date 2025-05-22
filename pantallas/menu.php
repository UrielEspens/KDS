<?php
session_start();

require_once('procedimientos/conexion.php'); //Conexión
require_once('procedimientos/crudProductos.php'); // Crud para la tabla producto
require_once('procedimientos/claseInfoProducto.php'); // Clase para los objetos InfoProducto
require_once('procedimientos/claseCategoria.php'); // Clase para los objetos Categoria

require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/clasePedido.php');

$crud = new CrudPedidos();
$pedido = new Producto();
$listaPedidos = $crud->mostrarPedidos();
$crudProductos = new CrudProductos();

$resultadosBusqueda = [];
$mostrarResultadosBusqueda = false; // Para saber si se hizo una búsqueda

// Lógica de búsqueda
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $textoBusqueda = $_GET['buscar'];
    $tipoBusqueda = 'inicio'; // Fijo a 'inicio'
    
    // Llama al método de búsqueda del CrudProductos
    $resultadosBusqueda = $crudProductos->buscarProductos($textoBusqueda, $tipoBusqueda);
    $mostrarResultadosBusqueda = true;
}

// Obtener todas las categorías y productos si no hay búsqueda activa o para la vista inicial
if (!$mostrarResultadosBusqueda) {
    $categorias = $crudProductos->obtenerCategorias();
    $todosLosProductos = $crudProductos->obtenerTodosLosProductos();
}


function mostrarProducto(InfoProducto $producto) {
    echo '<div class="menu-item" data-product-id="' . ($producto->getIdProducto()) . '" data-categoria-id="' . ($producto->getIdCategoria()) . '">';
    echo '<img src="' . ($producto->getImagen()) . '" alt="' . ($producto->getNombre()) . '">';
    echo '<div class="item-details">';
    echo '<h3>' . ($producto->getNombre()) . '</h3>';
    echo '<p>' . ($producto->getDescripcion()) . '</p>';
    echo '<p class="item-price">$' . number_format($producto->getPrecio(), 2) . ' MXN</p>';
    echo '</div>';
    
    echo '<form action="carrito.php?idProducto=<?php echo $pedido->getIdPedido(); ?>" method="GET" style="display: inline-block;">';
    echo '<input type="hidden" name="accionCarrito" value="add">';
    echo '<input type="hidden" name="idProducto" value="' . ($producto->getIdProducto()) . '">';
    echo '<button type="submit" class="add-buttons">+</button>';
    echo '</form>';
    echo '</div>';
}
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
                    <button type="submit" value="inicio">Buscar</button>
                </form>
            </div>
            <div class="menu-categories">
                <?php if (!$mostrarResultadosBusqueda): ?>
                    <button class="active" data-categoria="todos">Todos</button>
                    <?php foreach ($categorias as $categoria): ?>
                        <button data-categoria="<?php echo $categoria->getNombre(); ?>" data-categoria-id="<?php echo $categoria->getIdCategoria(); ?>">
                            <?php echo $categoria->getNombre(); ?>
                        </button>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="menu.php" class="menu-categorias"><button>Todos</button></a>
                <?php endif; ?>
            </div>
        </div>

        <div class="menu-container-productos">
            <?php if ($mostrarResultadosBusqueda): ?>
                <div id="resultados-busqueda-section" class="menu-section active-section">
                    <h2>Resultados de Búsqueda para "<?php echo ($textoBusqueda); ?>"</h2>
                    <div class="menu-items">
                        <?php if (!empty($resultadosBusqueda)): ?>
                            <?php foreach ($resultadosBusqueda as $producto): ?>
                                <?php mostrarProducto($producto); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No se encontraron productos que coincidan con su búsqueda.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div id="todos-section" class="menu-section active-section">
                    <?php foreach ($categorias as $categoria): ?>
                        <h2><?php echo ($categoria->getNombre()); ?></h2>
                        <div class="menu-items">
                            <?php foreach ($todosLosProductos as $producto): ?>
                                <?php if ($producto->getIdCategoria() == $categoria->getIdCategoria()): ?>
                                    <?php mostrarProducto($producto); ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($categorias as $categoria): ?>
                    <div id="<?php echo strtolower(str_replace(' ', '-', $categoria->getNombre())); ?>-section" class="menu-section" style="display: none;">
                        <h2><?php echo ($categoria->getNombre()); ?></h2>
                        <div class="menu-items">
                            <?php foreach ($todosLosProductos as $producto): ?>
                                <?php if ($producto->getIdCategoria() == $categoria->getIdCategoria()): ?>
                                    <?php mostrarProducto($producto); ?>
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