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

 //Creacion de la lista de usuarios a traves de la validacion del tipo de busqueda
 if (isset($_GET['busqueda']) && $_GET['busqueda'] != '') {
    $listaUsuarios = $crud->buscarUsuariosBusqueda($_GET['busqueda']);
}else if(isset($_GET['puesto']) && $_GET['puesto'] != '') {
        $listaUsuarios = $crud->buscarUsuariosPorPuesto($_GET['puesto']);
}else {
    $listaUsuarios = $crud->obtenerListaUsuarios();
 }
 
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
                <li><a href="historialPedidos.php"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
            </ul>
            </div>
        </div>
    </div>
    <div class = "container">
        <div class = "containerBusquedaP">
            <form method="GET" action="gestionUsuarios.php" class="containerBusqueda">
                <img src="../img/general/lupa.png" alt="busquedaLupa" height="20px" width="20px">
                <input type="text" name="busqueda" placeholder="Buscar usuario">
                <button type="submit" class="buttonConfiguracion">Buscar</button>
            </form>
            <form method="GET" action="gestionUsuarios.php" class="containerBusqueda">
                <button type="submit" class="buttonConfiguracion" name="puesto" value="chef">Chef</button>
                <button type="submit" class="buttonConfiguracion" name="puesto" value="mesero">Mesero</button>
                <button type="submit" class="buttonConfiguracion" name="puesto" value="gerente">Gerente</button>
            </form>
            <div class="containerBusqueda">
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
                        <a href="../pantallas/modificarUsuario.php?idEmpleado=<?php echo $usuario->getIdEmpleado()?>&accion=actualizar"><img src="../img/inicio/lapiz.png" alt="actualizar" height= "20"></a>
                        <a href="../pantallas/procedimientos/admin_Usuario.php?idEmpleado=<?php echo $usuario->getIdEmpleado()?>&accion=eliminar"><img src="../img/inicio/basurero.png" alt="eliminar "height= "20"></a>
                    </div>
                </div>
                <hr class = "linea">
            <?php }?>
        </div>

    </div>
</body>
</html>