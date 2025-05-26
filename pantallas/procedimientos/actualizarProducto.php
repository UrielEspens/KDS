<?php
session_start();

require_once('conexion.php');
require_once('claseProducto.php'); 
require_once('claseCategoria.php'); 
require_once('crudProductos.php');

$crud = new CrudProductos();

// Verifica si se envió la acción de actualizar
if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $idProducto = $_POST['idProducto'];
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = trim($_POST['precio']);
    $idCategoria = $_POST['categoria'];
    $mensajeError = '';
    $mensajeExito = '';

    $idProducto = (int)$idProducto;
    $precio = floatval(str_replace(',', '.', $precio));
    $idCategoria = (int)$idCategoria;
    $imagen_data = null; 

    // Solo si se envió un archivo y no hay errores de subida
    if (isset($_FILES['imagen_nueva']) && $_FILES['imagen_nueva']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen_nueva']['tmp_name'];
        $fileSize = $_FILES['imagen_nueva']['size'];
        $fileType = $_FILES['imagen_nueva']['type']; 

        $imagen_data = file_get_contents($fileTmpPath);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type_real = $finfo->buffer($imagen_data);

        // Tipos de imagen permitidos
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 10 * 1024 * 1024;

        if (!in_array($mime_type_real, $allowedTypes)) {
            $mensajeError = "El archivo subido no es una imagen válida (JPG, PNG, GIF, WebP). Tipo detectado: " . htmlspecialchars($mime_type_real);
            $imagen_data = null;
        } elseif ($fileSize > $maxFileSize) {
            $mensajeError = "El tamaño de la imagen excede el límite (" . ($maxFileSize / (1024 * 1024)) . " MB).";
            $imagen_data = null;
        }

        if (!empty($mensajeError)) {
            $_SESSION['mensaje_error'] = $mensajeError;
            header('Location: ../modificarProducto.php?idProducto=' . $idProducto);
            exit();
        }
    }
    if ($crud->actualizarProducto($idProducto, $nombre, $descripcion, $precio, $idCategoria, $imagen_data)) {
        $mensajeExito = "Producto actualizado correctamente.";
        $_SESSION['mensaje_exito'] = $mensajeExito;
    } else {
        $mensajeError = "Error al actualizar el producto en la base de datos.";
        $_SESSION['mensaje_error'] = $mensajeError;
    }

    // Redirigir de vuelta a modificarProducto.php con el ID del producto
    header('Location: ../modificarProducto.php?idProducto=' . $idProducto);
    exit();

} else {
    // Si se accede directamente o sin la acción 'actualizar'
    $_SESSION['mensaje_error'] = "Acceso no autorizado para actualizar producto.";
    header('Location: ../modificarMenu.php'); // Redirigir a la lista general
    exit();
}
?>