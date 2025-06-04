<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}

require_once ('procedimientos/conexion.php');
require_once ('procedimientos/claseProducto.php'); 
require_once ('procedimientos/claseCategoria.php'); 
require_once ('procedimientos/crudProductos.php'); 


// Verificar si se ha proporcionado un ID de producto
if (!isset($_GET['idProducto']) || !is_numeric($_GET['idProducto'])) {
    $_SESSION['mensaje_error'] = "ID de producto no válido.";
    header('Location: modificarMenu.php'); 
    exit();
}
//se obtiene el id del producto a eliminar y se crea una instancia de CrudProductos para obtener el producto
$idProducto = $_GET['idProducto'];
$crudProductos = new CrudProductos();
$producto = $crudProductos->obtenerProductoPorId($idProducto);


if (!$producto) {
    $_SESSION['mensaje_error'] = "Producto no encontrado.";
    header('Location: modificarMenu.php');
    exit();
}

// Obtener todas las categorías usando el método de CrudProductos
$categorias = $crudProductos->obtenerCategorias(); // Llamada al método de CrudProductos

// Variable para el nombre de la categoría del producto actual
$nombreCategoriaProducto = '';
foreach ($categorias as $categoria) {
    
    if ($categoria->getIdCategoria() == $producto->getIdCategoria()) {
        $nombreCategoriaProducto = $categoria->getNombre();
        break;
    }
}

// Mensajes de sesión en caso de exito o error
$mensajeExito = '';
$mensajeError = '';

if (isset($_SESSION['mensaje_exito'])) {
    $mensajeExito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']);
}
if (isset($_SESSION['mensaje_error'])) {
    $mensajeError = $_SESSION['mensaje_error'];
    unset($_SESSION['mensaje_error']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Eliminar producto</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/agregarProducto.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">ELIMINAR PRODUCTO</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu"> Ver menú</a></li>
                    <hr>
                    <li><a href="historialPedidos.php"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="form-container">
        <?php if (!empty($mensajeExito)): ?>
            <div class="mensaje-exito"><?php echo $mensajeExito; ?></div>
        <?php endif; ?>
        <?php if (!empty($mensajeError)): ?>
            <div class="mensaje-error"><?php echo $mensajeError; ?></div>
        <?php endif; ?>

        <form action="procedimientos/procesarEliminarProducto.php" method="POST">
            <input type="hidden" name="idProducto" value="<?php echo $producto->getIdProducto(); ?>">

            <div class="top-section">
                <div class="info-column">
                    <div class="form-item">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" value="<?php echo $producto->getNombre(); ?>" readonly>
                    </div>
                    <div class="form-item">
                        <label for="categoria">Categoría</label>
                        <div class="select-container">
                            <select id="categoria" disabled>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria->getIdCategoria(); ?>"
                                        <?php echo ($categoria->getIdCategoria() == $producto->getIdCategoria()) ? 'selected' : ''; ?>>
                                        <?php echo $categoria->getNombre(); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="image-column">
                    <div class="form-item image-upload-item">
                        <label for="imagen">Imagen</label>
                        <div class="image-upload-container">
                            <div class="image-upload">
                                <?php if ($producto->getImagen()): ?>
                                    <img src="data:image/webp;base64, <?php echo base64_encode($producto->getImagen()); ?>" 
                                         alt="Imagen de <?php echo $producto->getNombre(); ?>" 
                                         style="display: block;">
                                    <div class="upload-icon" style="display: none;">+</div> 
                                <?php else: ?>
                                    <img src="#" alt="Sin imagen" style="display: none;">
                                    <div class="upload-icon" style="display: flex;">+</div> 
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item description-item">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" value="<?php echo $producto->getDescripcion(); ?>" readonly>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item small-item">
                    <label for="precio">Precio unitario</label>
                    <div class="price-input">
                        <span class="price-symbol">$</span>
                        <input type="text" id="precio" value="<?php echo $producto->getPrecio(); ?>" readonly>
                    </div>
                </div>
                <div class="form-item"></div>
                <div class="form-item"></div>
            </div>

            <div class="buttons">
                <button type="submit" class="btn btn-save">ELIMINAR</button>
                <a href="modificarMenu.php" class="btn btn-cancel">CANCELAR</a>
            </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
    <script>
    </script>
</body>
</html>