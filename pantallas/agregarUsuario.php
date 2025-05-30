<!DOCTYPE html>
<html>
<head>
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/agregarUsuario.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">AGREGAR USUARIO</div>
        </div>
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
    </div>
    <div class="container">
        <div>
            <img src="../img/general/puntos.png" alt="puntos" height="30px">
        </div>
        <div class="container2">
            <form action="../pantallas/procedimientos/admin_Usuario.php" method="POST" enctype="multipart/form-data">
                    <div>
                        <label for="nombreUsuario" class = "labelTittle">Nombre usuario:</label><br>
                        <input type="text" id="nombreUsuario" name="nombreUsuario"  class = "input" required>
                    </div>
                    <div>
                        <label for="nombre" class = "labelTittle">Nombre:</label><br>
                        <input type="text" id="nombre" name="nombre" class = "input" required>
                    </div>
                    <div>
                        <label for="PrimerApe" class = "labelTittle">Primer apellido:</label><br>
                        <input type="text" id="PrimerApe" name="primerApe" class = "input" required>
                    </div>
                    <div>
                        <label for="telefono" class = "labelTittle">Segundo apellido:</label><br>
                        <input type="text" id="segundoApe" name="segundoApe" class = "input" required>
                    </div>
                    <div>
                        <label for="puesto" class = "labelTittle">Puesto:</label><br>
                        <select id="text" name="puesto"  class = "input"required>
                            <option  value="Mesero">Mesero</option>
                            <option value="Chef">Chef</option>
                            <option value="Cajero">Cajero</option>
                            <option value="Gerente">Gerente</option>
                        </select>
                    </div>
                    <div>
                        <label for="contrasena" class = "labelTittle">Contraseña:</label><br>
                        <input type="text" id="contrasena" class = "input" name="contrasena" required>
                    </div>
                    </div>
                    <div class = "containerImagen">
                        <label for="imagen" class = "containerPerfil"><img src="../img/general/Usuario.png" alt="Perfil" height="100px"></label><br>
                        <input type="file" id="imagen" class = "input" name="imagen" accept="image/jpeg, image/png, image/webp" required>
                    </div>    
                        <input type='hidden' name='insertar' value='insertar'>
		                <input class = "btnRegistrar" type='submit' value='Registrar'>
                    </div>
            </form>
        </div>
    </div>
    
    <script src="../js/menuOpciones.js"></script>
</body>
</html>