<?php
session_start();

if (!isset($_SESSION['idEmpleado'])) {
    header('Location: ../index.php');
    exit();
}

$usuarioPuesto = $_SESSION['puesto'];

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

$metodoPagoSeleccionado = $_GET['metodoPago'] ?? '';

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

// CAMBIO: Lógica mejorada para determinar el action y método del formulario
$formAction = '../pantallas/procedimientos/adminPago.php';
$formMethod = 'POST';
$buttonText = 'REGISTRAR PAGO';

if ($metodoPagoSeleccionado === 'Tarjeta de Credito' || $metodoPagoSeleccionado === 'Tarjeta de Debito') {
    $formAction = 'tarjeta.php';
    $formMethod = 'GET'; // Para pasar parámetros a tarjeta.php
    $buttonText = 'CONTINUAR';
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
                    <?php if ($usuarioPuesto == 'Gerente'): ?>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu"> Ver menú</a></li>
                    <hr>
                    <li><a href="historialPedidos.php"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
                    <?php endif; ?>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
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
                        <span class="producto-precio">$<?php echo number_format($producto->getPrecioProducto() * $producto->getCantidadProducto(), 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="buttons-container">
                <a href="inicio.php" class="btn-volver">VOLVER</a>
             
                <form action="<?php echo htmlspecialchars($formAction); ?>" method="<?php echo htmlspecialchars($formMethod); ?>" style="flex: 1;">
                    <input type="hidden" name="idPedido" value="<?php echo htmlspecialchars($idPedido); ?>">
                    <input type="hidden" name="subtotal" value="<?php echo htmlspecialchars($subtotal); ?>">
                    <input type="hidden" name="propinaToggle" value="<?php echo htmlspecialchars($aplicarPropina); ?>">
                    <input type="hidden" name="metodoPago" value="<?php echo htmlspecialchars($metodoPagoSeleccionado); ?>">
                    
                   
                    <?php if ($metodoPagoSeleccionado === 'Tarjeta de Credito' || $metodoPagoSeleccionado === 'Tarjeta de Debito'): ?>
                        <input type="hidden" name="propina_monto" value="<?php echo htmlspecialchars($aplicarPropina == '1' ? $subtotal * 0.10 : 0); ?>">
                        <input type="hidden" name="total_final" value="<?php echo htmlspecialchars($totalFinal); ?>">
                        <input type="hidden" name="propina" value="<?php echo htmlspecialchars($aplicarPropina); ?>">
                    <?php endif; ?>

                    <button type="submit" name="accion" value="<?php echo ($metodoPagoSeleccionado === 'Tarjeta de Credito' || $metodoPagoSeleccionado === 'Tarjeta de Debito') ? 'continuar_tarjeta' : 'registrar_pago'; ?>"><?php echo htmlspecialchars($buttonText); ?></button>
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
                        <input type="hidden" name="metodoPago" value="<?php echo htmlspecialchars($metodoPagoSeleccionado); ?>">
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
                    <form method="GET" action="pago.php" id="metodoPagoForm">
                        <input type="hidden" name="idPedido" value="<?php echo htmlspecialchars($idPedido); ?>">
                        <input type="hidden" name="propina" value="<?php echo htmlspecialchars($aplicarPropina); ?>">
                        <div class="form-group">
                            <select id="metodoPago" name="metodoPago" required onchange="this.form.submit()">
                                <option value="">Selecciona un método</option>
                                <option value="Efectivo" <?php echo ($metodoPagoSeleccionado == 'Efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                                <option value="Tarjeta de Credito" <?php echo ($metodoPagoSeleccionado == 'Tarjeta de Credito') ? 'selected' : ''; ?>>Tarjeta de Crédito</option>
                                <option value="Tarjeta de Debito" <?php echo ($metodoPagoSeleccionado == 'Tarjeta de Debito') ? 'selected' : ''; ?>>Tarjeta de Débito</option>
                                <option value="Transferencia" <?php echo ($metodoPagoSeleccionado == 'Transferencia') ? 'selected' : ''; ?>>Transferencia</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/menuOpciones.js"></script>
</body>
</html>