<?php
require_once('procedimientos/crudUsuarios.php');
require_once('procedimientos/claseUsuario.php');
$crud= new CrudUsuarios();
$usuario = $crud->obtenerUsuario($_GET['idEmpleado']);
?>

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
                    <div>
                        <label class = "labelTittle" for="idEmpleado">Id del usuario:</label><?php echo $usuario->getIdEmpleado()?>
                    </div>
                </div>
                <div class = "nombreUsuario">
                    <div class = "iconUsuario">
                        <img src="data:image/png;base64,<?php echo base64_encode($usuario->getImagen()); ?>" alt="Imagen de usuario" class="imagenUsuario">
                    </div>
                    <div>
                        <label class = "labelTittle" for="nombre">Nombre:</label><?php echo $usuario ->getNombre()?><br><br>
                        <label class = "labelTittle" for="primerApe">Primer pellido</label><?php echo $usuario ->getPrimerApe()?><br><br>
                        <label class = "labelTittle" for="segundoApe">Segundo apellido</label><?php echo $usuario ->getSegundoApe()?>
                    </div>
                </div>
            </div>
            <hr class = "linea">
            <div class = "container-info">
                <div>
                    <label class = "labelTittle" for="usuario">Usuario:</label><?php echo $usuario ->getUsuario()?><br><br>
                    <label for="puesto" class = "labelTittle">Puesto:</label><?php echo $usuario->getPuesto()?><br><br>
                </div>
                <div class = "containerBotones">
                    <a href="../pantallas/procedimientos/admin_Usuario.php?idEmpleado=<?php echo $usuario->getIdEmpleado()?>&accion=eliminar"><button class = "btnEliminar" onclick="location.href='eliminarUsuario.php'">Eliminar</button></a>
                    <button class = "btnCancelar" onclick="location.href='gestionUsuarios.php'">Cancelar</button>
                </div>
            </div>   
    <script src="../js/menuOpciones.js"></script>
</body>
</html>
