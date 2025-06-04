<?php
// procedimientos/procesarEliminarProducto.php

require_once 'crudProductos.php'; 
session_start(); 

// Verifica que se haya enviado un ID de producto válido por POST
if (isset($_POST['idProducto']) && is_numeric($_POST['idProducto'])) {
    $idProducto = $_POST['idProducto'];
    $crudProductos = new CrudProductos();

    // Intenta eliminar el producto con el ID proporcionado
    if ($crudProductos->eliminarProducto($idProducto)) {
        $_SESSION['mensaje_exito'] = "Producto eliminado correctamente.";
    } else {
        $_SESSION['mensaje_error'] = "Error al eliminar el producto.";
    }
} else {
    // Si no se recibió un ID válido, guarda un mensaje de error
    $_SESSION['mensaje_error'] = "ID de producto no válido para la eliminación.";
}

header('Location: ../modificarMenu.php');
exit();
?>