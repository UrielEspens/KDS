<?php
session_start();

// Redireccionar si el usuario no está logueado
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: ../index.php');
    exit();
}

require_once('conexion.php');
require_once('crudProductos.php');
require_once('claseInfoProducto.php');
require_once('crudCarrito.php');
require_once('crudPedidos.php');
require_once('claseCliente.php');
require_once('crudClientes.php');

// Inicializa el carrito en la sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$crudClientes = new CrudClientes();
$crudProductos = new CrudProductos();
$crudCarrito = new CrudCarrito();
$crudPedidos = new CrudPedidos();

$idEmpleado = $_SESSION['idEmpleado'];

// Validación del idCarritoDB
if (!isset($_SESSION['idCarritoDB']) || !$_SESSION['idCarritoDB']) {
    error_log("ERROR: idCarritoDB no está en sesión en adminCarrito.php para empleado " . $idEmpleado);
    $_SESSION['mensaje_error'] = "Error al procesar el carrito. Por favor, recargue la página.";
    header('Location: ../carrito.php'); // Redirigir para que cargarCarrito.php lo inicialice
    exit();
}
$idCarritoDB = $_SESSION['idCarritoDB'];


$accion = $_POST['accionCarrito'] ?? '';
$idProducto = isset($_POST['idProducto']) ? (int)$_POST['idProducto'] : 0;

if (isset($_POST['idCliente']) && !isset($_POST['accionCarrito'])) {
    $clienteIdSeleccionado = (int)$_POST['idCliente'];
    $_SESSION['clienteSeleccionado_id'] = $clienteIdSeleccionado;
    $clienteObjeto = $crudClientes->obtenerClientePorId($clienteIdSeleccionado);
    if ($clienteObjeto) {
        $_SESSION['clienteSeleccionado_nombre'] = $clienteObjeto->getNombre() . " " . $clienteObjeto->getPrimerApe() . " " . $clienteObjeto->getSegundoApe();
    } else {
        $_SESSION['clienteSeleccionado_nombre'] = 'Cliente no encontrado.';
        error_log("WARNING: Cliente con ID " . $clienteIdSeleccionado . " no encontrado al seleccionar cliente en carrito.");
    }
    header('Location: ../carrito.php');
    exit();
}

// Para añadir al carrido desde menu.php
if ($accion === 'añadir' && $idProducto > 0) {
    $productoEncontradoEnCarritoSesion = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] === $idProducto) {
            $item['cantidad']++;
            $productoEncontradoEnCarritoSesion = true;
            $crudCarrito->actualizarCantidadItemCarritoDB($idCarritoDB, $idProducto, $item['cantidad']);
            break;
        }
    }
    unset($item); // Romper la referencia

    if (!$productoEncontradoEnCarritoSesion) {
        $productoObtenido = $crudProductos->obtenerProductoPorId($idProducto);
        if ($productoObtenido && $productoObtenido instanceof InfoProducto) {
            $nuevoItem = [
                'id'        => $productoObtenido->getIdProducto(),
                'nombre'    => $productoObtenido->getNombre(),
                'precio'    => $productoObtenido->getPrecio(),
                'cantidad'  => 1
            ];
            $_SESSION['carrito'][] = $nuevoItem;
            $crudCarrito->agregarOActualizarItemEnCarritoDB(
                $idCarritoDB,
                $productoObtenido->getIdProducto(),
                1,
                $productoObtenido->getPrecio()
            );
        } else {
            error_log("ERROR: Producto con ID " . $idProducto . " no encontrado o no es una instancia de InfoProducto válida al intentar añadir al carrito.");
        }
    }
    // Redirigir a menu.php si el añadir viene de allí
    header('Location: ../menu.php');
    exit();
//Para aumentar la cantidad desde carrito.php
} elseif ($accion === 'sumar' && $idProducto > 0) {
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] === $idProducto) {
            $item['cantidad']++;
            $crudCarrito->actualizarCantidadItemCarritoDB($idCarritoDB, $idProducto, $item['cantidad']);
            break;
        }
    }
    unset($item);
    header('Location: ../carrito.php');
    exit();
//Para disminuir la cantidad desde carrito.php
} elseif ($accion === 'restar' && $idProducto > 0) {
    foreach ($_SESSION['carrito'] as $key => &$item) {
        if ($item['id'] === $idProducto) {
            $item['cantidad']--;
            if ($item['cantidad'] <= 0) {
                unset($_SESSION['carrito'][$key]);
                $crudCarrito->eliminarItemDeCarritoDB($idCarritoDB, $idProducto);
            } else {
                $crudCarrito->actualizarCantidadItemCarritoDB($idCarritoDB, $idProducto, $item['cantidad']);
            }
            break;
        }
    }
    unset($item);
    $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Re-indexar para evitar huecos en el array
    header('Location: ../carrito.php');
    exit();
//Para eliminar el producto completo desde carrito.php
} elseif ($accion === 'borrar' && $idProducto > 0) {
    foreach ($_SESSION['carrito'] as $key => $item) {
        if ($item['id'] === $idProducto) {
            unset($_SESSION['carrito'][$key]);
            $crudCarrito->eliminarItemDeCarritoDB($idCarritoDB, $idProducto);
            break;
        }
    }
    $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Re-indexar
    header('Location: ../carrito.php');
    exit();
//Para vaciar el carrito completo desde carrito.php
} elseif ($accion === 'vaciar_carrito') {
    $_SESSION['carrito'] = [];
    $crudCarrito->vaciarCarritoDB($idCarritoDB);
    header('Location: ../carrito.php');
    exit();
//Para guardar el pedidio 
} elseif ($accion === 'guardar_pedido') {
    $idCliente = $_POST['idCliente'] ?? null;
    $articulos = $_POST['articulos'] ?? [];
    $cantidades = $_POST['cantidades'] ?? [];

    if (!empty($articulos) && !empty($cantidades) && isset($_SESSION['idEmpleado']) && $idCliente) {
        // Validar que el idCliente sea válido y exista
        $clienteObjeto = $crudClientes->obtenerClientePorId($idCliente);
        if (!$clienteObjeto) {
            echo "<script>alert('Error: El cliente seleccionado no es válido. Por favor, intente de nuevo.'); window.location.href='../carrito.php';</script>";
            exit();
        }

        $idNuevoPedido = $crudPedidos->insertarPedido($idCliente, $idEmpleado);

        if ($idNuevoPedido) {
            if (count($articulos) === count($cantidades)) {
                for ($i = 0; $i < count($articulos); $i++) {
                    $idProducto = (int)$articulos[$i];
                    $cantidadProducto = (int)$cantidades[$i];
                    $crudPedidos->insertarDetalleIndividual($idNuevoPedido, $idProducto, $cantidadProducto);
                }
            } else {
                echo "<script>alert('Error: El número de artículos y cantidades en el formulario no coincide.'); </script>";
                exit();
            }

            $crudCarrito->finalizarCarrito($idCarritoDB);

            $_SESSION['carrito'] = []; // Vaciar el carrito de la sesión actual
            unset($_SESSION['idCarritoDB']); // Eliminar el ID del carrito persistente de la sesión
            unset($_SESSION['clienteSeleccionado_id']);
            unset($_SESSION['clienteSeleccionado_nombre']);

            header('Location: ../inicio.php?pedidoGuardado=true');
            exit();

        } else {
            echo "<script>alert('Error: El carrito está vacío, no hay un empleado logueado o no se ha seleccionado un cliente.'); window.location.href='../carrito.php';</script>";
            exit();
        }
    }
}


if (isset($_GET['accion'])) {
    if ($_GET['accion'] === 'e') {
        header('Location: borado.php');
        exit();
    } elseif ($_GET['accion'] === 'a') {
        header('Location: actualizar.php');
        exit();
    } elseif ($_GET['accion'] === 'b') {
        header('Location: mostrarArticulo.php');
        exit();
    }
}


header('Location: ../carrito.php');
exit();
?>