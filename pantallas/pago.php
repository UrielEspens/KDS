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

if ($idPedido) {
    $crudPedidos = new CrudPedidos();
    $pedido = $crudPedidos->mostrarDetalles($idPedido);
    $productosPedido = $crudPedidos->obtenerProductosDePedido($idPedido);

    // Calcular subtotal
    foreach ($productosPedido as $producto) {
        $subtotal += $producto->getPrecioProducto() * $producto->getCantidadProducto();
    }
    
    // Calcular propina potencial en PHP (no se suma al total aqui todavia)
    $propinaPotencial = $subtotal * 0.10;
    
    // El total a pagar que se MUESTRA en esta pantalla es siempre el subtotal
    // La propina se sumará al total en adminPago.php si se selecciona
    $totalPagarDisplay = $subtotal;

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
            <a href="../inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">Pago</div>
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
        <h2>Resumen del Pedido #<?php echo htmlspecialchars($pedido->getIdPedido()); ?></h2>
        <div class="pedido-info">
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido->getNombreCliente() . ' ' . $pedido->getApellidoCliente()); ?></p>
            <p><strong>Mesero:</strong> <?php echo htmlspecialchars($pedido->getNombreEmpleado() . ' ' . $pedido->getApellidoEmpleado()); ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido->getEstado()); ?></p>
            <p><strong>Hora de Creación:</strong> <?php echo htmlspecialchars($pedido->getHoraCreacion()); ?></p>
        </div>

        <h3>Artículos del Pedido:</h3>
        <table class="productos-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productosPedido as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto->getNombreProducto()); ?></td>
                        <td><?php echo htmlspecialchars($producto->getCantidadProducto()); ?></td>
                        <td>$<?php echo number_format($producto->getPrecioProducto(), 2); ?></td>
                        <td>$<?php echo number_format($producto->getPrecioProducto() * $producto->getCantidadProducto(), 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="resumen-pago">
            <p><strong>Subtotal:</strong> <span>$<?php echo number_format($subtotal, 2); ?></span></p>

            <div class="form-group propina-toggle-container">
                <label for="propinaToggle">Agregar Propina (10%):</label>
                <label class="switch">
                    <input type="checkbox" id="propinaToggle" name="propinaToggle" value="1">
                    <span class="slider round"></span>
                </label>
                <span id="propinaAmountDisplay">($<?php echo number_format($propinaPotencial, 2); ?>)</span>
            </div>

            <p><strong>Total a Pagar:</strong> <span>$<?php echo number_format($totalPagarDisplay, 2); ?></span></p>
        </div>

        <form action="../pantallas/procedimientos/adminPago.php" method="POST" class="form-pago">
            <input type="hidden" name="idPedido" value="<?php echo htmlspecialchars($idPedido); ?>">
            <input type="hidden" name="subtotal" value="<?php echo htmlspecialchars($subtotal); ?>">
            <h3>Detalles de Pago</h3>
            <div class="form-group">
                <label for="metodoPago">Método de Pago:</label>
                <select id="metodoPago" name="metodoPago" required>
                    <option value="">Seleccione un método</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta de Credito">Tarjeta de Crédito</option>
                    <option value="Tarjeta de Debito">Tarjeta de Débito</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>

            <button type="submit" name="accion" value="registrar_pago">Registrar Pago</button>
        </form>
    </div>

    <script src="../js/menuOpciones.js"></script>
    </body>
</html>