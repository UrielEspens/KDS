<?php
require_once('claseCliente.php');
require_once('crudClientes.php');
require_once('crudCarrito.php');
require_once('crudProductos.php'); 

// Instanciar los objetos CRUD
$crudCarrito = new CrudCarrito();
$crudProductos = new CrudProductos(); // Mantener por si necesitas obtener detalles de productos para el carrito
$crudClientes = new CrudClientes();

// Variables de sesión del empleado
$nombreEmpleado = $_SESSION['nombre'] ?? 'Desconocido';
$apellidoEmpleado = $_SESSION['primerApe'] ?? 'Desconocido';
$puestoEmpleado = $_SESSION['puesto'] ?? 'Desconocido';
$usuarioEmpleado = $_SESSION['usuario'] ?? 'Desconocido';
$idEmpleado = $_SESSION['idEmpleado'] ?? null;

// Validar que el idEmpleado exista
if (is_null($idEmpleado)) {
    error_log("ERROR: idEmpleado no está definido en la sesión en cargarCarrito.php");
    $_SESSION['mensaje_error'] = "No se pudo cargar la información del empleado. Por favor, inicie sesión nuevamente.";
    header('Location: ../index.php'); // Redirigir a inicio de sesión
    exit();
}

// --- Lógica de Manejo del Carrito Persistente en DB ---
// Centralizamos la lógica de obtención/creación del ID del carrito activo
if (!isset($_SESSION['idCarritoDB'])) {
    $idCarritoDB = $crudCarrito->obtenerIdCarritoActivoPorEmpleado($idEmpleado);

    if (!$idCarritoDB) {
        // Si no hay un carrito activo para este empleado, crea uno nuevo.
        $idCarritoDB = $crudCarrito->crearCarritoParaEmpleado($idEmpleado);
        if (!$idCarritoDB) {
            error_log("ERROR: No se pudo crear un nuevo carrito para el empleado " . $idEmpleado);
            $_SESSION['mensaje_error'] = "No se pudo iniciar el carrito de compras. Intente de nuevo.";
            header('Location: ../inicio.php'); // O a una página de error
            exit();
        }
    }
    $_SESSION['idCarritoDB'] = $idCarritoDB; // Almacena el ID del carrito en la sesión
} else {
    // Si ya existe en sesión, simplemente lo usamos
    $idCarritoDB = $_SESSION['idCarritoDB'];
    // Opcional: podrías verificar si este idCarritoDB sigue activo en la BD,
    // pero para simplicidad, asumimos que si está en sesión, es válido.
}

// Cargar los ítems del carrito (desde la DB) en la sesión del carrito actual
// Es importante que $_SESSION['carrito'] siempre refleje el estado de la DB al cargar la página
$_SESSION['carrito'] = []; // Vaciar por si acaso, para recargar limpio
$itemsDB = $crudCarrito->obtenerItemsDeCarrito($idEmpleado); // Esta función ya obtiene los ítems del carrito activo
foreach ($itemsDB as $itemDB) {
    $_SESSION['carrito'][] = [
        'id'        => $itemDB->getIdItem(),
        'nombre'    => $itemDB->getNombreItem(),
        'precio'    => $itemDB->getPrecioItem(),
        'cantidad'  => $itemDB->getCantidadItem()
    ];
}


// --- Lógica para seleccionar cliente ---
$listaClientes = [];
if (method_exists($crudClientes, 'obtenerTodosLosClientes')) {
    $listaClientes = $crudClientes->obtenerTodosLosClientes();
} else {
    error_log("ERROR: El método 'obtenerTodosLosClientes' no existe en CrudClientes.");
}

// Asegurarse de que el cliente seleccionado en sesión se muestre correctamente
$clienteIdActual = $_SESSION['clienteSeleccionado_id'] ?? '';
$clienteNombreParaMostrar = $_SESSION['clienteSeleccionado_nombre'] ?? 'No hay cliente seleccionado.';

// Si hay un ID de cliente en sesión pero no el nombre (ej. primera carga o sesión recuperada), buscarlo
if (!empty($clienteIdActual) && empty($_SESSION['clienteSeleccionado_nombre']) && !empty($listaClientes)) {
    foreach ($listaClientes as $cliente) {
        if ((int)$cliente->getIdCliente() === (int)$clienteIdActual) {
            $clienteNombreParaMostrar = $cliente->getNombre() . ' ' . $cliente->getPrimerApe() . ' ' . $cliente->getSegundoApe();
            $_SESSION['clienteSeleccionado_nombre'] = $clienteNombreParaMostrar; // Guardar el nombre en sesión
            break;
        }
    }
}
?>