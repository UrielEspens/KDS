<?php
require_once('../pantallas/procedimientos/crudCategoria.php');

$crudCategoria = new CrudCategoria();
$listaCategorias = $crudCategoria->obtenerListaCategorias();

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
                    <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
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
                        <input type="text" id="nombre" name="nombre" placeholder="Nombre del producto" required>
                    </div>
                    <div class="form-item">
                        <label for="idCategoria">Categoría</label>
                        <select id="idCategoria" name="idCategoria" required>
                            <?php foreach ($listaCategorias as $categoria): ?>
                                <option value="<?php echo $categoria->getIdCategoria(); ?>">
                                    <?php echo htmlspecialchars($categoria->getNombre()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="image-column">
                    <div class="form-item image-upload-item">
                        <label for="imagen_file">Imagen</label>
                        <input type="file" id="imagen_file" name="imagen_file" accept="image/png" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item description-item">
                    <label for="descripcion">Descripción</label>
                    <input type="text" id="descripcion" name="descripcion" placeholder="Escriba la descripción del producto" required>
                </div>
            </div>

            <div class="form-section">
                <div class="form-item small-item">
                    <label for="precio">Precio unitario</label>
                    <div class="price-input">
                        <span class="price-symbol">$</span>
                        <input type="text" id="precio" name="precio" placeholder="00.00" required>
                    </div>
                </div>
            </div>

            <div class="buttons">
                <input type="hidden" name="insertar" value="insertar">
                <input type="submit" class="btn btn-save" value="GUARDAR">
                <a href="modificarMenu.php" class="btn btn-cancel">CANCELAR</a> </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>