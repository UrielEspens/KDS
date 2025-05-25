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

$accion = $_POST['accion'] ?? null; // Obtén la acción del formulario
$idPedido = $_POST['idPedido'] ?? null;

if ($accion === 'eliminar_pedido' && $idPedido !== null) {
    // Validar y sanear el ID
    $idPedido = (int)$idPedido;

    // Lógica para eliminar el pedido
    // Esto implicaría:
    // 1. Eliminar los artículos asociados a ese pedido en la tabla de detalle (si existe)
    // 2. Eliminar el pedido de la tabla principal de pedidos
    // ¡Asegúrate de que tu método delete de CrudPedidos maneje esto correctamente para evitar datos huérfanos!
    
    // Ejemplo de cómo podría ser en tu CrudPedidos:

    if ($crud->eliminarPedido($idPedido)) {
        // Redirigir a una página de éxito o al historial de pedidos
        header('Location: ../inicio.php?status=pedido_eliminado');
        exit();
    } else {
        // Redirigir con un mensaje de error
        header('Location: ../inicio.php?error=error_al_eliminar_pedido');
        exit();
    }
} else {
    // Acción o ID no válidos, redirigir a una página de error o al inicio
    header('Location: ../inicio.php?error=accion_invalida');
    exit();
}
?>
