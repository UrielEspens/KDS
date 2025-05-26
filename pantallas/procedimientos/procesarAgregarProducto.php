<?php
// procedimientos/procesarAgregarProducto.php

// Incluir los archivos necesarios.
// Estas rutas son relativas a la ubicación de este script (dentro de la carpeta 'procedimientos').
require_once 'conexion.php';
require_once 'claseProducto.php'; // La clase del objeto Producto (o como la hayas llamado, ej. InfoProducto)
require_once 'crudProductos.php'; // Tu clase CrudProductos, donde resides el método agregarProducto()

// Iniciar sesión para gestionar mensajes de éxito o error
session_start();

// Verificar que la solicitud sea de tipo POST (es decir, que venga de un formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Recoger y sanear los datos del formulario
    // trim() elimina espacios en blanco al inicio y al final de los strings.
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    
    // filter_var con FILTER_VALIDATE_FLOAT valida que el precio sea un número flotante.
    // filter_var con FILTER_VALIDATE_INT valida que la categoría sea un número entero.
    $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT); 
    $idCategoria = filter_var($_POST['idCategoria'] ?? '', FILTER_VALIDATE_INT); 

    // Guardar los datos del formulario en la sesión.
    // Esto es útil para rellenar el formulario si hay un error de validación y el usuario debe volver a la página anterior.
    $_SESSION['form_data'] = [
        'nombre' => $nombre,
        'descripcion' => $descripcion,
        'precio' => $_POST['precio'] ?? '', // Guardamos el string original para el input de texto
        'idCategoria' => $_POST['idCategoria'] ?? ''
    ];

    // 2. Realizar validaciones básicas de los datos
    if (empty($nombre)) {
        $_SESSION['mensaje_error'] = "El **nombre** del producto es obligatorio.";
        header('Location: ../agregarProducto.php'); // Redirigir de vuelta al formulario
        exit();
    }
    if (empty($descripcion)) {
        $_SESSION['mensaje_error'] = "La **descripción** del producto es obligatoria.";
        header('Location: ../agregarProducto.php');
        exit();
    }
    // Comprobar si el precio es un número válido y positivo.
    // Un precio de 0.00 es válido, pero un precio negativo o inválido no lo es.
    if ($precio === false || $precio < 0) {
        $_SESSION['mensaje_error'] = "El **precio unitario** debe ser un número válido y positivo.";
        header('Location: ../agregarProducto.php');
        exit();
    }
    // Comprobar si el ID de categoría es un número entero válido y mayor que 0.
    if ($idCategoria === false || $idCategoria <= 0) {
        $_SESSION['mensaje_error'] = "Debe seleccionar una **categoría** válida.";
        header('Location: ../agregarProducto.php');
        exit();
    }

    // 3. Manejo de la imagen (subida de archivo)
    $imagenBlob = null; // Inicializamos la imagen como nula por defecto

    // Verificar si se subió un archivo de imagen y no hubo errores en la subida
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['imagen']['tmp_name']; // Ruta temporal del archivo en el servidor
        $fileName = $_FILES['imagen']['name'];       // Nombre original del archivo
        $fileSize = $_FILES['imagen']['size'];       // Tamaño del archivo en bytes
        $fileType = $_FILES['imagen']['type'];       // Tipo MIME del archivo (ej. image/jpeg)

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

        // Leer el contenido binario del archivo temporal.
        // Esto es necesario para almacenar la imagen directamente como un BLOB en la base de datos.
        $imagenBlob = file_get_contents($fileTmpPath);

        // Opcional: Eliminar el archivo temporal del servidor.
        // PHP generalmente lo hace automáticamente al finalizar la ejecución del script,
        // pero puedes forzarlo si lo necesitas (por ejemplo, para liberar espacio rápidamente).
        // unlink($fileTmpPath); 
    }

    // 4. Crear un objeto Producto y pasarlo a la clase CrudProductos para guardarlo
    $nuevoProducto = new InfoProducto(); // Asegúrate de usar el nombre correcto de tu clase (ej. InfoProducto)
    $nuevoProducto->setNombre($nombre);
    $nuevoProducto->setDescripcion($descripcion);
    $nuevoProducto->setPrecio($precio);
    $nuevoProducto->setIdCategoria($idCategoria);
    $nuevoProducto->setImagen($imagenBlob); // La imagen será NULL si no se subió o hubo un error

    // Instanciar la clase CrudProductos
    $crudProductos = new CrudProductos();

    // Intentar agregar el producto a la base de datos
    if ($crudProductos->agregarProducto($nuevoProducto)) {
        $_SESSION['mensaje_exito'] = "Producto **agregado** correctamente.";
        // Limpiar los datos del formulario de la sesión en caso de éxito para que el formulario aparezca vacío la próxima vez
        unset($_SESSION['form_data']); 
    } else {
        $_SESSION['mensaje_error'] = "Ocurrió un **error** al agregar el producto.";
    }

    // 5. Redirigir al usuario de vuelta a la página de agregar producto (o a la lista de productos)
    // Esto es crucial para evitar el "reenvío del formulario" si el usuario recarga la página.
    header('Location: ../agregarProducto.php');
    exit(); // Es importante llamar a exit() después de header() para asegurar que no se ejecute más código.

} else {
    // Si la solicitud no es POST (es decir, alguien intentó acceder directamente a este script),
    // redirigir con un mensaje de error.
    $_SESSION['mensaje_error'] = "Acceso no permitido directamente.";
    header('Location: ../agregarProducto.php');
    exit();
}