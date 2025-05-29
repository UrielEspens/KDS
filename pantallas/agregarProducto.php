<?php
require_once 'procedimientos/conexion.php';
require_once 'procedimientos/claseCategoria.php';
require_once 'procedimientos/crudProductos.php';

// Iniciar sesión para gestionar los mensajes de éxito o error
session_start();

// Instanciar CrudProductos para obtener la lista de categorías
$crudProductos = new CrudProductos();
$categorias = $crudProductos->obtenerCategorias();

// Inicializar variables para mensajes y datos pre-rellenados del formulario
$mensajeExito = '';
$mensajeError = '';

if (isset($_SESSION['mensaje_exito'])) {
    $mensajeExito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']);
}
// Verificar si hay mensajes de error en la sesión y borrarlos después de mostrarlos
if (isset($_SESSION['mensaje_error'])) {
    $mensajeError = $_SESSION['mensaje_error'];
    unset($_SESSION['mensaje_error']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agregar producto</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/agregarProducto.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">AGREGAR PRODUCTO</div>
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

        <form action="procedimientos/procesarAgregarProducto.php" method="POST" enctype="multipart/form-data">
            <div class="top-section">
                <div class="info-column">
                    <div class="form-item">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Escriba el nombre" value="" required>
                    </div>
                    <div class="form-item">
                        <label for="categoria">Categoría</label>
                        <div class="select-container">
                            <select id="categoria" name="idCategoria" required>
                                <option value="">Seleccione una categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat->getIdCategoria(); ?>"
                                        <?php echo ($idCategoriaSeleccionada == $cat->getIdCategoria()) ? 'selected' : ''; ?>>
                                        <?php echo $cat->getNombre(); ?>
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
                            <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png, image/webp" required>
                        </div>
                        </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item description-item">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" placeholder="Escriba la descripción del producto" value="" required>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item small-item">
                    <label for="precio">Precio unitario</label>
                    <div class="price-input">
                        <span class="price-symbol">$</span>
                        <input type="text" id="precio" name="precio" value="00.00" required>
                    </div>
                </div>
                <div class="form-item"></div>
                <div class="form-item"></div>
            </div>

            <div class="buttons">
                <button type="submit" class="btn btn-save">Guardar</button>
                <a href="modificarMenu.php" class="btn btn-cancel">Volver</a>
            </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>