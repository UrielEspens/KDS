<?php
require_once('../pantallas/procedimientos/crud.Producto.php');
require_once('../pantallas/procedimientos/claseInfoProducto.php');
require_once('../pantallas/procedimientos/crudCategoria.php');

$crudProducto = new CrudProducto();
$crudCategoria = new CrudCategoria();
$listaCategorias = $crudCategoria->obtenerListaCategorias();

$productoAActualizar = null;
$idProducto = null;

if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];
    $productoAActualizar = $crudProducto->obtenerProducto($idProducto);

    if (!$productoAActualizar) {
        header('Location: modificarMenu.php?mensaje=ProductoNoEncontrado'); // Redirige si el ID no existe
        exit();
    }
} else {
    header('Location: modificarMenu.php?mensaje=IDProductoFaltante'); // Redirige si no hay ID
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Modificar Producto</title>
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
        <form action="../pantallas/procedimientos/adminProducto.php" method="post" enctype="multipart/form-data">
            <div class="top-section">
                <div class="info-column">
                    <div class="form-item">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre del producto" required
                               value="<?php echo htmlspecialchars($productoAActualizar->getNombre()); ?>">
                    </div>
                    <div class="form-item">
                        <label for="idCategoria">Categoría</label>
                        <select id="idCategoria" name="idCategoria" required>
                            <?php foreach ($listaCategorias as $categoria): ?>
                                <option value="<?php echo $categoria->getIdCategoria(); ?>"
                                    <?php echo ($categoria->getIdCategoria() == $productoAActualizar->getIdCategoria()) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($categoria->getNombre()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="image-column">
                    <div class="form-item image-upload-item">
                        <label for="imagen_file">Imagen</label>
                        <input type="file" id="imagen_file" name="imagen_file" accept="image/png">
                        <small style="display: block; margin-top: 5px; color: #666;">Dejar vacío si no se desea cambiar la imagen</small>
                        <div class="current-image-preview" style="margin-top: 10px;">
                            <?php if ($productoAActualizar->getImagen()): ?>
                                <p>Imagen actual:</p>
                                <img src="../pantallas/procedimientos/mostrarImagen.php?idProducto=<?php echo $productoAActualizar->getIdProducto(); ?>" alt="Imagen actual" style="max-width: 150px; height: auto; border: 1px solid #ddd;">
                            <?php else: ?>
                                <p>No hay imagen actual.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item description-item">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" placeholder="Escriba la descripción del producto" required
                           value="<?php echo htmlspecialchars($productoAActualizar->getDescripcion()); ?>">
                </div>
            </div>

            <div class="form-section">
                <div class="form-item small-item">
                    <label for="precio">Precio unitario</label>
                    <div class="price-input">
                        <span class="price-symbol">$</span>
                        <input type="text" id="precio" name="precio" placeholder="00.00" required
                               value="<?php echo htmlspecialchars($productoAActualizar->getPrecio()); ?>">
                    </div>
                </div>
            </div>

            <div class="buttons">
                <input type="hidden" name="idProducto" value="<?php echo $productoAActualizar->getIdProducto(); ?>">
                <input type="hidden" name="actualizar" value="actualizar">
                <input type="submit" class="btn btn-save" value="ACTUALIZAR">
                <a href="modificarMenu.php" class="btn btn-cancel">CANCELAR</a>
            </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>