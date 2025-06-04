<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header("location:../error/index.php");
    exit;
}

require_once 'conexion.php';
require_once 'claseCategoria.php';
require_once 'crudProductos.php';

// Verifica que la petición sea POST (para evitar accesos indebidos por GET)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica que se haya enviado el id de la categoría
    if (isset($_POST['idCategoria']) && !empty($_POST['idCategoria'])) {
        $idCategoria = $_POST['idCategoria'];

        $crudProductos = new CrudProductos();
        
        // Intenta eliminar la categoría por su ID
        if ($crudProductos->eliminarCategoriaPorId($idCategoria)) {
            $_SESSION['mensaje_exito'] = 'Categoría eliminada con éxito.';
        } else {
            $_SESSION['mensaje_error'] = 'Error al eliminar la categoría.';
        }
    } else {
        // Si no se seleccionó ninguna categoría
        $_SESSION['mensaje_error'] = 'Por favor, seleccione una categoría.';
    }
    // Redirige de vuelta al formulario de eliminar categoría
    header("location: ../eliminarCategoria.php");
    exit;
} else {
    header("location: ../error/index.php");
    exit;
}
?>