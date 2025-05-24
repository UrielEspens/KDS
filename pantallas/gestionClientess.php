<?php
require_once('../pantallas/procedimientos/conexion.php');
require_once('../pantallas/procedimientos/claseCliente.php');
require_once('../pantallas/procedimientos/crudCliente.php');

$crud = new CrudCliente();
$listaClientes = $crud->obtenerListaClientes();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/gestionUsuarios.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">GESTIÓN DE CLIENTES</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu"> Ver menú</a></li>
                    <hr>
                    <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <?php
        if (isset($_GET['mensaje'])) {
            if ($_GET['mensaje'] == 'ClienteAgregado') {
                echo '<p style="color: green; text-align: center; font-weight: bold;">Cliente agregado exitosamente.</p><hr>';
            } elseif ($_GET['mensaje'] == 'ClienteActualizado') {
                echo '<p style="color: green; text-align: center; font-weight: bold;">Cliente actualizado exitosamente.</p><hr>';
            } elseif ($_GET['mensaje'] == 'ClienteEliminado') {
                echo '<p style="color: green; text-align: center; font-weight: bold;">Cliente eliminado exitosamente.</p><hr>';
            }
        }
        ?>

        <div class="containerBusqueda">
            <a href="nuevoCliente.php"><button class="buttonConfiguracion">AGREGAR CLIENTE</button></a>
        </div>
        
        <div class="containerPerfiles">
            <?php foreach ($listaClientes as $cliente) { ?>
                <div class="containerInfo">
                    <img src="../img/general/usuario.png" alt="cliente" height="40px" width="40px" class="iconoPerfil">
                    <div class="infoPerfil">
                        <div class="labelTittle"><?php echo $cliente->getNombre() . ' ' . $cliente->getPrimerApe(); ?></div>
                        <div class="labelConfiguracion">Teléfono: <?php echo $cliente->getTelefono(); ?></div>
                        <div class="labelConfiguracion">Dirección: <?php echo $cliente->getCalle() . ' #' . $cliente->getNumeroCasa(); ?></div>
                    </div>
                    <div class="containerBotones">
                        <a href="modificarCliente.php?idCliente=<?php echo $cliente->getIdCliente(); ?>&accion=actualizar" class="buttonConfiguracion">actualizar</a>
                        <a href="../procedimientos/admin_Cliente.php?idCliente=<?php echo $cliente->getIdCliente(); ?>&accion=eliminar" class="buttonConfiguracion" onclick="return confirm('¿Está seguro de que desea eliminar a este cliente?');">eliminar</a>
                    </div>
                </div>
                <hr class="linea">
            <?php } ?>
        </div>
    </div>

    <script src="../js/menuOpciones.js"></script>
</body>
</html>