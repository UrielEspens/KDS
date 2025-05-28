<?php
session_start();

if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}

require_once('procedimientos/conexion.php');
require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/clasePedido.php');
require_once('procedimientos/claseDetallesPedido.php');
require_once('procedimientos/claseProducto.php');
require_once('procedimientos/claseCliente.php');
require_once('procedimientos/claseEmpleados.php');

$idPedido = $_GET['idPedido'] ?? null;

$detallesPedido = null;
$totalProductos = 0;
$propina = 0;
$totalGeneral = 0;
$porcentajePropina = 0.10;

if ($idPedido) {
    $crudPedidos = new CrudPedidos();
    $detallesPedido = $crudPedidos->obtenerPedidoCompletoParaPago($idPedido);

    if ($detallesPedido) {
        foreach ($detallesPedido->getArticulos() as $articulo) {
            $totalProductos += ($articulo->getPrecioProducto() * $articulo->getCantidadProducto());
        }
        $propina = $totalProductos * $porcentajePropina;
        $totalGeneral = $totalProductos + $propina;
    } else {
        $_SESSION['mensaje_error'] = "Error: Pedido no encontrado o no se pudo cargar.";
        header('Location: inicio.php');
        exit();
    }
} else {
    $_SESSION['mensaje_error'] = "Error: ID de pedido no proporcionado.";
    header('Location: inicio.php');
    exit();
}

$usuarioPuesto = $_SESSION['Cajero'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pago de la Orden</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/pago.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">PAGO DE LA ORDEN</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <?php if ($usuarioPuesto == 'Gerente' || $usuarioPuesto == 'Mesero'): ?>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                    <hr>
                    <?php endif; ?>
                    <?php if ($usuarioPuesto == 'Gerente'): ?>
                    <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
                    <?php endif; ?>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="content-pago">
        <?php if ($detallesPedido): ?>
            <div class="left-panel-pago">
                <div class="order-details-pago">
                    <p><strong>No. Pedido:</strong> <?php echo htmlspecialchars($detallesPedido->getIdPedido()); ?></p>
                    <p><strong>Cliente:</strong> <?php echo htmlspecialchars($detallesPedido->getNombreCliente()); ?></p>
                    <p><strong>Mesero:</strong> <?php echo htmlspecialchars($detallesPedido->getNombreEmpleado()); ?></p>
                </div>

                <div class="item-list-pago">
                    <?php if (!empty($detallesPedido->getArticulos())): ?>
                        <?php foreach ($detallesPedido->getArticulos() as $articulo): ?>
                            <div class="item-pago">
                                <span class="item-quantity-pago"><?php echo htmlspecialchars($articulo->getCantidadProducto()); ?></span>
                                <span class="item-name-pago"><?php echo htmlspecialchars($articulo->getNombreProducto()); ?></span>
                                <span class="item-price-pago">$<?php echo number_format($articulo->getPrecioProducto(), 2); ?></span>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>

            <div class="right-panel-pago">
                <div class="summary-title-pago">Resumen de compra</div>
                <div class="summary-line-pago">
                    <span>Productos:</span>
                    <span>$<?php echo number_format($totalProductos, 2); ?></span>
                </div>
                <div class="summary-line-pago">
                    <span>Propina:</span>
                    <span>$<?php echo number_format($propina, 2); ?></span>
                </div>
                <div class="summary-total-pago">
                    <span>Total:</span>
                    <span>$<?php echo number_format($totalGeneral, 2); ?></span>
                </div>
            </div>
            <div class="footer-buttons-pago">
                <a href="inicio.php" class="btn-volver-pago">VOLVER</a>
                <a href="metodo_pago.php?idPedido=<?php echo htmlspecialchars($idPedido); ?>&total=<?php echo htmlspecialchars($totalGeneral); ?>&propina=<?php echo htmlspecialchars($propina); ?>&productos=<?php echo htmlspecialchars($totalProductos); ?>" class="btn-continuar-pago">CONTINUAR</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="../js/menuOpciones.js"></script>
</body>
</html>