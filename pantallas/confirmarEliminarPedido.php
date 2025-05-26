<?php
session_start();

require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/claseDetallesPedido.php'); // O clasePedido

$idPedido = null;
$pedidoInfo = null;
$totalPedidoMostrado = 'N/A';


$idPedido = (int)$_GET['idPedido'];

$crudPedidos = new CrudPedidos();
$pedidoInfo = $crudPedidos->obtenerPedidoPorId($idPedido); 

if (isset($_GET['total'])) {
    $totalPedidoMostrado = $_GET['total'];
}

if (!$pedidoInfo) {
    // Si no se encontró el pedido, redirigir o mostrar un error
    header('Location: inicio.php?error=pedido_no_encontrado');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmar Eliminación</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/eliminarPedido.css">
    
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">CONFIRMACIÓN DE ELIMINACIÓN</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
            <ul>
                <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                <hr>
                <li><a href="historialPedidos.php"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                <hr>
                <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
            </ul>
            </div>
        </div>
        <script src="../js/menuOpciones.js"></script>
    </div>
    
    <div class="overlay"></div>

    <div class="modal-confirmacion">
        <h2>¿Estás seguro de que quieres eliminar este pedido?</h2>
        <?php if ($pedidoInfo): ?>
            <p>Se eliminará el pedido No. <?php echo $pedidoInfo->getIdPedido(); ?></p>
            <p><strong>Del cliente:</strong><?php echo $pedidoInfo->getNombreCliente() . ' ' . $pedidoInfo->getApellidoCliente(); ?></p>
            <p><strong>Total:</strong> $<?php echo $totalPedidoMostrado; ?></p> 
        <?php else: ?>
            <p>No se pudo cargar la información del pedido. ¿Deseas eliminarlo de todas formas?</p>
        <?php endif; ?>

        <div class="modal-buttons">
            <a href="detallesPedido.php?idPedido=<?php echo $idPedido; ?>" class="btn btn-cancel">Cancelar</a>
            
            <form action="procedimientos/adminPedido.php" method="post" style="display: inline-block;">
                <input type="hidden" name="accion" value="eliminar_pedido">
                <input type="hidden" name="idPedido" value="<?php echo $idPedido; ?>">
                <button type="submit" class="btn btn-delete">Eliminar</button>
            </form>
        </div>
    </div>
</body>
</html>