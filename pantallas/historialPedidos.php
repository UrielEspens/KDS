<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}

require_once('procedimientos/conexion.php');
require_once('procedimientos/clasePedido.php');
require_once('procedimientos/crudPedidos.php');

$crudPedidos = new CrudPedidos();
$listaPedidos = [];

// Obtener parámetros de filtro
$idOrden = isset($_GET['idOrden']) ? $_GET['idOrden'] : null;
$desdeFecha = isset($_GET['desdeFecha']) ? $_GET['desdeFecha'] : null;
$hastaFecha = isset($_GET['hastaFecha']) ? $_GET['hastaFecha'] : null;
$tipoPago = isset($_GET['tipoPago']) ? $_GET['tipoPago'] : null;
$nombreClienteBusqueda = isset($_GET['nombreCliente']) ? $_GET['nombreCliente'] : null;

// Si hay parámetros de búsqueda, usar buscarPedidosParaHistorial, de lo contrario, obtenerTodosLosPedidosParaHistorial
if ($idOrden || $desdeFecha || $hastaFecha || ($tipoPago && $tipoPago !== 'todos') || $nombreClienteBusqueda) {
    $listaPedidos = $crudPedidos->buscarPedidosParaHistorial($idOrden, $desdeFecha, $hastaFecha, $tipoPago, $nombreClienteBusqueda);
} else {
    $listaPedidos = $crudPedidos->obtenerTodosLosPedidosParaHistorial();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Historial de pedidos</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/historialPedidos.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">HISTORIAL DE PEDIDOS</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu"> Ver menú</a></li>
                    <hr>
                    <li><a href="historialPedidos.php"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <hr>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
        </div>
    </div>
    <form method="GET" action="historialPedidos.php">
        <div class="filters-container">
            <div class="filter-group">
                <label class="filter-label" for="desde-fecha">Desde</label>
                <input type="date" class="date-input" id="desde-fecha" name="desdeFecha" value="<?php echo $desdeFecha; ?>">
            </div>

            <div class="filter-group">
                <label class="filter-label" for="hasta-fecha">Hasta</label>
                <input type="date" class="date-input" id="hasta-fecha" name="hastaFecha" value="<?php echo $hastaFecha; ?>">
            </div>

            <div class="filter-group">
                <label class="filter-label" for="tipo-pago">Tipo de pago</label>
                <select class="filter-input" id="tipo-pago" name="tipoPago">
                    <option value="todos" <?php echo ($tipoPago == 'todos' || $tipoPago == null) ? 'selected' : ''; ?>>Todos</option>
                    <option value="efectivo" <?php echo ($tipoPago == 'efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                    <option value="transferencia" <?php echo ($tipoPago == 'transferencia') ? 'selected' : ''; ?>>Transferencia</option>
                    <option value="tarjeta" <?php echo ($tipoPago == 'tarjeta') ? 'selected' : ''; ?>>Tarjeta</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label" for="id-orden">ID de orden</label>
                <input type="text" class="order-id-input" id="id-orden" name="idOrden" placeholder="escriba el ID" value="<?php echo $idOrden; ?>">
            </div>

            <div class="filter-group">
                <label class="filter-label" for="nombre-cliente">Cliente</label>
                <input type="text" class="order-id-input" id="nombre-cliente" name="nombreCliente" placeholder="Nombre o Apellido" value="<?php echo $nombreClienteBusqueda; ?>">
            </div>


            <div class="filter-actions">
                <div class="buttons-container">
                    <button type="submit" class="btn btn-search">BUSCAR</button>
                    <button type="button" class="btn btn-clear" onclick="window.location.href='historialPedidos.php'">LIMPIAR</button>
                </div>
            </div>
        </div>
    </form>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Orden</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Tipo de pago</th>
                    <th>Cliente</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listaPedidos)): ?>
                    <?php foreach ($listaPedidos as $pedido): ?>
                        <tr>
                            <td><?php echo $pedido->getIdPedido(); ?></td>
                            <td><?php echo $pedido->getFechaHora(); ?></td>
                            <td>$<?php echo number_format($pedido->getMontoTotal(), 2); ?></td>
                            <td><?php echo $pedido->getTipoPago(); ?></td>
                            <td><?php echo $pedido->getNombreCliente() . ' ' . $pedido->getApellidoCliente(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No se encontraron pedidos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="../js/menuOpciones.js"></script>
</body>
</html>