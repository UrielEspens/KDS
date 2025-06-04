<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header("location:../error/index.php");
    exit;
}
require_once 'procedimientos/conexion.php';
require_once 'procedimientos/claseCategoria.php';
require_once 'procedimientos/crudProductos.php';

// Instanciar CrudProductos para obtener la lista de categorías
$crudProductos = new CrudProductos();
$categorias = $crudProductos->obtenerCategorias();

// Inicializar variables para mensajes
$mensajeExito = '';
$mensajeError = '';

// Verificar si hay mensajes en la sesión y borrarlos después de mostrarlos
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
    <title>Agregar categoría</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/agregarCategoria.css">    
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">AGREGAR CATEGORÍA</div>
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

        <form action="procedimientos/procesarAgregarCategoria.php" method="POST" enctype="multipart/form-data">
            <div class="top-section">
                <div class="info-column">
                    <div class="form-item">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Escriba el nombre" value="" required>
                    </div>
                </div>
            </div>

            <div class="buttons">
                <button type="submit" class="btn btn-save">Guardar</button>
                <a href="modificarMenu.php" class="btn btn-cancel">Volver</a>
            </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>