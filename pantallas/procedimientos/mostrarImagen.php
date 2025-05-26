<?php
require_once(__DIR__ . '/crudProducto.php');
require_once(__DIR__ . '/claseInfoProducto.php');

if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];
    $crudProducto = new CrudProducto();
    $producto = $crudProducto->obtenerProducto($idProducto);

    if ($producto && $producto->getImagen() !== null) {
        $imagenData = $producto->getImagen();
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imagenData);

        header("Content-Type: " . $mimeType);
        header("Content-Length: " . strlen($imagenData));
        echo $imagenData;
        exit();
    }
}
// Si no se encuentra la imagen o el ID no es válido, muestra una imagen por defecto
header('Content-Type: image/png'); 
readfile(__DIR__ . '/../../img/general/no_image_available.png');
exit();
?>