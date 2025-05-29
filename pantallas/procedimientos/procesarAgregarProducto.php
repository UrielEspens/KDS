<?php
// procedimientos/procesarAgregarProducto.php

require_once 'conexion.php';
require_once 'claseProducto.php';
require_once 'crudProductos.php';

// Iniciar sesión para gestionar mensajes de éxito o error
session_start();

// Verificar que la solicitud sea de tipo POST (es decir, que venga de un formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    
    $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT); 
    $idCategoria = filter_var($_POST['idCategoria'] ?? '', FILTER_VALIDATE_INT); 

    $_SESSION['form_data'] = [
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'precio' => $_POST['precio'] ?? '',
        'idCategoria' => $_POST['idCategoria'] ?? ''
    ];

    if ($precio === false || $precio < 0) {
        $_SESSION['mensaje_error'] = "El **precio unitario** debe ser un número válido y positivo.";
        header('Location: ../agregarProducto.php');
        exit();
    }
    $imagenBlob = null; // Inicializamos la imagen como nula por defecto

    // Verificar si se subió un archivo de imagen y no hubo errores en la subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];   
        $fileSize = $_FILES['imagen']['size'];     
        $fileType = $_FILES['imagen']['type'];     

        // Tipos de archivos permitidos
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        // Tamaño máximo permitido (2 MB en este ejemplo)
        $maxFileSize = 2 * 1024 * 1024; // 2 MB = 2 * 1024 KB = 2 * 1024 * 1024 bytes

        // Validar el tipo de archivo
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['mensaje_error'] = "Tipo de archivo de imagen no permitido. Solo se aceptan **JPG, PNG o WebP**.";
            header('Location: ../agregarProducto.php');
            exit();
        }
        // Validar el tamaño del archivo
        if ($fileSize > $maxFileSize) {
            $_SESSION['mensaje_error'] = "El tamaño de la imagen excede el límite permitido (**2 MB**).";
            header('Location: ../agregarProducto.php');
            exit();
        }

        $imagenBlob = file_get_contents($fileTmpPath);

    }

    $nuevoProducto = new InfoProducto(); 
    $nuevoProducto->setNombre($nombre);
    $nuevoProducto->setDescripcion($descripcion);
    $nuevoProducto->setPrecio($precio);
    $nuevoProducto->setIdCategoria($idCategoria);
    $nuevoProducto->setImagen($imagenBlob);

    // Instanciar la clase CrudProductos
    $crudProductos = new CrudProductos();

    // Intentar agregar el producto a la base de datos
    if ($crudProductos->agregarProducto($nuevoProducto)) {
        $_SESSION['mensaje_exito'] = "Producto agregado correctamente.";
        unset($_SESSION['form_data']); 
    } else {
        $_SESSION['mensaje_error'] = "Ocurrió un error al agregar el producto.";
    }

    header('Location: ../modificarMenu.php');
    exit();

} else {
    $_SESSION['mensaje_error'] = "Acceso no permitido directamente.";
    header('Location: ../agregarProducto.php');
    exit();
}