<?php
require_once('../pantallas/procedimientos/crud.Producto.php');
require_once('../pantallas/procedimientos/claseInfoProducto.php');
require_once('../pantallas/procedimientos/crudCategoria.php');

$crudProducto = new CrudProducto();
$crudCategoria = new CrudCategoria();

$productoAEliminar = null;
$idProducto = null;

if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];
    $productoAEliminar = $crudProducto->obtenerProducto($idProducto);

    if (!$productoAEliminar) {
        header('Location: modificarMenu.php?mensaje=ProductoNoEncontrado'); // Redirige si el ID no existe
        exit();
    }
} else {
    header('Location: modificarMenu.php?mensaje=IDProductoFaltante'); // Redirige si no hay ID
    exit();
}

// Obtener el nombre de la categoría para mostrarlo
$categoriaProducto = $crudCategoria->obtenerCategoria($productoAEliminar->getIdCategoria());
$nombreCategoria = $categoriaProducto ? $categoriaProducto->getNombre() : 'Desconocida';

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
                    <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="form-container">
        <h2>¿Estás seguro de que quieres eliminar este producto?</h2><br>
        <form action="../pantallas/procedimientos/adminProducto.php" method="post">
            <div class="top-section">
                <div class="info-column">
                    <div class="form-item">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" value="<?php echo htmlspecialchars($productoAEliminar->getNombre()); ?>" readonly>
                    </div>
                    <div class="form-item">
                        <label for="categoria">Categoría</label>
                        <input type="text" id="categoria" value="<?php echo htmlspecialchars($nombreCategoria); ?>" readonly>
                    </div>
                </div>

                <div class="image-column">
                    <div class="current-image-preview">
                        <?php if ($productoAEliminar->getImagen()): ?>
                            <p>Imagen del producto:</p>
                            <img src="../pantallas/procedimientos/mostrarImagen.php?idProducto=<?php echo $productoAEliminar->getIdProducto(); ?>" alt="Imagen del producto" style="max-width: 150px; height: auto; border: 1px solid #ddd;">
                        <?php else: ?>
                            <p>No hay imagen para este producto.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item description-item">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" value="<?php echo htmlspecialchars($productoAEliminar->getDescripcion()); ?>" readonly>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item small-item">
                    <label for="precio">Precio unitario</label>
                    <div class="price-input">
                        <span class="price-symbol">$</span>
                        <input type="text" id="precio" value="<?php echo number_format($productoAEliminar->getPrecio(), 2); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="buttons">
                <input type="hidden" name="idProducto" value="<?php echo $productoAEliminar->getIdProducto(); ?>">
                <input type="hidden" name="eliminar" value="eliminar">
                <button type="submit" class="btn btn-save">ELIMINAR</button>
                <a href="modificarMenu.php" class="btn btn-cancel">CANCELAR</a>
            </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>