<?php

require_once('../pantallas/procedimientos/conexion.php');      
require_once('../pantallas/procedimientos/claseCliente.php');  
require_once('../pantallas/procedimientos/crudClientes.php');  

// Instancia el CRUD y el modelo de cliente
$crud = new CrudClientes();
$cliente = new Cliente();

// Si hay una búsqueda, filtra los clientes por el término ingresado y crea la lista de clientes
// Si no hay búsqueda, obtiene todos los clientes
if (isset($_GET['busqueda']) && $_GET['busqueda'] != '') {
    $listaClientes = $crud->buscarClientesBusqueda($_GET['busqueda']); // Busca por nombre, apellido, etc.
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
    <div class="container">
        <div class="containerBusquedaP">
            <!-- Formulario de búsqueda de clientes -->
            <form method="GET" action="gestionClientes.php" class="containerBusqueda">
                <img src="../img/general/lupa.png" alt="busquedaLupa" height="20px" width="20px">
                <input type="text" name="busqueda" placeholder="Buscar usuario">
                <button type="submit" class="buttonConfiguracion">Buscar</button>
            </form>
            <div class="containerBusqueda">
                <!-- Botón para agregar un nuevo cliente -->
                <a href="nuevoClienteConfiguracion.php"><button class="buttonConfiguracion">Agregar</button></a>
            </div>
        </div>
        <div class="containerPerfiles">
            <!-- Recorrido de la lista de clientes tras la consulta -->
            <?php foreach ($listaClientes as $cliente){ ?>
              <div class="containerInfo">
                <div class="infoPerfil">
                    <!-- Muestra nombre completo, teléfono y calle del cliente -->
                    <div class="labelTittle"><?php echo $cliente->getNombre() . ' ' . $cliente->getPrimerApe() . ' ' . $cliente->getSegundoApe(); ?></div>
                    <div class="labelConfiguracion"><?php echo $cliente->getTelefono(); ?></div>
                    <div class="labelConfiguracion"><?php echo $cliente->getCalle(); ?></div>
                </div>
                <div class="containerBotones">
                    <!-- Botón para eliminar cliente (usa admin_Cliente.php y CrudClientes) -->
                    <a href="../pantallas/procedimientos/admin_Cliente.php?idCliente=<?php echo $cliente->getIdCliente()?>&accion=eliminar"><img src="../img/inicio/basurero.png" alt="eliminar "height= "20"></a>
                </div>
              </div>
              <hr class="linea">
            <?php }?>
        </div>    
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>