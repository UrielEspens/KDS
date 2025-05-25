<?php
// Requiere los archivos de clases y CRUDs
require_once('claseCliente.php');
require_once('crudClientes.php');
require_once('crudCarrito.php');
require_once('crudProductos.php');

// Instanciar los objetos CRUD
$crudCarrito = new CrudCarrito();
$crudProductos = new CrudProductos();
$crudClientes = new CrudClientes();

// Variables de sesión del empleado (pueden ser útiles para la vista)
$nombreEmpleado = $_SESSION['nombre'];
$apellidoEmpleado = $_SESSION['primerApe'];
$puestoEmpleado = $_SESSION['puesto'];
$usuarioEmpleado = $_SESSION['usuario'];
$idEmpleado = $_SESSION['idEmpleado'];

// Manejo del Carrito en la base de datos
if (!isset($_SESSION['idCarritoDB'])) {
    $idCarritoDB_result = $crudCarrito->obtenerIdCarritoActivoPorEmpleado($idEmpleado);
    $idCarritoDB = null;

    if ($idCarritoDB_result && isset($idCarritoDB_result['idCarrito'])) {
        $idCarritoDB = $idCarritoDB_result['idCarrito'];
    }

    if (!$idCarritoDB) {
        $idCarritoDB = $crudCarrito->crearCarritoParaEmpleado($idEmpleado);
    }
    $_SESSION['idCarritoDB'] = $idCarritoDB; 
    
   
    if ($idCarritoDB) {
        $_SESSION['carrito'] = []; 
        $itemsDB = $crudCarrito->obtenerItemsDeCarrito($idEmpleado); 
        foreach ($itemsDB as $itemDB) {
            $_SESSION['carrito'][] = [
                'id'       => $itemDB->getIdItem(),
                'nombre'   => $itemDB->getNombreItem(),
                'precio'   => $itemDB->getPrecioItem(),
                'cantidad' => $itemDB->getCantidadItem()
            ];
        }
    }
} else {
    $idCarritoDB = $_SESSION['idCarritoDB'];
}


// --- Lógica para seleccionar cliente ---
$listaClientes = [];
if (method_exists($crudClientes, 'obtenerTodosLosClientes')) {
    $listaClientes = $crudClientes->obtenerTodosLosClientes();
} else {
    error_log("ERROR: El método 'obtenerTodosLosClientes' no existe en CrudClientes.");
}

$clienteIdActual = $_SESSION['clienteSeleccionado_id'] ?? '';
$clienteNombreParaMostrar = $_SESSION['clienteSeleccionado_nombre'] ?? 'No hay cliente seleccionado.';

if (!empty($clienteIdActual) && empty($_SESSION['clienteSeleccionado_nombre']) && !empty($listaClientes)) {
    foreach ($listaClientes as $cliente) {
        if ((int)$cliente->getIdCliente() === (int)$clienteIdActual) {
            $clienteNombreParaMostrar = htmlspecialchars($cliente->getNombre() . ' ' . $cliente->getPrimerApe() . ' ' . $cliente->getSegundoApe());
            $_SESSION['clienteSeleccionado_nombre'] = $clienteNombreParaMostrar;
            break;
        }
    }
}

?>