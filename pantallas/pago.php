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

//Se inicializan las variables necesarias
$idPedido = $_GET['idPedido'] ?? null;
$pedido = null;
$productosPedido = [];
$subtotal = 0;
$nombrePantalla = "Realizar Pago";

// Verificar si se debe aplicar propina (viene por GET)
$aplicarPropina = $_GET['propina'] ?? '0';

// Método de pago seleccionado (viene por GET)
$metodoPagoSeleccionado = $_GET['metodoPago'] ?? '';

// Si hay un ID de pedido, obtiene los detalles y productos del pedido
if ($idPedido) {
    $crudPedidos = new CrudPedidos();
    $pedido = $crudPedidos->mostrarDetalles($idPedido);
    $productosPedido = $crudPedidos->obtenerProductosDePedido($idPedido);

    // Calcular subtotal sumando el precio * cantidad de cada producto
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

// Lógica para determinar el action y método del formulario según el método de pago
$formAction = '../pantallas/procedimientos/adminPago.php';
$formMethod = 'POST';
$buttonText = 'REGISTRAR PAGO';

// Si el método de pago es tarjeta, redirige a la pantalla de tarjeta
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
            <!-- Botón para volver al inicio -->
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">PAGO DE LA ORDEN</div>
        </div>
        <div class="menu-container">
            <!-- Menú de navegación principal, solo para Gerente -->
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
                <h2>No. Pedido: <?php echo $pedido->getIdPedido(); ?></h2>
                <p><strong>Cliente:</strong> <?php echo $pedido->getNombreCliente() . ' ' . $pedido->getApellidoCliente(); ?></p>
                <p><strong>Mesero:</strong> <?php echo $pedido->getNombreEmpleado() . ' ' . $pedido->getApellidoEmpleado(); ?></p>
            </div>

            <div class="productos-list">
                <!-- Lista de productos del pedido -->
                <?php foreach ($productosPedido as $producto): ?>
                    <div class="producto-item">
                        <span class="producto-cantidad"><?php echo $producto->getCantidadProducto(); ?></span>
                        <span class="producto-nombre"><?php echo $producto->getNombreProducto(); ?></span>
                        <span class="producto-linea"></span>
                        <span class="producto-precio">$<?php echo number_format($producto->getPrecioProducto() * $producto->getCantidadProducto(), 2); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="buttons-container">
                <!-- Botón para volver al inicio -->
                <a href="inicio.php" class="btn-volver">VOLVER</a>
             
                <!-- Formulario para registrar el pago o continuar a la pantalla de tarjeta -->
                <form action="<?php echo $formAction; ?>" method="<?php echo $formMethod; ?>" style="flex: 1;">
                    <input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>">
                    <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                    <input type="hidden" name="propinaToggle" value="<?php echo $aplicarPropina; ?>">
                    <input type="hidden" name="metodoPago" value="<?php echo $metodoPagoSeleccionado; ?>">
                    
                    <!-- Si el método es tarjeta, pasa también el monto de propina y total final -->
                    <?php if ($metodoPagoSeleccionado === 'Tarjeta de Credito' || $metodoPagoSeleccionado === 'Tarjeta de Debito'): ?>
                        <input type="hidden" name="propina_monto" value="<?php echo $aplicarPropina == '1' ? $subtotal * 0.10 : 0; ?>">
                        <input type="hidden" name="total_final" value="<?php echo $totalFinal; ?>">
                        <input type="hidden" name="propina" value="<?php echo $aplicarPropina; ?>">
                    <?php endif; ?>

                    <button type="submit" name="accion" value="<?php echo ($metodoPagoSeleccionado === 'Tarjeta de Credito' || $metodoPagoSeleccionado === 'Tarjeta de Debito') ? 'continuar_tarjeta' : 'registrar_pago'; ?>"><?php echo $buttonText; ?></button>
                </form>
            </div>
        </div>

        <!-- Columna derecha: Resumen de pago y selección de método -->
        <div class="right-column">
            <div class="resumen-pago">
                <h3>Resumen de compra</h3>
                
                <div class="resumen-line">
                    <span>Productos</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                
                <div class="propina-line">
                    <!-- Switch para activar/desactivar propina -->
                    <form method="GET" action="pago.php" class="propina-toggle-container">
                        <input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>">
                        <input type="hidden" name="metodoPago" value="<?php echo $metodoPagoSeleccionado; ?>">
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
                    <!-- Selección de método de pago, recarga la página al cambiar -->
                    <form method="GET" action="pago.php" id="metodoPagoForm">
                        <input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>">
                        <input type="hidden" name="propina" value="<?php echo $aplicarPropina; ?>">
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