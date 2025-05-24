<?php
session_start();

require_once('crudPedidos.php');
require_once('claseDetallesPedido.php');

$crud = new CrudPedidos();

// Verificamos que el carrito no esté vacío y que haya un cliente seleccionado
if (isset($_POST['accionCarrito']) && $_POST['accionCarrito'] == 'guardar_pedido' &&
    !empty($_SESSION['carrito']) && isset($_SESSION['clienteSeleccionado_id'])) {

    $idCliente = $_SESSION['clienteSeleccionado_id'];
    $idEmpleado = $_SESSION['idEmpleado'];

    // Primero, insertamos el pedido general
    $idPedido = $crud->insertarPedido($idCliente, $idEmpleado);

    // Luego, recorremos el carrito para insertar cada producto como un detalle
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
?>
