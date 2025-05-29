<?php
session_start();

if (!isset($_SESSION['idEmpleado'])) {
    header('Location: ../index.php');
    exit();
}

require_once('conexion.php');
require_once('crudPagos.php');
require_once('clasePago.php');
require_once('crudPedidos.php');

if (isset($_POST['accion']) && $_POST['accion'] === 'registrar_pago') {
    $idPedido = $_POST['idPedido'] ?? null;
    $metodoPago = $_POST['metodoPago'] ?? null;
    $subtotalForm = $_POST['subtotal'] ?? null;
    
    // Obtener el idEmpleado de la sesión
    $idEmpleado = $_SESSION['idEmpleado'];

    $propinaAplicadaCheckbox = $_POST['propinaToggle'] ?? null;    

    if (!$idPedido || !$metodoPago || !is_numeric($subtotalForm)) {
        header('Location: ../inicio.php'); // Redirigir a inicio.php en caso de datos incompletos
        exit();
    }

    $crudPedidos = new CrudPedidos();
    $crudPago = new CrudPago();

    $pedidoDetalles = $crudPedidos->mostrarDetalles($idPedido);
    $productosPedido = $crudPedidos->obtenerProductosDePedido($idPedido);

    if (!$pedidoDetalles) {
        header('Location: ../inicio.php'); // Redirigir si el pedido no se encuentra
        exit();
    }

    $calculatedSubtotal = 0;
    foreach ($productosPedido as $producto) {
        $calculatedSubtotal += $producto->getPrecioProducto() * $producto->getCantidadProducto();
    }

    // Calcular el total final incluyendo la propina si el checkbox estaba marcado
    $totalFinalCalculado = $calculatedSubtotal;
    if ($propinaAplicadaCheckbox === '1') {
        $totalFinalCalculado += $calculatedSubtotal * 0.10; // Añadir 10% de propina
    }

    // Crear objeto Pago e insertar
    $pago = new Pago();
    $pago->setIdPedido($idPedido);
    $pago->setIdEmpleado($idEmpleado);
    $pago->setMetodoPago($metodoPago);
    $pago->setMontoPagado($totalFinalCalculado); // Almacenar el total final calculado en montoPagado

    if ($crudPago->insertarPago($pago)) {
        // Actualizar estado del pedido a 'Pagado'
        if ($crudPedidos->actualizarEstadoPedido($idPedido, 'Pagado')) {
            header('Location: ../inicio.php');
            exit();
        }
    } else {
        error_log("Error: Fallo al registrar el pago del pedido #{$idPedido}.");
        header('Location: ../inicio.php');
        exit();
    }
} else {
    header('Location: ../inicio.php'); // Redirigir si la acción no es válida
    exit();
}
?>