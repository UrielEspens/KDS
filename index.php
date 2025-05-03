<?php
session_start();

// Inicializar carrito
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    if (isset($_SESSION['carrito'][$nombre])) {
        $_SESSION['carrito'][$nombre]['cantidad'] += 1;
    } else {
        $_SESSION['carrito'][$nombre] = ['precio' => $precio, 'cantidad' => 1];
    }
}

// Total general de artículos
$cantidadCarrito = 0;
foreach ($_SESSION['carrito'] as $producto) {
    if (isset($producto['cantidad'])) {
        $cantidadCarrito += $producto['cantidad'];
    }
}

?>
<!DOCTYPE html>
<html>
<head><title>Inicio</title></head>
<body>
  <h1>Tienda</h1>
  <p>🛒 Carrito: <?php echo $cantidadCarrito; ?> artículo(s)</p>
  <a href="carrito.php">Ver carrito</a>
  <hr>

  <form method="post">
    <input type="hidden" name="nombre" value="Camiseta">
    <input type="hidden" name="precio" value="200">
    <button name="agregar">Agregar Camiseta ($200)</button>
  </form>

  <form method="post">
    <input type="hidden" name="nombre" value="Gorra">
    <input type="hidden" name="precio" value="100">
    <button name="agregar">Agregar Gorra ($100)</button>
  </form>
</body>
</html>
