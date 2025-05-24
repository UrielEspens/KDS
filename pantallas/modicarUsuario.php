<!DOCTYPE html>
<html>
<head>
    <title>Configuración</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/verPerfil.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">VER PERFIL</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
            <ul>
            <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
            </ul>
            </div>
        </div>
    </div>
    <div class = "container">
        <div class = "containerInfoPerfil">
            <div class = "idUsuario">
                <div class = "labelTittle">ID DEL USUARIO:</div><div class = "labelConfiguracion">@idUsuario</div>
            </div>
            <div class = "nombreUsuario">
                <img src="../img/general/usuario.png" alt="usuario" height = "75" width = "75" class = "iconoUsuario">
                <div>
                    <div class = "nombreApellido">
                        <div class = "labelTittle">Nombre:</div><div class = "labelConfiguracion">@nomUsuario</div>
                    </div>
                    <div class = "nombreApellido">
                        <div class = "labelTittle">Apellido:</div><div class = "labelConfiguracion">@apeUsuario</div>
                    </div>
                </div>
            </div>
        </div>
       <hr class = "linea">
       <div class = "container-perfil">
             <div class = "nombreApellido">
                 <div class = "labelTittle">Nombre:</div><div class = "labelConfiguracion">@nomUsuario</div>
             </div>
             <div class = "nombreApellido">
                 <div class = "labelTittle">Contraseña:</div><div class = "labelConfiguracion">@contraseña</div>
             </div>
             <div class = "nombreApellido">
                 <div class = "labelTittle">Rol:</div><div class = "labelConfiguracion">@rolUsuario</div>
             </div>
       </div>
       <hr class = "linea">
       <div class = "container-perfil">
            <a href="gestionUsuarios.php"></a><button class = "buttonConfiguracion"> Cerrar sesion</button>
       </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>