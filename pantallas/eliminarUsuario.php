<!DOCTYPE html>
<html>
<head>
    <title>Eliminar usuario</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/eliminarUsuario.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">Eliminar usuario</div>
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
    <div class = "container">
        <div class = "infoPerfil">
            <div class = "labelTittle">ID EMPLEADO:</div>
            <div class = "labelConfiguracion">@idEmpleado</div>
        </div>
        <div class = "containerPerfil">
            <img src="../img/general/usuario.png" alt="usuarioImagen" height="70px" class = "iconoPerfil" >
            <div class = "containerInfo">
                <div class = "infoPerfil">
                    <div class = "labelTittle">Nombre:</div>
                    <div class = "labelConfiguracion">@nombre</div>
                </div>
                <div class = "infoPerfil">
                    <div class = "labelTittle">Correo:</div>
                    <div class = "labelConfiguracion">@correo</div>
                </div>
            </div>
        </div>
        <hr class = "linea">
        <div class = "containerPerfil">
            <div class = "containerInfo2">
                <div class = "infoPerfil2">
                    <div class = "labelTittle">Usuario:</div>
                    <div class = "labelConfiguracion">@usuarioEmpleado</div>
                </div>
                <div class = "infoPerfil2">
                    <div class = "labelTittle">Contraseña:</div>
                    <div class = "labelConfiguracion">@contraseñaEmpleado</div>
                </div>
                <div class = "infoPerfil2">
                    <div class = "labelTittle">Rol:</div>
                    <div class = "labelConfiguracion">@rolMesero</div>
                </div>
            </div>
            <div class = "containerBotones">
                    <button class = "btnEliminar" onclick="location.href='eliminarUsuario.php'">Eliminar</button>
                    <button class = "btnCancelar" onclick="location.href='gestionUsuarios.php'">Cancelar</button>
                </div>
        </div>
    </div>
    
    <script src="../js/menuOpciones.js"></script>
</body>
</html>