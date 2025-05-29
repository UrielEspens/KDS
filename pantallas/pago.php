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
$pedido = null;
$productosPedido = [];
$subtotal = 0;
$nombrePantalla = "Realizar Pago";

// Verificar si se debe aplicar propina
$aplicarPropina = $_GET['propina'] ?? '0';

if ($idPedido) {
    $crudPedidos = new CrudPedidos();
    $pedido = $crudPedidos->mostrarDetalles($idPedido);
    $productosPedido = $crudPedidos->obtenerProductosDePedido($idPedido);

    // Calcular subtotal
    foreach ($productosPedido as $producto) {
        $subtotal += $producto->getPrecioProducto() * $producto->getCantidadProducto();
    }
    
    // Calcular total final (con o sin propina)
    $totalFinal = $subtotal;
    if ($aplicarPropina == '1') {
        $totalFinal += $subtotal * 0.10; // Agregar 10% de propina
    }

} else {
    header('Location: ../inicio.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pago</title>
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
                    <li><a href="../menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu"> Ver menú</a></li>
                    <hr>
                    <li><a href="../historialPedidos.php"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
                    <li><a href="../configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="left-column">
            <div class="pedido-info">
                <h2>No. Pedido: <?php echo htmlspecialchars($pedido->getIdPedido()); ?></h2>
                <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido->getNombreCliente() . ' ' . $pedido->getApellidoCliente()); ?></p>
                <p><strong>Mesero:</strong> <?php echo htmlspecialchars($pedido->getNombreEmpleado() . ' ' . $pedido->getApellidoEmpleado()); ?></p>
            </div>

            <div class="productos-list">
                <?php foreach ($productosPedido as $producto): ?>
                    <div class="producto-item">
                        <span class="producto-cantidad"><?php echo htmlspecialchars($producto->getCantidadProducto()); ?></span>
                        <span class="producto-nombre"><?php echo htmlspecialchars($producto->getNombreProducto()); ?></span>
                        <span class="producto-linea"></span>
                        <span class="producto-precio">$<?php echo number_format($producto->getPrecioProducto(), 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="buttons-container">
                <a href="../inicio.php" class="btn-volver">VOLVER</a>
                <form action="../pantallas/procedimientos/adminPago.php" method="POST" style="flex: 1;">
                    <input type="hidden" name="idPedido" value="<?php echo htmlspecialchars($idPedido); ?>">
                    <input type="hidden" name="subtotal" value="<?php echo htmlspecialchars($subtotal); ?>">
                    <input type="hidden" name="propinaToggle" value="<?php echo htmlspecialchars($aplicarPropina); ?>">
                    <button type="submit" name="accion" value="registrar_pago">CONTINUAR</button>
                </form>
            </div>
        </div>

        <div class="right-column">
            <div class="resumen-pago">
                <h3>Resumen de compra</h3>
                
                <div class="resumen-line">
                    <span>Productos</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                
                <div class="propina-line">
                    <form method="GET" action="pago.php" class="propina-toggle-container">
                        <input type="hidden" name="idPedido" value="<?php echo htmlspecialchars($idPedido); ?>">
                        <label for="propinaToggle">Propina</label>
                        <label class="switch">
                            <input type="checkbox" id="propinaToggle" name="propina" value="1" <?php echo ($aplicarPropina == '1') ? 'checked' : ''; ?> onchange="this.form.submit()">
                            <span class="slider"></span>
                        </label>
                    </form>
                    <span id="propinaAmountDisplay">$<?php echo number_format($aplicarPropina == '1' ? $subtotal * 0.10 : 0, 2); ?></span>
                </div>
                
                <div class="total-line">
                    <span>Total a pagar:</span>
                    <span>$<?php echo number_format($totalFinal, 2); ?></span>
                </div>
                
                <div class="detalles-pago">
                    <h3>Detalles del pago</h3>
                    <div class="form-group">
                        <select id="metodoPago" name="metodoPago" required>
                            <option value="">Selecciona un método</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta de Credito">Tarjeta de Crédito</option>
                            <option value="Tarjeta de Debito">Tarjeta de Débito</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/menuOpciones.js"></script>
</body>
</html>