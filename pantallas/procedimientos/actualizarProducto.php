<?php
// Se requiere inicio de sesión
session_start();

// Incluye los archivos necesarios para la conexión a la base de datos y las clases de Producto, Categoria y CrudProductos
require_once('conexion.php');
require_once('claseProducto.php'); 
require_once('claseCategoria.php'); 
require_once('crudProductos.php');

// Se crea una nueva instancia de la clase CrudProductos
$crud = new CrudProductos();

// Verifica si se envió la acción de actualizar
if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    // Obtiene los datos del formulario
    $idProducto = $_POST['idProducto'];
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = trim($_POST['precio']);
    $idCategoria = $_POST['categoria'];
    $mensajeError = '';
    $mensajeExito = '';

    $idProducto = (int)$idProducto;
    // Convierte el precio a un número decimal, reemplazando la coma por un punto si es necesario
    $precio = floatval(str_replace(',', '.', $precio));
    $idCategoria = (int)$idCategoria;
    $imagen_data = null; 

    // Verifica si se envió un archivo de imagen nuevo y si no hubo errores en la subida
    if (isset($_FILES['imagen_nueva']) && $_FILES['imagen_nueva']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen_nueva']['tmp_name'];
        $fileSize = $_FILES['imagen_nueva']['size'];
        $fileType = $_FILES['imagen_nueva']['type']; 

        $imagen_data = file_get_contents($fileTmpPath);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type_real = $finfo->buffer($imagen_data);

        // Tipos de imagen permitidos y el tamaño máximo permitido para la imagen
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 10 * 1024 * 1024;

        // Valida el tipo de archivo de imagen
        if (!in_array($mime_type_real, $allowedTypes)) {
            $mensajeError = "El archivo subido no es una imagen válida (JPG, PNG, GIF, WebP). Tipo detectado: " . $mime_type_real;
            $imagen_data = null;
        } elseif ($fileSize > $maxFileSize) {
            $mensajeError = "El tamaño de la imagen excede el límite (" . ($maxFileSize / (1024 * 1024)) . " MB).";
            $imagen_data = null;
        }

        // Si hay algún error en la validación de la imagen, se guarda el mensaje de error en la sesión y redirige.
        if (!empty($mensajeError)) {
            $_SESSION['mensaje_error'] = $mensajeError;
            header('Location: ../modificarProducto.php?idProducto=' . $idProducto);
            exit();
        }
    }
    // Se actualiza el producto en la base de datos con los nuevos datos y la imagen (si se proporcionó una nueva)
    if ($crud->actualizarProducto($idProducto, $nombre, $descripcion, $precio, $idCategoria, $imagen_data)) {
        $mensajeExito = "Producto actualizado correctamente.";
        $_SESSION['mensaje_exito'] = $mensajeExito;
    } else {
        $mensajeError = "Error al actualizar el producto en la base de datos.";
        $_SESSION['mensaje_error'] = $mensajeError;
    }

    // Redirigir a modificarProducto.php con el ID del producto
    header('Location: ../modificarProducto.php?idProducto=' . $idProducto);
    exit();

} else {
    // Si se accede directamente o sin la acción 'actualizar'
    $_SESSION['mensaje_error'] = "Acceso no autorizado para actualizar producto.";
    header('Location: ../modificarMenu.php'); // Redirigir a la página principal del menú para modificar
    exit();
}
?>