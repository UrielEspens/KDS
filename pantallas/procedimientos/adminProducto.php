<?php
require_once(__DIR__ . '/crud.Producto.php');
require_once(__DIR__ . '/claseInfoProducto.php');

$crudProducto = new CrudProducto();
$producto = new InfoProducto();

if (isset($_POST['insertar'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $idCategoria = $_POST['idCategoria'];
    $precio = $_POST['precio'];

    $imagenContenido = null;

    if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] == UPLOAD_ERR_OK) {
        $imagenContenido = file_get_contents($_FILES['imagen_file']['tmp_name']);
        if ($imagenContenido === false) {
            error_log("Error al leer el contenido del archivo de imagen temporal en INSERT.");
            $imagenContenido = null;
        }
    }

    $producto->setNombre($nombre);
    $producto->setDescripcion($descripcion);
    $producto->setIdCategoria($idCategoria);
    $producto->setPrecio($precio);
    $producto->setImagen($imagenContenido);

    $crudProducto->insertar($producto);
    header('Location: ../modificarMenu.php?mensaje=ProductoAgregado');
    exit();
}
// Actualizar
else if (isset($_POST['actualizar'])) {
    $idProducto = $_POST['idProducto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $idCategoria = $_POST['idCategoria'];
    $precio = $_POST['precio'];

    $imagenContenido = null; // Por defecto, no se actualizará la imagen

    if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] == UPLOAD_ERR_OK) {
        $imagenContenido = file_get_contents($_FILES['imagen_file']['tmp_name']);
        if ($imagenContenido === false) {
            error_log("Error al leer el contenido del archivo de imagen temporal en el UPDATE.");
            $imagenContenido = null;
        }
    }
    // Si imagenContenido es null, el método actualizar en CrudProducto NO modificará el campo imagen
    // en la base de datos, y asi se conserva la imagen existente

    $producto->setIdProducto($idProducto);
    $producto->setNombre($nombre);
    $producto->setDescripcion($descripcion);
    $producto->setIdCategoria($idCategoria);
    $producto->setPrecio($precio);
    $producto->setImagen($imagenContenido);

    $crudProducto->actualizar($producto);
    header('Location: ../modificarMenu.php?mensaje=ProductoActualizado');
    exit();
}
// Eliminar
else if (isset($_POST['eliminar']) && isset($_POST['idProducto'])) {
    $idProducto = $_POST['idProducto'];
    $crudProducto->eliminar($idProducto);
    header('Location: ../modificarMenu.php?mensaje=ProductoEliminado');
    exit();
}

else {
    header('Location: ../menu.php');
    exit();
}
?>