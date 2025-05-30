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

if (isset($_POST['accion']) && $_POST['accion'] === 'registrar_pago_tarjeta') {
    $idPedido = $_POST['idPedido'] ?? null;
    $metodoPago = 'Tarjeta de Credito'; // Por simplicidad, se asume que ambos tipos de tarjeta caen aquí
    
    // Recuperar el total final que viene de los campos ocultos de tarjeta.php
    $totalFinal = $_POST['total_final'] ?? null; 
    
    // Detalles de la tarjeta (aunque no se almacenarán en la DB de pagos, se pueden usar para validación o un log)
    $cardNumber = $_POST['cardNumber'] ?? '';
    $cardName = $_POST['cardName'] ?? '';
    $expiryMonth = $_POST['expiryMonth'] ?? '';
    $expiryYear = $_POST['expiryYear'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    $idEmpleado = $_SESSION['idEmpleado'];

    // --- Validación de datos de la tarjeta (lado del servidor) ---
    $errors = [];

    if (!$idPedido || !is_numeric($totalFinal)) {
        $errors[] = "Datos del pedido incompletos o inválidos.";
    }

    // Validación básica del número de tarjeta (solo dígitos, longitud)
    $cleanCardNumber = preg_replace('/\s+/', '', $cardNumber); // Eliminar espacios
    if (!preg_match('/^\d{13,19}$/', $cleanCardNumber)) {
        $errors[] = "Número de tarjeta inválido. Debe contener entre 13 y 19 dígitos.";
    }

    // Validación del nombre
    if (empty(trim($cardName))) {
        $errors[] = "El nombre del titular de la tarjeta es requerido.";
    }

    // Validación de la fecha de vencimiento
    $month = (int)$expiryMonth;
    $year = (int)$expiryYear;
    $currentYear = (int)date('y'); // Últimos dos dígitos del año actual
    $currentMonth = (int)date('m');

    if ($month < 1 || $month > 12) {
        $errors[] = "Mes de vencimiento inválido (MM).";
    }
    // Asumimos que el año de la tarjeta es en formato AA (ej. 25 para 2025)
    else if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
        $errors[] = "La tarjeta ha expirado.";
    }

    // Validación del CVV
    if (!preg_match('/^\d{3,4}$/', $cvv)) {
        $errors[] = "Código de seguridad (CVV) inválido. Debe tener 3 o 4 dígitos.";
    }

    if (!empty($errors)) {
        // Redirigir de vuelta a tarjeta.php con mensajes de error (simple, o podrías usar sesión para errores)
        $errorString = implode('<br>', $errors); // Une los errores para mostrar
        error_log("Errores de validación en procesar_tarjeta.php: " . $errorString);
        // Para una UX mejor, podrías pasar los errores de vuelta a tarjeta.php y rellenar los campos
        header('Location: ../pantallas/tarjeta.php?idPedido=' . urlencode($idPedido) . '&error=' . urlencode("Por favor, corrige los siguientes errores:<br>" . $errorString) .
               '&subtotal=' . urlencode($_POST['subtotal']) . '&propina_monto=' . urlencode($_POST['propina_monto']) .
               '&total_final=' . urlencode($_POST['total_final']) . '&propina=' . urlencode($_POST['propina']));
        exit();
    }
    // --- Fin Validación ---


    $crudPedidos = new CrudPedidos();
    $crudPago = new CrudPago();

    // Crear objeto Pago e insertar
    $pago = new Pago();
    $pago->setIdPedido($idPedido);
    $pago->setIdEmpleado($idEmpleado);
    $pago->setMetodoPago($metodoPago);
    $pago->setMontoPagado($totalFinal); // Usar el total final recibido

    if ($crudPago->insertarPago($pago)) {
        // Actualizar estado del pedido a 'Pagado'
        if ($crudPedidos->actualizarEstadoPedido($idPedido, 'Pagado')) {
            header('Location: ../inicio.php?mensaje=pago_tarjeta_registrado_exito');
            exit();
        } else {
            error_log("Error en procesar_tarjeta.php: No se pudo actualizar el estado del pedido $idPedido a 'Pagado'.");
            header('Location: ../inicio.php?error=actualizar_estado_fallido_tarjeta');
            exit();
        }
    } else {
        error_log("Error en procesar_tarjeta.php: No se pudo insertar el pago con tarjeta para el pedido $idPedido.");
        header('Location: ../inicio.php?error=registro_pago_fallido_tarjeta');
        exit();
    }
} else {
    header('Location: ../inicio.php'); // Redirigir si se accede directamente sin POST
    exit();
}