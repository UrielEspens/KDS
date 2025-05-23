<?php
session_start();

// Redireccionar si el usuario no está logueado
if (!isset($_SESSION['idEmpleado'])) {
    header('Location: index.php'); // Ajusta la ruta a tu página de inicio de sesión
    exit();
}
// Inicializa el carrito en la sesión si no existe (esto es seguro repetirlo)
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [ ];
}

$nombreEmpleado = $_SESSION['nombre']; 
$apellidoEmpleado = $_SESSION['primerApe'] ;
$puestoEmpleado = $_SESSION['puesto'];
$usuarioEmpleado = $_SESSION['usuario'];

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
            <div class="title">TU CARRITO DE COMPRAS:</div>
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

    <div class="carrito-cuerpo">
        <h2>Contenido de tu Carrito:</h2>
        <div class="buscar-cliente">
            <label for="buscarCliente">Cliente</label>
            <form action="carrito.php" method="get"> <input type="text" id="buscarCliente" name="buscarCliente" placeholder="Busca cliente" value="">
                <button type="submit" value="buscarCliente">Buscar</button>
            </form>
        </div>
        
        <div>Mesero: <?php echo $nombreEmpleado . ' ' . $apellidoEmpleado; ?></div>
        <hr>
        <?php if (!empty($_SESSION['carrito'])): ?>
            <div class="carrito-items-container">
                <?php
                $totalCarrito = 0;
                foreach ($_SESSION['carrito'] as $item):
                    $precio = is_numeric($item['precio']) ? (float)$item['precio'] : 0.00;
                    $cantidad = is_numeric($item['cantidad']) ? (int)$item['cantidad'] : 0;

                    $subtotalProducto = $precio * $cantidad;
                    $totalCarrito += $subtotalProducto;
                ?>
                    <div class="carrito-item">
                        <div class="item-contenedor-izq">
                            <div class="item-info">
                                <span class="item-cantidad">&nbsp;<?php echo $cantidad; ?>&nbsp;</span>
                                <span class="item-nombre"><?php echo $item['nombre'] ?? 'Producto Desconocido'; ?></span>
                            </div>
                            <span class="item-precio-unitario">$<?php echo number_format($precio, 2); ?> MXN (c/u)</span><br>
                        </div>
                        
                        <div class="item-contenedor-der">
                            <span class="item-subtotal">Subtotal: $<?php echo number_format($subtotalProducto, 2); ?> MXN</span>

                            <div class="item-botones">
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="decrease">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button type="submit">-</button>
                                </form>
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="increase">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button type="submit">+</button>
                                </form>
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="remove">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button type="submit" style="background-color: red; color: white;">Eliminar</button>
                                </form>
                            </div>
                        </div>
                        
                        
                        
                    </div>
                <?php endforeach; ?>
            </div>
            <hr>
            <div class="carrito-resumen">
                <h3>Total del Carrito: $<?php echo number_format($totalCarrito, 2); ?> MXN</h3>
                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                    <input type="hidden" name="accionCarrito" value="guardar_pedido">
                    <button type="submit" class="boton-guardar-pedido">Guardar Pedido</button>
                </form>
                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                    <input type="hidden" name="accionCarrito" value="clear_cart">
                    <button type="submit" >Vaciar Carrito</button>
                </form>
                <a href="menu.php"><button >Añadir producto</button></a>
            </div>
        <?php else: ?>
            <p class="carrito-vacio">Tu carrito está vacío.<a href="menu.php"><button>Añadir producto</button></a></p>
        <?php endif; ?>
    </div>
</body>
</html>