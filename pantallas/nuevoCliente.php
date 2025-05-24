<!DOCTYPE html>
<html lang="es">
<head>
    <title>Nuevo Cliente</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/nuevoCliente.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">NUEVO CLIENTE</div>
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
        <?php
        // Mensajes de éxito o error (opcional)
        if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'ClienteAgregado') {
            echo '<p style="color: green; text-align: center; font-weight: bold;">Cliente agregado exitosamente.</p><hr>';
        }
        ?>
        <form action="../pantallas/procedimientos/admin_Cliente.php" method="POST">
            <div class="form-row">
                <div class="form-col">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Escriba el nombre" required>
                </div>
                <div class="form-col">
                    <label for="primerApellido">Primer apellido</label>
                    <input type="text" id="primerApellido" name="primerApe" placeholder="Escriba el primer apellido" required>
                </div>
                <div class="form-col">
                    <label for="segundoApellido">Segundo apellido</label>
                    <input type="text" id="segundoApellido" name="segundoApe" placeholder="Escriba segundo apellido">
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="calle">Calle</label>
                    <input type="text" id="calle" name="calle" placeholder="Escriba la calle" required>
                </div>
                <div class="form-col smaller-col">
                    <label for="numero">Número</label>
                    <input type="text" id="numero" name="numeroCasa" placeholder="Escriba el número" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="cruzamientos">Cruzamientos y/o referencia</label>
                    <input type="text" id="cruzamientos" name="referenciaCasa" placeholder="Escriba los cruzamientos y/o referencias">
                </div>
                <div class="form-col smaller-col">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Escriba el teléfono" required>
                </div>
            </div>

            <div class="buttons">
                <input type="hidden" name="insertar" value="insertar">
                <input type="submit" class="btn btn-save" value='GUARDAR'>
                <a href="carrito.php" class="btn btn-cancel">CANCELAR</a>
            </div>
        </form>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>