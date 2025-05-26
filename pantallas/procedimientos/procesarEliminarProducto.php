<?php
// procedimientos/procesarEliminarProducto.php

// Ruta relativa a la ubicación de este script (dentro de 'procedimientos')
require_once 'crudProductos.php';
session_start();

if (isset($_POST['idProducto']) && is_numeric($_POST['idProducto'])) {
    $idProducto = $_POST['idProducto'];
    $crudProductos = new CrudProductos();

    if ($crudProductos->eliminarProducto($idProducto)) {
        $_SESSION['mensaje_exito'] = "Producto eliminado correctamente.";
    } else {
        $_SESSION['mensaje_error'] = "Error al eliminar el producto.";
    }
} else {
    $_SESSION['mensaje_error'] = "ID de producto no válido para la eliminación.";
}

header('Location: ../modificarMenu.php');
exit();
?>