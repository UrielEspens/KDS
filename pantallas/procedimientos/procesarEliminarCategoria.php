<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header("location:../error/index.php");
    exit;
}
require_once 'conexion.php';
require_once 'claseCategoria.php';
require_once 'crudProductos.php'; // O el archivo donde esté tu lógica de eliminación de categorías

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['idCategoria']) && !empty($_POST['idCategoria'])) {
        $idCategoria = $_POST['idCategoria'];

        $crudProductos = new CrudProductos();
        
        // Asumiendo que tienes un método para eliminar categoría por ID
        if ($crudProductos->eliminarCategoriaPorId($idCategoria)) {
            $_SESSION['mensaje_exito'] = 'Categoría eliminada con éxito.';
        } else {
            $_SESSION['mensaje_error'] = 'Error al eliminar la categoría.';
        }
    } else {
        $_SESSION['mensaje_error'] = 'Por favor, seleccione una categoría.';
    }
    header("location: ../eliminarCategoria.php"); // Redirige de vuelta al formulario
    exit;
} else {
    header("location: ../error/index.php"); // O redirige a donde consideres apropiado si no es un POST
    exit;
}
?>