<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}
 require_once('../pantallas/procedimientos/conexion.php');
 require_once('../pantallas/procedimientos/claseUsuario.php');
 require_once('../pantallas/procedimientos/crudUsuarios.php');

 $crud= new CrudUsuarios();
 $usuario= new Empleado();
 $listaUsuarios=$crud->obtenerListaUsuarios();
 
 ?>
<html>
<head>
    <title>GestionUsuarios</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/gestionUsuarios.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">GESTION DE USUARIOS</div>
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
        <div class = "containerBusquedaP">
            <form method="GET" action="admin_Usuarios.php" class="containerBusqueda">
                <img src="../img/general/lupa.png" alt="busquedaLupa" height="20px" width="20px">
                <input type="text" name="busqueda" placeholder="Buscar usuario">
                <input type='hidden' name='busqueda' value='busqueda'>
                <button type="submit" class="buttonConfiguracion">Buscar</button>
            </form>
            <div class = "containerBusqueda">
                <button class="buttonConfiguracion">Chef</button>
                <button class="buttonConfiguracion">Mesero</button>
                <button class="buttonConfiguracion">Gerente</button>
                <a href="agregarUsuario.php"><button class="buttonConfiguracion">AGREGAR</button></a>
            </div>
        </div>
        <div class = "containerPerfiles">
            <?php foreach ($listaUsuarios as $usuario){ ?>
                <div class = "containerInfo">
                    <img src="../img/general/usuario.png" alt="usuario" height="40px" wight="40px" class="iconoPerfil">
                    <div class = "infoPerfil">
                        <div class = "labelTittle"><?php echo $usuario->getUsuario(); ?></div>
                        <div class = "labelConfiguracion"><?php echo $usuario->getPuesto(); ?></div>
                    </div>
                    <div class = "containerBotones">
                    <a href="../pantallas/modificarUsuario.php?idEmpleado=<?php echo $usuario->getIdEmpleado()?>&accion=actualizar">actualizar</a>
                    <a href="../pantallas/procedimientos/admin_Usuario.php?idEmpleado=<?php echo $usuario->getIdEmpleado()?>&accion=eliminar">eliminar</a>
                    </div>
                </div>
                <hr class = "linea">
            <?php }?>
        </div>

    </div>

    
    <script src="../js/menuOpciones.js"></script>
</body>
</html>