<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}
require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/clasePedido.php');

$crud = new CrudPedidos();
$pedido = new Pedido();
$listaPedidos = $crud->mostrarPedidos();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Centro de Órdenes - KDS</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/inicio.css">
</head>
<body>
    <div class="header" id="miCabecera">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">CENTRO DE ORDENES</div>
        </div>
        <a href="menu.php" class="add-button">Añadir pedido</a>
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

    <div class="orders-container">
        <?php foreach ($listaPedidos as $pedido): ?>
            <div class="order-card">
                <form action="procedimientos/cambioEstado.php" method="post">
                    <input type="hidden" name="idPedido" value="<?php echo $pedido->getIdPedido(); ?>">
                    <input type="hidden" name="estado" value="<?php echo $pedido->getEstado(); ?>">
                    <button type="submit" class="indicador-estado estado-<?php echo $pedido->getEstado(); ?>">
                        <p></p>
                    </button>
                </form>
                <a href="detallesPedido.php?idPedido=<?php echo $pedido->getIdPedido(); ?>" class="botonDetalles">
                <button class="botonDetalles" id="<?php echo $pedido->getIdPedido(); ?>">
                    <div class="order-details">
                        <div class="order-number">No. Pedido: <?php echo $pedido->getIdPedido(); ?></div>
                        <div class="customer-name"><?php echo $pedido->getNombreCliente() . ' ' . $pedido->getApellidoCliente(); ?></div>
                        <div class="check-icon"></div>
                    </div>
                </button>
                </a>
                <button class="botonCheck">
                    <img src="../img/inicio/controlar.png" alt="Listo" width="25px" height="27px">
                </button>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>