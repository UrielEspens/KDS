<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}
$usuarioPuesto = $_SESSION['puesto'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Cliente</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/nuevoCliente.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">AGREGAR CLIENTE</div>
        </div>
        <?php if ($usuarioPuesto == 'Gerente'): ?>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
            <ul>
            <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                <li><a href="historialPedidos.php"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
            </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="form-container">
        <form action="../pantallas/procedimientos/admin_Cliente.php" method="POST">
            <div class="form-row">
                <div class="form-col">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Escriba el nombre" required>
                </div>
                <div class="form-col">
                    <label for="primerApe">Primer apellido</label>
                    <input type="text" id="primerApe" name="primerApe" placeholder="Escriba el primer apellido" required>
                </div>
                <div class="form-col">
                    <label for="segundoApe">Segundo apellido</label>
                    <input type="text" id="segundoApe" name="segundoApe" placeholder="Escriba segundo apellido" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="calle">Calle</label>
                    <input type="text" id="calle" name="calle" placeholder="Escriba la calle" required>
                </div>
                <div class="form-col smaller-col">
                    <label for="numeroCasa">Número</label>
                    <input type="text" id="numeroCasa" name="numeroCasa" placeholder="Escriba el número" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="referenciaCasa">Cruzamientos y/o referencia</label>
                    <input type="text" id="referenciaCasa" name="referenciaCasa" placeholder="Escriba los cruzamientos y/o referencias"required>
                </div>
                <div class="form-col smaller-col">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Escriba el teléfono" required>
                </div>
            </div>

            <div class="buttons">
                <input type="hidden" name="insertar" value="insertar">
                <input type="submit" class="btn btn-save" value='GUARDAR'>
                <a href="carrito.php" class="btn btn-cancel">Volver</a>
            </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>