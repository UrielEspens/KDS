<?php
session_start();

// Acciones: reducir o eliminar
if (isset($_GET['accion']) && isset($_GET['producto'])) {
    $producto = $_GET['producto'];

    if ($_GET['accion'] === 'reducir') {
        if (isset($_SESSION['carrito'][$producto]['cantidad']) && $_SESSION['carrito'][$producto]['cantidad'] > 1) {
            $_SESSION['carrito'][$producto]['cantidad']--;
        } else {
            unset($_SESSION['carrito'][$producto]);
        }
    }

    if ($_GET['accion'] === 'eliminar') {
        unset($_SESSION['carrito'][$producto]);
    }

    // Redirigir para evitar reenvío de formulario
    header('Location: carrito.php');
    exit;
}

$carrito = $_SESSION['carrito'] ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Carrito de Pedidos</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/carrito.css">
</head>
<body>
    <div class="header" id="miCabecera">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">CARRITO DE PEDIDOS</div>
        </div>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="../img/general/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
                <ul>
                    <li><a href="menu.php"><img src="../img/general/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                    <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                    <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="carrito-container">
        <h2>Tu Carrito</h2>
        <a href="menu.php" class="volver-menu">← Volver al menú</a>
        <hr class="separador">

        <?php if (empty($carrito)): ?>
            <p class="carrito-vacio">El carrito está vacío.</p>
        <?php else: ?>
            <table class="carrito-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($carrito as $nombre => $info): ?>
                        <?php
                            $cantidad = $info['cantidad'] ?? 0;
                            $subtotal = $info['precio'] * $cantidad;
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td class="producto-nombre"><?= htmlspecialchars($nombre) ?></td>
                            <td class="producto-precio">$<?= $info['precio'] ?></td>
                            <td class="producto-cantidad">
                                <div class="cantidad-control">
                                    <a href="?accion=reducir&producto=<?= urlencode($nombre) ?>" class="boton-accion">-</a>
                                    <span class="cantidad"><?= $cantidad ?></span>
                                    </div>
                            </td>
                            <td class="producto-subtotal">$<?= number_format($subtotal, 2) ?></td>
                            <td class="producto-acciones">
                                <a href="?accion=eliminar&producto=<?= urlencode($nombre) ?>" class="boton-eliminar">🗑️ Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="total-label"><strong>Total:</strong></td>
                        <td colspan="2" class="total-amount"><strong>$<?= number_format($total, 2) ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="finalizar-pedido">
                            <button class="boton-finalizar">Finalizar Pedido</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>
    </div>

    <script src="../js/menuOpciones.js"></script>
</body>
</html>