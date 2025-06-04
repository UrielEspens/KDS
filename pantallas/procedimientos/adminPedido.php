<?php
session_start();

require_once('crudPedidos.php');
require_once('claseDetallesPedido.php');


$crud = new CrudPedidos();

// Se verifica que el carrito no esté vacío y que haya un cliente seleccionado
if (isset($_POST['accionCarrito']) && $_POST['accionCarrito'] == 'guardar_pedido' &&
    !empty($_SESSION['carrito']) && isset($_SESSION['clienteSeleccionado_id'])) {

    $idCliente = $_SESSION['clienteSeleccionado_id'];
    $idEmpleado = $_SESSION['idEmpleado'];

    // Se inserta el pedido general
    $idPedido = $crud->insertarPedido($idCliente, $idEmpleado);

    // Se recorre el carrito para insertar cada producto como un detalle
    foreach ($_SESSION['carrito'] as $producto) {
        $detalle = new DetallesPedido();
        $detalle->setIdPedido($idPedido);
        $detalle->setIdProducto($producto['id']);
        $detalle->setCantidad($producto['cantidad']);
        $detalle->setPrecio($producto['precio']); // precio unitario

        $crud->insertarDetalle($detalle);
    }

    // Limpiar el carrito después de guardar
    unset($_SESSION['carrito']);
    unset($_SESSION['clienteSeleccionado_id']);
    unset($_SESSION['clienteSeleccionado_nombre']);

    // Redireccionar o mostrar mensaje de éxito
    header('Location: carrito.php?mensaje=pedido_guardado');
    exit();
} else {
    echo "No se puede guardar el pedido: faltan datos.";
}

$accion = $_POST['accion'] ?? null; // Se obtiene la acción del formulario
$idPedido = $_POST['idPedido'] ?? null;

if ($accion === 'eliminar_pedido' && $idPedido !== null) {
    // Validar el ID
    $idPedido = (int)$idPedido;

    if ($crud->eliminarPedido($idPedido)) {
        header('Location: ../inicio.php?status=pedido_eliminado');
        exit();
    } else {
        // Redirigir con un mensaje de error
        header('Location: ../inicio.php?error=error_al_eliminar_pedido');
        exit();
    }
} else {
    // Si la acción o ID no son válidos, redirigir a una página de error o al inicio
    header('Location: ../inicio.php?error=accion_invalida');
    exit();
}
?>
