<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}

require_once('procedimientos/conexion.php');
require_once('procedimientos/claseCategoria.php'); 
require_once('procedimientos/crudProductos.php');

$crudProductos = new CrudProductos();

$idProducto = $_GET['idProducto'] ?? null;

if ($idProducto === null || !is_numeric($idProducto)) {
    $_SESSION['mensaje_error'] = "ID de producto no válido o no especificado.";
    header('Location: modificarMenu.php');
    exit();
}

$producto = $crudProductos->obtenerProductoPorId($idProducto);

if ($producto === null) {
    $_SESSION['mensaje_error'] = "El producto solicitado no existe.";
    header('Location: modificarMenu.php');
    exit();
}

$categorias = $crudProductos->obtenerCategorias();

// Recuperar mensajes de sesión si existen
$mensajeError = $_SESSION['mensaje_error'] ?? '';
$mensajeExito = $_SESSION['mensaje_exito'] ?? '';
unset($_SESSION['mensaje_error']); // Limpiar mensaje después de mostrar
unset($_SESSION['mensaje_exito']); // Limpiar mensaje después de mostrar

?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar producto</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/agregarProducto.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">MODIFICAR PRODUCTO</div>
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
        <?php if (!empty($mensajeError)): ?>
            <p class="mensaje-error"><?php echo $mensajeError; ?></p>
        <?php endif; ?>
        <?php if (!empty($mensajeExito)): ?>
            <p class="mensaje-exito"><?php echo $mensajeExito; ?></p>
        <?php endif; ?>

        <form action="procedimientos/actualizarProducto.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idProducto" value="<?php echo $producto->getIdProducto(); ?>">
            <input type="hidden" name="accion" value="actualizar">

            <div class="top-section">
                <div class="info-column">
                    <div class="form-item">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Escriba el nombre" value="<?php echo $producto->getNombre(); ?>" required>
                    </div>
                    <div class="form-item">
                        <label for="categoria">Categoría</label>
                        <div class="select-container">
                            <select id="categoria" name="categoria" required>
                                <?php if (!empty($categorias)): ?>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?php echo $cat->getIdCategoria(); ?>"
                                            <?php echo ($cat->getIdCategoria() == $producto->getIdCategoria()) ? 'selected' : ''; ?>>
                                            <?php echo $cat->getNombre(); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="">No hay categorías disponibles</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="image-column">
                    <div class="form-item">
                        <label for="imagen">Imagen Actual</label>
                        <div class="image-upload-container">
                            <div class="image-upload">
                                <img id="preview-image" src="data:image/webp;base64, <?php echo base64_encode($producto->getImagen()); ?>" alt="Imagen <?php echo htmlspecialchars($producto->getNombre()); ?>">
    
                            </div>
                        </div>
                        
                        <div class="form-item">
                            <input class="iconoDeSubirImagen" type="file" name="imagen_nueva" id="imagen_nueva" accept="image/jpeg, image/png, image/gif, image/webp">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item description-item">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" placeholder="Escriba la descripción del producto" value="<?php echo $producto->getDescripcion(); ?>" required>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item small-item">
                    <label for="precio">Precio unitario</label>
                    <div class="price-input">
                        <span class="price-symbol">$</span>
                        <input type="text" id="precio" name="precio" value="<?php echo number_format($producto->getPrecio(), 2, '.', ''); ?>" required>
                    </div>
                </div>
                <div class="form-item"></div>
                <div class="form-item"></div>
            </div>

            <div class="buttons">
                <button type="submit" class="btn btn-save">Guardar Cambios</button>
                <a href="modificarMenu.php" class="btn btn-cancel">Volver</a>
            </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>