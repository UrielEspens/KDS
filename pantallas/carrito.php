<?php
session_start();
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php');
    exit();
}

$usuarioPuesto = $_SESSION['puesto'];

require_once('procedimientos/conexion.php');
require_once('procedimientos/cargarCarrito.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carrito</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/carrito.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">GENERAR PEDIDO:</div>
        </div>
        <div class="menu-container">
            <?php if ($usuarioPuesto == 'Gerente'): ?>
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
            <?php endif; ?>
        </div>
        <script src="../js/menuOpciones.js"></script>
    </div>

    <div class="carrito-cuerpo">
        <h2>Contenido de tu pedido:</h2>
        <div class="seleccionar-cliente">
            <label for="clienteSeleccion" style="margin-right: 8px;">Seleccionar Cliente:</label>
            <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                <select class="clienteSeleccion" id="clienteSeleccion" name="idCliente" required>
                    <option value="">selecciona un cliente</option>
                    <?php if (!empty($listaClientes)): ?>
                        <?php foreach ($listaClientes as $cliente): ?>
                            <option value="<?php echo $cliente->getIdCliente(); ?>"
                                <?php
                                if ((int)$clienteIdActual === (int)$cliente->getIdCliente()) {
                                    echo 'selected';
                                }
                                ?>
                            >
                                <?php echo $cliente->getNombre() . " " . $cliente->getPrimerApe() . " " . $cliente->getSegundoApe(); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay clientes registrados</option>
                    <?php endif; ?>
                </select>
                <input class="btn" type="submit" value="Seleccionar Cliente"> </form>
            <button class="btn"><a href="nuevoCliente.php">Nuevo Cliente</a></button>
        </div>
        <div class="clienteSeleccionado"><strong>Mesero: </strong> <?php echo $nombreEmpleado . ' ' . $apellidoEmpleado; ?></div>
        <p class="clienteSeleccionado"><strong>Cliente Seleccionado: </strong><?php echo $clienteNombreParaMostrar; ?></p>
        <hr class="linea">
        <?php if (!empty($_SESSION['carrito'])): ?>
            <div class="carrito-items-container">
                <?php
                $totalCarrito = 0;
                foreach ($_SESSION['carrito'] as $item):
                    $precio = $item['precio'];
                    $cantidad = $item['cantidad'];

                    $subtotalProducto = $precio * $cantidad;
                    $totalCarrito += $subtotalProducto;
                ?>
                
                    <div class="carrito-item">
                        <div class="item-contenedor-izq">
                            <div class="item-info">
                                <span class="item-cantidad">&nbsp;<?php echo $cantidad; ?>&nbsp;</span>
                                <span class="item-nombre"><?php echo $item['nombre'] ?? 'Producto Desconocido'; ?></span>
                            </div>
                            <span class="item-precio-unitario">$<?php echo number_format($precio, 2); ?> </span><br>
                        </div>

                        <div class="item-contenedor-der">
                            <span class="item-subtotal">Subtotal: $<?php echo number_format($subtotalProducto, 2); ?></span>

                            <div class="item-botones">
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="restar">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn">-</button>
                                </form>
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="sumar">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn">+</button>
                                </form>
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="borrar">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button class="btn" type="submit" style="background-color: red; color: white;">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <hr class="linea">
            <div class="carrito-resumen">
                <p class="totalPedido"><strong>Total del pedido: </strong> $<?php echo number_format($totalCarrito, 2); ?></p>
                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                    <input type="hidden" name="accionCarrito" value="guardar_pedido">
                    <input type="hidden" name="idCliente" value="<?php echo $clienteIdActual; ?>">
                    <input type="hidden" name="idEmpleado" value="<?php echo $_SESSION['idEmpleado']; ?>">
                    <?php foreach ($_SESSION['carrito'] as $item): ?>
                        <input type="hidden" name="articulos[]" value="<?php echo $item['id']; ?>">
                        <input type="hidden" name="cantidades[]" value="<?php echo $item['cantidad']; ?>">
                    <?php endforeach; ?>
                    <button class="btn" type="submit">Guardar Pedido</button>
                </form>
                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                    <input type="hidden" name="accionCarrito" value="vaciar_carrito">
                    <button class="btn" type="submit">Vaciar carrito</button>
                </form>
                <a href="menu.php"><button class="btn">Añadir producto</button></a>
            </div>
        <?php else: ?>
            <p class="carrito-vacio">Tu pedido está vacío.<a href="menu.php"><button class="btn">Añadir producto</button></a></p>
        <?php endif; ?>
    </div>
</body>
</html>