<?php
// ¡Se recomienda renombrar este archivo a detallesPedido.php!
require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/claseDetallesPedido.php');
require_once('procedimientos/conexion.php');
require_once('procedimientos/claseProducto.php');

$crud = new CrudPedidos();
$pedido = new DetallesPedido();
$idPedido = null;

// Validar si el ID existe y es un número entero
if (isset($_GET['idPedido']) && is_numeric($_GET['idPedido'])) {
    $idPedido = $_GET['idPedido']; 

    // Obtener los detalles del pedido DE LA BASE DE DATOS
    // El método mostrarDetalles ahora devuelve un objeto DetallesPedido (o null/false)
    $pedido = $crud->mostrarDetalles($idPedido);
    
    $productosDelPedido = $crud->obtenerProductosDePedido($idPedido);
    $pedido->setArticulos($productosDelPedido);

    // Verifica si se encontró el objeto DetallesPedido
    if (!$pedido) { // Si $pedido es null (no se encontró en DB)
        echo "<p>El pedido con ID <strong>" . htmlspecialchars($idPedido) . "</strong> no fue encontrado.</p>";
    }

} else {
    // ID no proporcionado o inválido, manejar el error
    echo "<p>ID de pedido no proporcionado o inválido.</p>";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/inicio.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">DETALLES DE PEDIDO: <?php echo $pedido->getIdPedido(); ?></div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
            <ul>
                <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                <hr>
                <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                <hr>
                <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
            </ul>
            </div>
        </div>
        <script src="../js/menuOpciones.js"></script>
    </div>

    <div class="fondoDifuminado" id="fondoDifuminado">
        <div id="contenedorDetallesPedido">
            <?php if ($pedido): ?>
                <div class="detalles-header header-<?php echo strtolower($pedido->getEstado()); ?>" id="estado-header">
                    <h3 id="pedido-estado-detalle"><?php echo strtoupper($pedido->getEstado()); ?></h3>
                    <div id="pedido-hora-detalle"><?php echo (new DateTime($pedido->getHoraCreacion()))->format('H:i'); ?></div>
                </div>

                <div class="info-pedido">
                    <p><strong id="pedido-numero-detalle">No. Pedido: </strong><?php echo $pedido->getIdPedido(); ?></p>
                    <p><strong id="pedido-cliente-detalle">Cliente: </strong><?php echo $pedido->getNombreCliente() . ' ' . $pedido->getApellidoCliente(); ?></p>
                    <p><strong id="pedido-mesero-detalle">Mesero: </strong><?php echo $pedido->getNombreEmpleado() . ' ' . $pedido->getApellidoEmpleado(); ?></p>
                </div>

                <textarea class="lista-articulos" id="lista-articulos-detalle" rows="10" cols="57" readonly><?php
                    $listaArticulosTexto = "";
                    $totalCalculadoEnBucle = 0.00;

                    if (is_array($pedido->getArticulos())) {
                        foreach ($pedido->getArticulos() as $articuloObjeto) {
                            $cantidad = $articuloObjeto->getCantidadProducto();
                            $nombre = $articuloObjeto->getNombreProducto();
                            $precio = $articuloObjeto->getPrecioProducto();

                            $totalPorArticulo = $cantidad * $precio;
                            $totalCalculadoEnBucle += $totalPorArticulo; 

                            $listaArticulosTexto .= $cantidad. '    ' . $nombre . '  -  $' . number_format($totalPorArticulo, 2) . "\n";
                        }
                    } else {
                        $listaArticulosTexto = "No hay artículos en este pedido.";
                    }
                    echo trim($listaArticulosTexto);
                ?></textarea>

                <?php
                    $pedido->setTotal($totalCalculadoEnBucle);
                ?>

                <div class="total-pedido" id="total-pedido-detalle">Total: $<?php echo (number_format($pedido->getTotal(), 2)); ?></div>

                <div class="botones-accion">
                    <a href="inicio.php"><button id="cerrarDetalles" class="boton-volver">VOLVER</button></a>
                    <div class="botones-iconos">
                        <!-- >
                        <button class="editarDetalles">
                            <img src="../img/inicio/lapiz.png" alt="Editar" width="24px">
                        </button> 
                        -->
                        <a href="confirmarEliminarPedido.php?idPedido=<?php echo $idPedido; ?>&total=<?php echo number_format($pedido->getTotal(), 2); ?>">
                            <button class="eliminarDetalles">
                                <img src="../img/inicio/basurero.png" alt="Eliminar" width="24px">
                            </button>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body> 
</html>