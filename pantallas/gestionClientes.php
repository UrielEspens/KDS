<?php
 require_once('../pantallas/procedimientos/conexion.php');
 require_once('../pantallas/procedimientos/claseCliente.php');
 require_once('../pantallas/procedimientos/crudClientes.php');

 $crud= new CrudClientes();
 $cliente= new Cliente();   
 if(isset($_GET['busqueda']) && $_GET['busqueda'] != '') {
     $listaClientes = $crud->buscarClientesBusqueda($_GET['busqueda']);
 } else {
     $listaClientes = $crud->obtenerClientes();
 }
 ?>

<html>
<head>
    <title>Gestion clientes</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/gestionClientes.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">GESTION CLIENTES</div>
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
            <form method="GET" action="gestionClientes.php" class="containerBusqueda">
                <img src="../img/general/lupa.png" alt="busquedaLupa" height="20px" width="20px">
                <input type="text" name="busqueda" placeholder="Buscar usuario">
                <button type="submit" class="buttonConfiguracion">Buscar</button>
            </form>
            <div class = "containerBusqueda">
                <a href="nuevoClienteConfiguracion.php"><button class="buttonConfiguracion">Agregar</button></a>
            </div>
        </div>
        <div class = "containerPerfiles">
            <?php foreach ($listaClientes as $cliente){ ?>
              <div class = "containerInfo">
                <div class = "infoPerfil">
                    <div class = "labelTittle"><?php echo $cliente->getNombre() . ' ' . $cliente->getPrimerApe() . ' ' . $cliente->getSegundoApe(); ?></div>
                    <div class = "labelConfiguracion"><?php echo $cliente->getTelefono(); ?></div>
                    <div class = "labelConfiguracion"><?php echo $cliente->getCalle(); ?></div>
                </div>
                <div class = "containerBotones">
                    <a href="../pantallas/procedimientos/admin_Cliente.php?idCliente=<?php echo $cliente->getIdCliente()?>&accion=eliminar"><img src="../img/inicio/basurero.png" alt="eliminar "height= "20"></a>
                </div>
              </div>
              <hr class = "linea">
            <?php }?>
        </div>    
    </div>

    
    <script src="../js/menuOpciones.js"></script>
</body>
</html>