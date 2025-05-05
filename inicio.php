<!DOCTYPE html>
<html>
<head>
    <title>Centro de Órdenes - KDS</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="img/hogar.png" alt="Home" height="30px"></a>
            <div class="title">CENTRO DE ORDENES</div>
        </div>
        <a href="menu.php" class="add-button">Añadir pedido</a>
        <div class="menu-container">
            <button id="toggleMenu">
                <img src="img/menuOpciones.png" alt="Opciones" height="30px">
            </button>
            <div id="menu">
            <ul>
                <li><a href="menu.php"><img src="img/menuRestaurante.png" alt="menuComida" class="icono-menu">  Ver menú</a></li>
                <li><a href="historialPedidos.php"><img src="img/historialPedidos.png" alt="historialPedidos" class="icono-menu"> Historial de pedidos</a></li>
                <li><a href="configuracion.php"><img src="img/configuracion.png" alt="configuracion" class="icono-menu"> Configuración</a></li>
            </ul>
            </div>
            <script>
                const btn = document.getElementById("toggleMenu");
                const menu = document.getElementById("menu");

                btn.addEventListener("click", () => {
                if (menu.style.display === "none" || menu.style.display === "") {
                    menu.style.display = "block";
                } else {
                    menu.style.display = "none";
                }
                });
            </script>
        </div>
    </div>

    <div class="orders-container">
        <?php
        $pedidos = [
            ['id' => 1, 'cliente' => 'nombreCliente1', 'estado' => 'red'],
            ['id' => 2, 'cliente' => 'nombreCliente2', 'estado' => 'yellow'],
            ['id' => 3, 'cliente' => 'nombreCliente3', 'estado' => 'green'],
            ['id' => 4, 'cliente' => 'nombreCliente4', 'estado' => 'red'],
            ['id' => 5, 'cliente' => 'nombreCliente5', 'estado' => 'red'],
            ['id' => 6, 'cliente' => 'nombreCliente6', 'estado' => 'yellow'],
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
                    <img src="img/controlar.png" alt="Listo" width="25px" height="27px">
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
                        <img src="img/lapiz.png" alt="Editar" width="24px">
                    </button>
                    <button class="eliminarDetalles">
                        <img src="img/basurero.png" alt="Eliminar" width="24px">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script> 
        document.addEventListener('DOMContentLoaded', function() {
            const botonesDetalles = document.querySelectorAll('.botonDetalles');
            const fondoDifuminado = document.getElementById('fondoDifuminado');
            const contenedorDetallesPedido = document.getElementById('contenedorDetallesPedido');
            const botonCerrar = document.getElementById('cerrarDetalles');
            const pedidoNumeroDetalle = document.getElementById('pedido-numero-detalle');
            const pedidoClienteDetalle = document.getElementById('pedido-cliente-detalle');
            const pedidoMeseroDetalle = document.getElementById('pedido-mesero-detalle');
            const pedidoEstadoDetalle = document.getElementById('pedido-estado-detalle');
            const pedidoHoraDetalle = document.getElementById('pedido-hora-detalle');
            const listaArticulosDetalle = document.getElementById('lista-articulos-detalle');
            const totalPedidoDetalle = document.getElementById('total-pedido-detalle');
            const estadoHeader = document.getElementById('estado-header');
            const botonesStatus = document.querySelectorAll('.botonStatus');

            // Objeto para almacenar los estados modificados de los pedidos
            const estadosModificados = {};

            botonesStatus.forEach(botonStatus => {
                botonStatus.addEventListener('click', function() {
                    const pedidoId = this.nextElementSibling.getAttribute('data-pedido-id'); // Obtener el ID del pedido del botón de detalles hermano
                    const statusIndicator = this.querySelector('.status-indicator');
                    if (statusIndicator) {
                        let nuevoEstado;
                        if (statusIndicator.classList.contains('status-red')) {
                            statusIndicator.classList.remove('status-red');
                            statusIndicator.classList.add('status-yellow');
                            nuevoEstado = 'yellow';
                        } else if (statusIndicator.classList.contains('status-yellow')) {
                            statusIndicator.classList.remove('status-yellow');
                            statusIndicator.classList.add('status-green');
                            nuevoEstado = 'green';
                        } else if (statusIndicator.classList.contains('status-green')) {
                            statusIndicator.classList.remove('status-green');
                            statusIndicator.classList.add('status-red');
                            nuevoEstado = 'red';
                        }

                        // Guardar el nuevo estado en el objeto estadosModificados
                        if (pedidoId) {
                            estadosModificados[pedidoId] = nuevoEstado;
                        }
                    }
                });
            });

            botonesDetalles.forEach(boton => {
                boton.addEventListener('click', function() {
                    const pedidoId = this.getAttribute('data-pedido-id');

                    // Mostrar indicador de carga
                    listaArticulosDetalle.value = "Cargando detalles...";

                    fetch(`obtener_detalles_pedido.php?id=${pedidoId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al cargar los datos');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data) {
                                // Actualizar información básica
                                pedidoNumeroDetalle.textContent = `No. Pedido: ${data.id}`;
                                pedidoClienteDetalle.textContent = `Cliente: ${data.cliente}`;
                                pedidoMeseroDetalle.textContent = `Mesero: ${data.mesero}`;

                                let estadoParaMostrar = data.estado;
                                // Verificar si hay un estado modificado para este pedido
                                if (estadosModificados.hasOwnProperty(pedidoId)) {
                                    estadoParaMostrar = estadosModificados[pedidoId];
                                }

                                // Actualizar estado y cambiar color del encabezado Y TEXTO DEL ESTADO
                                pedidoEstadoDetalle.textContent = estadoParaMostrar || 'Pendiente';
                                estadoHeader.className = 'detalles-header';

                                // Cambiar texto del estado según el valor
                                if (estadoParaMostrar === 'yellow') {
                                    pedidoEstadoDetalle.textContent = 'En preparación';
                                    estadoHeader.classList.add('header-preparacion');
                                } else if (estadoParaMostrar === 'green') {
                                    pedidoEstadoDetalle.textContent = 'Listo';
                                    estadoHeader.classList.add('header-listo');
                                } else if (estadoParaMostrar === 'red') {
                                    pedidoEstadoDetalle.textContent = 'Pendiente'; // O el texto que prefieras para 'red'
                                    estadoHeader.classList.add('header-pendiente');
                                } else {
                                    pedidoEstadoDetalle.textContent = 'Pendiente'; // Estado por defecto si no coincide
                                    estadoHeader.classList.add('header-pendiente');
                                }

                                // Actualizar hora
                                pedidoHoraDetalle.textContent = data.hora || '--:--';

                                // Formatear y mostrar artículos en textarea
                                let listaFormateada = '';

                                if (data.articulos && data.articulos.length > 0) {
                                    data.articulos.forEach(item => {
                                        // Calcular espacios para alineación
                                        const nombrePrecio = `${item.cantidad} ${item.nombre} ${item.precio}`;
                                        const espacios = '-'.repeat(Math.max(5, 65 - nombrePrecio.length));

                                        listaFormateada += `${item.cantidad} ${item.nombre} ${espacios} ${item.precio}\n`;
                                    });

                                    
                                } else {
                                    listaFormateada = 'No hay artículos en este pedido';
                                }

                                listaArticulosDetalle.value = listaFormateada;

                                // Actualizar total
                                totalPedidoDetalle.textContent = `Total: $${data.total}`;

                                // Mostrar el modal
                                fondoDifuminado.style.display = 'flex';
                            } else {
                                alert('No se encontraron detalles para este pedido.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            listaArticulosDetalle.value = "Error al cargar los detalles del pedido.";
                        });
                });
            });

            // Cerrar el modal al hacer clic en el botón de volver
            botonCerrar.addEventListener('click', function() {
                fondoDifuminado.style.display = 'none';
            });

            // Cerrar el modal al hacer clic fuera del contenedor
            fondoDifuminado.addEventListener('click', function(event) {
                if (event.target === fondoDifuminado) {
                    fondoDifuminado.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>