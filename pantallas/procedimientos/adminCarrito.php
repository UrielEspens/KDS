<?php
session_start();

// Redireccionar si el usuario no está logueado
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php'); // Ajusta la ruta a tu página de inicio de sesión
    exit();
}

require_once('conexion.php');
require_once('crudProductos.php');
require_once('claseInfoProducto.php');
require_once('crudCarrito.php'); // ¡Ahora incluimos el nuevo CrudCarrito!
require_once('crudPedidos.php'); // Mantenemos CrudPedidos para la lógica de guardar el pedido final

// Inicializa el carrito en la sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// --- OBTENER/CREAR EL ID DEL CARRITO PERSISTENTE ---
// Esto es CRUCIAL para que adminCarrito.php sepa qué carrito de la DB manipular.
// Si no está en sesión (ej. primera vez en adminCarrito después de login), lo busca/crea.

$crudProductos = new CrudProductos();
$crudCarrito = new CrudCarrito(); // Instanciamos CrudCarrito
$crudPedidos = new CrudPedidos(); // Para la operación de guardar el pedido

$idEmpleado = $_SESSION['idEmpleado'];

// Obtener el ID del carrito persistente de la sesión (establecido en el login)
// Si por alguna razón no está (ej. directamente a adminCarrito.php sin login previo o sesión expirada)
// lo intentamos obtener de la DB o lo creamos.
if (!isset($_SESSION['idCarritoDB'])) {
    $idCarritoDB = $crudCarrito->obtenerIdCarritoActivoPorEmpleado($idEmpleado);
    if (!$idCarritoDB) {
        $idCarritoDB = $crudCarrito->crearCarritoParaEmpleado($idEmpleado);
    }
    $_SESSION['idCarritoDB'] = $idCarritoDB; // Guarda el ID del carrito persistente en sesión
} else {
    $idCarritoDB = $_SESSION['idCarritoDB'];
}

// Verifica si se recibió una acción para el carrito
if (isset($_POST['accionCarrito'])) {
    $accion = $_POST['accionCarrito'];
    $idProducto = isset($_POST['idProducto']) ? (int)$_POST['idProducto'] : 0;

    // Lógica para añadir producto al carrito
    if ($accion === 'add' && $idProducto > 0) {
        $productoEncontradoEnCarritoSesion = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] === $idProducto) {
                $item['cantidad']++;
                $productoEncontradoEnCarritoSesion = true;
                // Actualizar en la DB usando CrudCarrito
                $crudCarrito->actualizarCantidadItemCarritoDB($idCarritoDB, $idProducto, $item['cantidad']);
                break;
            }
        }
        unset($item); // Rompe la referencia

        if (!$productoEncontradoEnCarritoSesion) {
            $productoObtenido = $crudProductos->obtenerProductoPorId($idProducto);
            if ($productoObtenido instanceof InfoProducto) { // Asumiendo que obtenerProductoPorId devuelve InfoProducto
                $_SESSION['carrito'][] = [
                    'id'       => $productoObtenido->getIdProducto(),
                    'nombre'   => $productoObtenido->getNombre(),
                    'precio'   => $productoObtenido->getPrecio(),
                    'cantidad' => 1
                ];
                // Añadir a la DB usando CrudCarrito
                $crudCarrito->agregarOActualizarItemEnCarritoDB(
                    $idCarritoDB,
                    $productoObtenido->getIdProducto(),
                    1,
                    $productoObtenido->getPrecio()
                );
            } else {
                error_log("ERROR: Producto con ID " . $idProducto . " no encontrado en la base de datos al intentar añadir al carrito.");
            }
        }
    }
    // Lógica para aumentar cantidad
    elseif ($accion === 'increase' && $idProducto > 0) {
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['id'] === $idProducto) {
                $item['cantidad']++;
                // Actualizar en la DB usando CrudCarrito
                $crudCarrito->actualizarCantidadItemCarritoDB($idCarritoDB, $idProducto, $item['cantidad']);
                break;
            }
        }
        unset($item);
    }
    // Lógica para disminuir cantidad
    elseif ($accion === 'decrease' && $idProducto > 0) {
        foreach ($_SESSION['carrito'] as $key => &$item) {
            if ($item['id'] === $idProducto) {
                $item['cantidad']--;
                if ($item['cantidad'] <= 0) {
                    unset($_SESSION['carrito'][$key]);
                    // Eliminar de la DB usando CrudCarrito
                    $crudCarrito->eliminarItemDeCarritoDB($idCarritoDB, $idProducto);
                } else {
                    // Actualizar en la DB usando CrudCarrito
                    $crudCarrito->actualizarCantidadItemCarritoDB($idCarritoDB, $idProducto, $item['cantidad']);
                }
                break;
            }
        }
        unset($item);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Re-indexar
    }
    // Lógica para eliminar producto
    elseif ($accion === 'remove' && $idProducto > 0) {
        foreach ($_SESSION['carrito'] as $key => $item) {
            if ($item['id'] === $idProducto) {
                unset($_SESSION['carrito'][$key]);
                // Eliminar de la DB usando CrudCarrito
                $crudCarrito->eliminarItemDeCarritoDB($idCarritoDB, $idProducto);
                break;
            }
        }
        $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Re-indexar
    }
    // Lógica para vaciar carrito
    elseif ($accion === 'clear_cart') {
        $_SESSION['carrito'] = [];
        // Vaciar en la DB usando CrudCarrito
        $crudCarrito->vaciarCarritoDB($idCarritoDB);
    }
    // Lógica para guardar pedido (esto usará CrudPedidos)
    elseif ($accion === 'guardar_pedido') {
        if (!empty($_SESSION['carrito']) && isset($_SESSION['idEmpleado'])) {
            // Necesitas obtener el idCliente. Esto es un placeholder.
            $idCliente = 1; // **IMPORTANTE: Reemplaza esto con la lógica real para obtener el ID del cliente.**

            // Iniciar una transacción para asegurar la atomicidad de la creación del pedido
            $db = Db::conectar();
            $db->beginTransaction();

            try {
                // 1. Crear el registro principal del pedido en la tabla 'pedido'
                $idNuevoPedido = $crudPedidos->crearPedido($idCliente, $idEmpleado, 'pendiente');

                if ($idNuevoPedido) {
                    // 2. Guardar los detalles del pedido (productos) en 'detalles_pedido'
                    foreach ($_SESSION['carrito'] as $item) {
                        $crudPedidos->agregarDetallePedido(
                            $idNuevoPedido,
                            $item['id'],
                            $item['cantidad'],
                            $item['precio']
                        );
                    }
                    // 3. Marcar el carrito de sesión como finalizado en la DB usando CrudCarrito
                    $crudCarrito->finalizarCarrito($idCarritoDB);

                    // 4. Vaciar el carrito de la sesión
                    $_SESSION['carrito'] = [];
                    // Eliminar el idCarritoDB de la sesión, ya no es el carrito activo
                    unset($_SESSION['idCarritoDB']);

                    $db->commit(); // Confirmar la transacción
                    header('Location: ../historialPedidos.html?pedidoGuardado=true'); // Redirigir a confirmación
                    exit();
                } else {
                    throw new Exception("No se pudo crear el pedido principal.");
                }
            } catch (Exception $e) {
                $db->rollBack(); // Revertir la transacción si algo falla
                error_log("ERROR al guardar pedido: " . $e->getMessage());
                echo "<script>alert('ERROR: No se pudo guardar el pedido. " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('El carrito está vacío o no hay un empleado logueado para guardar el pedido.');</script>";
        }
    }

    // REDIRECCIÓN POST-REDIRECT-GET (PRG) para evitar reenvío de formulario
    header('Location: ../carrito.php');
    exit();

} else {
    // Si alguien intenta acceder directamente a adminCarrito.php sin una acción POST,
    header('Location: ../menu.php'); // O a una página de error
    exit();
}
?>