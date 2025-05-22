<?php
session_start();

// ¡Se recomienda renombrar este archivo a detallesPedido.php!
require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/claseDetallesPedido.php');
require_once('procedimientos/conexion.php');
require_once('procedimientos/claseProducto.php');
require_once('procedimientos/crudProductos.php');

$crud = new CrudProductos();
$pedidoActual = new DetallesPedido();
$idProducto = null;

// Validar si el ID existe y es un número entero
if (isset($_GET['idProducto']) && is_numeric($_GET['idProducto'])) {
    $idProducto = (int)$_GET['idProducto']; // Convertir a entero para mayor seguridad
    $producto = $crud->obtenerProductoPorId($idProducto);
}else{
    echo "No se encontró el producto";
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo ($pedidoActual ? 'Detalles de Pedido #' . $pedidoActual->getIdPedido() : 'Tu Carrito de Compras'); ?></title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/carrito.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title"><?php echo ($pedidoActual ? 'DETALLES DE PEDIDO: ' . $pedidoActual->getIdPedido() : 'CARRITO DE COMPRAS'); ?></div>
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
        <script src="../js/menuOpciones.js"></script>
    </div>

    <div class="carrito-body">
        <?php if ($pedidoActual): // Muestra los detalles del pedido si se está viendo uno guardado ?>
            <div class="fondoDifuminado" id="fondoDifuminado">
                <div id="contenedorDetallesPedido">
                    <div class="detalles-header header-<?php echo strtolower($pedidoActual->getEstado()); ?>" id="estado-header">
                        <h3 id="pedido-estado-detalle"><?php echo strtoupper($pedidoActual->getEstado()); ?></h3>
                        <div id="pedido-hora-detalle"><?php echo (new DateTime($pedidoActual->getHoraCreacion()))->format('H:i'); ?></div>
                    </div>

                    <div class="info-pedido">
                        <p><strong id="pedido-numero-detalle">No. Pedido: </strong><?php echo $pedidoActual->getIdPedido(); ?></p>
                        <p><strong id="pedido-cliente-detalle">Cliente: </strong><?php echo $pedidoActual->getNombreCliente() . ' ' . $pedidoActual->getApellidoCliente(); ?></p>
                        <p><strong id="pedido-mesero-detalle">Mesero: </strong><?php echo $pedidoActual->getNombreEmpleado() . ' ' . $pedidoActual->getApellidoEmpleado(); ?></p>
                    </div>

                    <textarea class="lista-articulos" id="lista-articulos-detalle" rows="10" cols="57" readonly><?php
                        $listaArticulosTexto = "";
                        if (is_array($pedidoActual->getArticulos())) {
                            foreach ($pedidoActual->getArticulos() as $articuloObjeto) {
                                $cantidad = $articuloObjeto->getCantidadProducto();
                                $nombre = $articuloObjeto->getNombreProducto();
                                $precio = $articuloObjeto->getPrecioProducto();
                                $totalPorArticulo = $cantidad * $precio;
                                $listaArticulosTexto .= $cantidad . '     ' . $nombre . '  -  $' . number_format($totalPorArticulo, 2) . "\n";
                            }
                        } else {
                            $listaArticulosTexto = "No hay artículos en este pedido.";
                        }
                        echo trim($listaArticulosTexto);
                    ?></textarea>

                    <div class="total-pedido" id="total-pedido-detalle">Total: $<?php echo (number_format($pedidoActual->getTotal(), 2)); ?></div>

                    <div class="botones-accion">
                        <a href="inicio.php"><button id="cerrarDetalles" class="boton-volver">VOLVER</button></a>
                        <div class="botones-iconos">
                            <button class="editarDetalles">
                                <img src="../img/inicio/lapiz.png" alt="Editar" width="24px">
                            </button>
                            <button class="eliminarDetalles">
                                <img src="../img/inicio/basurero.png" alt="Eliminar" width="24px">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: // Muestra el carrito si no se está viendo un pedido guardado ?>
            <h2>Contenido de tu Carrito:</h2>
            <?php if (!empty($_SESSION['carrito'])): ?>
                <div class="carrito-items-container">
                    <?php
                    $totalCarrito = 0;
                    foreach ($_SESSION['carrito'] as $item):
                        $subtotalProducto = $item['precio'] * $item['cantidad'];
                        $totalCarrito += $subtotalProducto;
                    ?>
                        <div class="carrito-item">
                            <span class="item-nombre"><?php echo ($item['nombre']); ?></span>
                            <span class="item-cantidad">Cantidad: <?php echo $item['cantidad']; ?></span>
                            <span class="item-precio-unitario">$<?php echo number_format($item['precio'], 2); ?> MXN (c/u)</span>
                            <span class="item-subtotal">Subtotal: $<?php echo number_format($subtotalProducto, 2); ?> MXN</span>

                            <form action="carrito.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="accionCarrito" value="decrease">
                                <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                <button type="submit">-</button>
                            </form>
                            <form action="carrito.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="accionCarrito" value="increase">
                                <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                <button type="submit">+</button>
                            </form>
                            <form action="carrito.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="accionCarrito" value="remove">
                                <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                <button type="submit" style="background-color: red; color: white;">Eliminar</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="carrito-resumen">
                    <h3>Total del Carrito: $<?php echo number_format($totalCarrito, 2); ?> MXN</h3>
                    <form action="carrito.php" method="post" style="display: inline-block;">
                        <input type="hidden" name="accionCarrito" value="guardar_pedido">
                        <button type="submit" class="boton-guardar-pedido">GUARDAR PEDIDO</button>
                    </form>
                    <form action="carrito.php" method="post" style="display: inline-block;">
                        <input type="hidden" name="accionCarrito" value="clear_cart">
                        <button type="submit" style="background-color: orange;">Vaciar Carrito</button>
                    </form>
                </div>
            <?php else: ?>
                <p>Tu carrito está vacío. <a href="menu.php">¡Añade algunos productos!</a></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>