<?php
session_start();

require_once('procedimientos/conexion.php'); //Conexión

$nombreEmpleado = $_SESSION['nombre'];
$apellidoEmpleado = $_SESSION['primerApe'];
$puestoEmpleado = $_SESSION['puesto'];
$usuarioEmpleado = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Configuración</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/configuracion.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">CONFIGURACIÓN</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
            <ul>
                <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                <hr>
                <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                <hr>
                <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
            </ul>
            </div>
        </div>
    </div>
    <div class = "container">
       <div class = "container-perfil">
           <div class="infoPerfil">
                <img src="../img/general/usuario.png" alt="usuario" height = "45" width = "45">
                <a href="verPerfil.php"><div class = "labelConfiguracion">
                    <?php echo $nombreEmpleado . ' ' . $apellidoEmpleado; ?> <br>
                    <?php echo $puestoEmpleado; ?>
                </div></a>
           </div>
            <a href="procedimientos/cerrarSesion.php"><button class ="btn-perfil">CERRAR SESIÓN</button></a>
       </div>
       <hr class = "linea">
       <div class = "container-perfil">
           <a href="gestionUsuarios.php"><div class = "labelConfiguracion">GESTION DE USUARIOS</div> </a><br>
       </div>
       <hr class = "linea">
       <div class = "container-perfil">
           <a href="gestionClientes.php"><div class = "labelConfiguracion">GESTION DE CLIENTES</div> </a><br>
       </div>
       <hr class = "linea">
       <div class = "container-perfil">
           <a href="menu.php"><div class = "labelConfiguracion">MENU</div></a><br>
       </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>