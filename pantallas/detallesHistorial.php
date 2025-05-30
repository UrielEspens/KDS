<?php
session_start();

if (!isset($_SESSION['idEmpleado'])) {
    header('Location: ../index.php');
    exit();
}

require_once('procedimientos/conexion.php');
require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/claseDetallesPedido.php');
require_once('procedimientos/claseProducto.php');

$idPedido = $_GET['idPedido'] ?? null;
$pedidoDetalles = null;
$productosPedido = [];
$totalFinal = 0; // This will be the actual total from historial_pedidos

if ($idPedido) {
    $crudPedidos = new CrudPedidos();
    
    // Get the full historical order details
    // You'll need a new method in CrudPedidos to get details from historial_pedidos
    // including the items from detalles_historial_pedido.
    $pedidoDetalles = $crudPedidos->obtenerDetallesPedidoHistorico($idPedido);

    if ($pedidoDetalles) {
        $productosPedido = $pedidoDetalles->getArticulos(); // Assuming getArticulos() is populated by obtenerDetallesPedidoHistorico
        $totalFinal = $pedidoDetalles->getMontoTotal(); // Assuming montoTotal is set in the returned object
    }
}

$nombrePantalla = "Detalles del Pedido Histórico";

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($nombrePantalla); ?></title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/pago.css"> </head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="historialPedidos.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">DETALLES DEL PEDIDO</div>
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

    <div class="container-pago">
        <div class="left-column">
            <div class="order-summary">
                <h2>Resumen del Pedido</h2>
                <?php if ($pedidoDetalles): ?>
                    <p><strong>ID Pedido:</strong> <?php echo htmlspecialchars($pedidoDetalles->getIdPedido()); ?></p>
                    <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedidoDetalles->getNombreCliente() . ' ' . $pedidoDetalles->getApellidoCliente()); ?></p>
                    <p><strong>Fecha/Hora:</strong> <?php echo htmlspecialchars($pedidoDetalles->getFechaHora()); ?></p>
                    <p><strong>Empleado:</strong> <?php echo htmlspecialchars($pedidoDetalles->getNombreEmpleado() . ' ' . $pedidoDetalles->getApellidoEmpleado()); ?></p>
                    <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($pedidoDetalles->getTipoPago()); ?></p>
                    <p><strong>Estado:</strong> Pagado</p> <?php else: ?>
                    <p>Pedido no encontrado.</p>
                <?php endif; ?>
            </div>

            <div class="product-list">
                <h3>Productos en el Pedido</h3>
                <?php if (!empty($productosPedido)): ?>
                    <ul>
                        <?php foreach ($productosPedido as $producto): ?>
                            <li>
                                <span><?php echo htmlspecialchars($producto->getCantidadProducto()); ?>x</span>
                                <span><?php echo htmlspecialchars($producto->getNombreProducto()); ?></span>
                                <span>$<?php echo number_format($producto->getPrecioProducto(), 2); ?> c/u</span>
                                <span>Total: $<?php echo number_format($producto->getCantidadProducto() * $producto->getPrecioProducto(), 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No hay productos registrados para este pedido histórico.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="right-column">
            <div class="payment-details-summary">
                <h3>Resumen Final</h3>
                <div class="summary-line">
                    <span>Monto Pagado:</span>
                    <span>$<?php echo number_format($totalFinal, 2); ?></span>
                </div>
            </div>
             <div class="buttons-container-pago">
                <a href="historialPedidos.php" class="btn-volver">VOLVER AL HISTORIAL</a>
            </div>
        </div>
    </div>

    <script src="../js/menuOpciones.js"></script>
</body>
</html>