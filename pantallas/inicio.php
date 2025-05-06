<!DOCTYPE html>
<html>
<head>
    <title>Centro de Órdenes - KDS</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/inicio.css">
</head>
<body>
    <div class="header">
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
                <li><a href="historialPedidos.html"><img src="../img/general/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                <li><a href="configuracion.php"><img src="../img/general/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
            </ul>
            </div>
        </div>
    </div>

    <div class="orders-container">
        <?php
        $pedidos = [
            ['id' => 1, 'cliente' => 'nombreCliente1', 'estado' => 'red'],
            ['id' => 2, 'cliente' => 'nombreCliente2', 'estado' => 'yellow'],
            ['id' => 3, 'cliente' => 'nombreCliente3', 'estado' => 'green'],
        ];

        foreach ($pedidos as $pedido): ?>
            <div class="order-card">
                <button class="botonStatus"  id="botonStatus">
                    <div class="status-indicator status-<?php echo $pedido['estado']; ?>"></div>
                </button>
                <button class="botonDetalles" data-pedido-id="<?php echo $pedido['id']; ?>">
                    <div class="order-details">
                        <div class="order-number">No. Pedido: <?php echo $pedido['id']; ?></div>
                        <div class="customer-name"><?php echo $pedido['cliente']; ?></div>
                        <div class="check-icon"></div>
                    </div>
                </button>
                <button class="botonCheck">
                    <img src="../img/inicio/controlar.png" alt="Listo" width="25px" height="27px">
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="fondoDifuminado" id="fondoDifuminado" style="display: none; justify-content: space-around;">
        <!-- Contenedor para los detalles del pedido -->
        <div id="contenedorDetallesPedido">
            <!-- Encabezado con el estado y la hora -->
            <div class="detalles-header" id="estado-header">
                <h3 id="pedido-estado-detalle"><?php echo $pedido['estado']; ?></h3>
                <div id="pedido-hora-detalle">18:48</div>
            </div>
            
            <!-- Información básica del pedido -->
            <div class="info-pedido">
                <p><strong id="pedido-numero-detalle">No. Pedido: 1</strong></p>
                <p><strong id="pedido-cliente-detalle">Cliente: nombreCliente</strong></p>
                <p><strong id="pedido-mesero-detalle">Mesero: nombreMesero</strong></p>
            </div>
            
            <!-- Lista de artículos en un textarea de solo lectura para mantener el formato -->
           
            <textarea class="lista-articulos" id="lista-articulos-detalle" style="border: none;" rows="10" cols="57"  readonly ></textarea>
            

            <!-- Total del pedido -->
            <div class="total-pedido" id="total-pedido-detalle">
                Total: $0.00
            </div>
            
            <!-- Botones de acción -->
            <div class="botones-accion">
                <button id="cerrarDetalles" class="boton-volver">VOLVER</button>
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
    <script src="../js/menuOpciones.js"></script> 
    <script src="../js/inicio.js"></script>
</body>
</html>