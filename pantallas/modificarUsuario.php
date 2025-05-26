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
        <form action="../pantallas/procedimientos/admin_Usuario.php" method="POST">
            <div class = "containerInfoPerfil">
                <div class = "idUsuario">
                    <div>
                        <label class = "labelTittle" for="idEmpleado">Id del usuario:</label><?php echo $usuario->getIdEmpleado()?>
                        <input type='hidden' name='idEmpleado' value='<?php echo $usuario->getIdEmpleado()?>'>
                    </div>
                </div>
                <div class = "nombreUsuario">
                    <img src="../img/general/usuario.png" alt="usuario" height = "75" width = "75" class = "iconoUsuario">
                    <div>
                        <label class = "labelTittle" for="nombre">Nombre:</label>
                        <input class = "labelConfiguracion" id= "nombre" name= "nombre" class= "input" type="text" value='<?php echo $usuario ->getNombre()?>'><br><br>
                        <label class = "labelTittle" for="primerApe">Primer pellido</label>
                        <input class = "labelConfiguracion" id= "primerApe" name= "primerApe" class= "input" type="text" value='<?php echo $usuario ->getPrimerApe()?>'><br><br>
                        <label class = "labelTittle" for="segundoApe">Segundo apellido</label>
                        <input class = "labelConfiguracion" id= "segundoApe" name= "segundoApe" class= "input" type="text" value='<?php echo $usuario ->getSegundoApe()?>'>
                    </div>
                </div>
            </div>
            <hr class = "linea">
            <div class = "container-info">
                <div>
                    <label class = "labelTittle" for="usuario">Usuario:</label>
                    <input class = "labelConfiguracion" id= "usuario" name= "usuario" class= "input" type="text" value='<?php echo $usuario ->getUsuario()?>'><br><br>
                    <label class = "labelTittle" for="contrasena">Contraseña</label>
                    <input class = "labelConfiguracion" id= "contrasena" name= "contrasena" class= "input" type="text" value='<?php echo $usuario ->getContrasena()?>'><br><br>
                    <label for="puesto" class = "labelTittle">Puesto:</label><?php echo $usuario->getPuesto()?>
                        <select id="text" name="puesto"  class = "input"required>
                            <option  value="mesero">Mesero</option>
                            <option value="cocinero">Chef</option>
                            <option value="admin">Cajero</option>
                            <option value="admin">Gerente</option>
                        </select><br><br>
                    <label class = "labelTittle" for="imagen">imagen:</label>
                    <input class = "labelConfiguracion" id= "imagen" name= "imagen" class= "input" type="text" value='<?php echo $usuario ->getImagen()?>'>
                    
                </div>
            </div>
            <hr class = "linea">    
            <input type='hidden' name='actualizar' value='actualizar'>
		    <input class="btnRegistrar" type="submit" value="Actualizar">
       </form>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>