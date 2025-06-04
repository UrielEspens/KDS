<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: ../index.php');
    exit();
}

// Se obtiene el puesto del usuario desde la sesión
$usuarioPuesto = $_SESSION['puesto'];
require_once('procedimientos/conexion.php');
require_once('procedimientos/crudPagos.php');
require_once('procedimientos/clasePago.php');
require_once('procedimientos/crudPedidos.php');

// Se inicializan las variables necesarias y se obtienen los datos del pedido meinte GET
$idPedido = $_GET['idPedido'] ?? null;
$productos_subtotal = floatval($_GET['subtotal'] ?? 0.00);
$propina_monto = floatval($_GET['propina_monto'] ?? 0.00);
$total_a_pagar = floatval($_GET['total_final'] ?? 0.00);
$aplicarPropina = $_GET['propina'] ?? '0';
$metodoPago = $_GET['metodoPago'] ?? '';

$nombrePantalla = "Datos de la Tarjeta";

// Redirigir si faltan datos 
if (!$idPedido) {
    header('Location: ../inicio.php');
    exit();
}

// Si el formulario fue enviado (POST), procesa el pago
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $crudPago = new CrudPago();
    $crudPedidos = new CrudPedidos();
    
    // Crea el objeto Pago y lo llena con los datos del formulario y sesión
    $pago = new Pago();
    $pago->setIdPedido($idPedido);
    $pago->setIdEmpleado($_SESSION['idEmpleado']);
    $pago->setMetodoPago($metodoPago);
    $pago->setMontoPagado($total_a_pagar);
    
    // Intenta registrar el pago
    if ($crudPago->insertarPago($pago)) {
        // Si el pago se registra, mueve el pedido al historial
        if ($crudPedidos->moverPedidoAHistorialNuevo( 
            $idPedido, 
            $metodoPago, 
            $total_a_pagar
        )) {
            // Elimina el pedido activo después de moverlo al historial
            if ($crudPedidos->eliminarPedidoCompletoNuevo($idPedido)) {
                header('Location: inicio.php');
                exit();
            } else {
                $error = "Error al eliminar el pedido activo después de moverlo al historial";
                error_log("Error al eliminar pedido activo en tarjeta.php: " . $idPedido);
            }
        } else {
            $error = "Error al mover el pedido al historial";
            error_log("Error al mover pedido a historial en tarjeta.php: " . $idPedido);
        }
    } else {
        $error = "Error al registrar el pago";
        error_log("Error al registrar pago en tarjeta.php: " . $idPedido);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $nombrePantalla; ?></title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/tarjeta.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <!-- Botón para volver al inicio -->
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">DATOS DE LA TARJETA</div>
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

    <div class="container-tarjeta">
        <div class="left-column-tarjeta">
            <!-- Formulario para ingresar los datos de la tarjeta -->
            <form id="cardForm" class="card-form" method="POST">
                <input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>">
                <input type="hidden" name="subtotal" value="<?php echo $productos_subtotal; ?>">
                <input type="hidden" name="propinaToggle" value="<?php echo $aplicarPropina; ?>">
                <input type="hidden" name="metodoPago" value="<?php echo $metodoPago; ?>">
                
                <div class="form-group">
                    <label for="cardNumber">Número de tarjeta</label>
                    <input type="text" id="cardNumber" name="cardNumber" placeholder="XXXX XXXX XXXX XXXX" maxlength="16" pattern="[0-9\s]*" inputmode="numeric" required>
                </div>

                <div class="form-group">
                    <label for="cardName">Nombre y apellido</label>
                    <input type="text" id="cardName" name="cardName" placeholder="Como figura en la tarjeta" required>
                </div>

                <div class="form-row">
                    <div class="form-group expiration-group">
                        <label for="expiryMonth">Fecha de vencimiento</label>
                        <div class="expiry-inputs">
                            <input type="text" id="expiryMonth" name="expiryMonth" placeholder="MM" maxlength="2" pattern="[0-9]*" inputmode="numeric" required class="short-input">
                            <span class="separator">/</span>
                            <input type="text" id="expiryYear" name="expiryYear" placeholder="AA" maxlength="2" pattern="[0-9]*" inputmode="numeric" required class="short-input">
                        </div>
                        <p class="hint">Mes / Año</p>
                    </div>

                    <div class="form-group cvv-group">
                        <label for="cvv">Código de seguridad</label>
                        <input type="text" id="cvv" name="cvv" placeholder="CVV" maxlength="3" pattern="[0-9]*" inputmode="numeric" required>
                    </div>
                </div>

                <!-- Muestra mensaje de error si ocurre algún problema en el proceso -->
                <?php if (isset($error)): ?>
                    <div class="error-message">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="buttons-container-tarjeta">
                    <!-- Botón para volver a la pantalla de pago -->
                    <button type="button" class="btn-volver" onclick="window.location.href='pago.php?idPedido=<?php echo $idPedido; ?>&propina=<?php echo $aplicarPropina; ?>&metodoPago=<?php echo $metodoPago; ?>'">VOLVER</button>
                    <!-- Botón para registrar el pago -->
                    <button type="submit" class="btn-registrar" name="accion" value="registrar_pago">REGISTRAR PAGO</button>
                </div>
            </form>
        </div>

        <div class="right-column-tarjeta">
            <div class="resumen-compra-tarjeta">
                <h3>Resumen de compra</h3>
                
                <div class="resumen-items">
                    <div class="resumen-line">
                        <span>Productos</span>
                        <span>$<?php echo number_format($productos_subtotal, 2); ?></span>
                    </div>
                    <div class="resumen-line">
                        <span>Propina</span>
                        <span>$<?php echo number_format($propina_monto, 2); ?></span>
                    </div>
                </div>
                
                <div class="total-section">
                    <div class="total-line-tarjeta">
                        <span>Total a pagar:</span>
                        <span>$<?php echo number_format($total_a_pagar, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/menuOpciones.js"></script>
</body>
</html>