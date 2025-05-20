<?php
// inicio.php
require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/clasePedido.php');

$crud = new CrudPedidos();
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
        <script src="../js/menuOpciones.js"></script>
    </div>

    <div class="orders-container">
        <?php foreach ($listaPedidos as $pedido): ?>
            <div class="order-card">
                <button class="botonStatus" data-pedido-id="<?php echo $pedido->getIdPedido(); ?>">
                    <div class="status-indicator status-<?php echo str_replace(' ', '', $pedido->getEstado()); ?>"></div>
                </button>
                <button class="botonDetalles" data-pedido-id="<?php echo $pedido->getIdPedido(); ?>">
                    <div class="order-details">
                        <div class="order-number">No. Pedido: <?php echo $pedido->getIdPedido(); ?></div>
                        <div class="customer-name"><?php echo $pedido->getNombreCliente() . ' ' . $pedido->getApellidoCliente(); ?></div>
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
        <div id="contenedorDetallesPedido">
            <div class="detalles-header" id="estado-header">
                <h3 id="pedido-estado-detalle"></h3>
                <div id="pedido-hora-detalle"></div>
            </div>
            
            <div class="info-pedido">
                <p><strong id="pedido-numero-detalle"></strong></p>
                <p><strong id="pedido-cliente-detalle"></strong></p>
                <p><strong id="pedido-mesero-detalle"></strong></p>
            </div>
            
            <textarea class="lista-articulos" id="lista-articulos-detalle" rows="10" cols="57" readonly></textarea>
            
            <div class="total-pedido" id="total-pedido-detalle"></div>
            
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selecciones de elementos del DOM
        const botonesDetalles = document.querySelectorAll('.botonDetalles');
        const fondoDifuminado = document.getElementById('fondoDifuminado');
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

        // Objeto para almacenar los estados modificados de los pedidos (si aún lo usas)
        const estadosModificados = {};

        function bloquearScroll() {
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
        }

        function desbloquearScroll() {
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
        }

        // Lógica para los botones de STATUS
        botonesStatus.forEach(botonStatus => {
            botonStatus.addEventListener('click', function() {
                const pedidoId = this.getAttribute('data-pedido-id'); 
                if (!pedidoId) {
                    console.error("No se encontró data-pedido-id en el botón de status.");
                    return;
                }

                const statusIndicator = this.querySelector('.status-indicator');
                if (statusIndicator) {
                    let nuevoEstadoParaClase = ''; 
                    let nuevoEstadoParaTexto = '';  

                    statusIndicator.classList.remove('status-Pendiente', 'status-Enpreparacion', 'status-Listo');

                    if (estadosModificados.hasOwnProperty(pedidoId)) {
                        const estadoActual = estadosModificados[pedidoId];
                        if (estadoActual === 'Pendiente') {
                            nuevoEstadoParaClase = 'Enpreparacion';
                            nuevoEstadoParaTexto = 'En preparacion';
                        } else if (estadoActual === 'En preparacion') {
                            nuevoEstadoParaClase = 'Listo';
                            nuevoEstadoParaTexto = 'Listo';
                        } else if (estadoActual === 'Listo') {
                            nuevoEstadoParaClase = 'Pendiente';
                            nuevoEstadoParaTexto = 'Pendiente';
                        } else { 
                            nuevoEstadoParaClase = 'Pendiente';
                            nuevoEstadoParaTexto = 'Pendiente';
                        }
                    } else {
                        // Si el estado no está en estadosModificados (primera vez haciendo clic o recargado)
                        // Obtén el estado actual del atributo de clase, si existe
                        const currentClass = Array.from(statusIndicator.classList).find(cls => cls.startsWith('status-'));
                        if (currentClass === 'status-Pendiente') {
                            nuevoEstadoParaClase = 'Enpreparacion';
                            nuevoEstadoParaTexto = 'En preparacion';
                        } else if (currentClass === 'status-Enpreparacion') {
                            nuevoEstadoParaClase = 'Listo';
                            nuevoEstadoParaTexto = 'Listo';
                        } else if (currentClass === 'status-Listo') {
                            nuevoEstadoParaClase = 'Pendiente';
                            nuevoEstadoParaTexto = 'Pendiente';
                        } else { 
                            // Si no tiene ninguna clase de estado inicial, y no está en estadosModificados, 
                            // intenta leer el estado del PHP o asume 'Pendiente'
                            // Por ahora, asumimos que siempre tiene una clase inicial del PHP si es una carga fresca.
                            // Si realmente no hay ninguna clase de estado en el HTML, podrías poner un valor predeterminado aquí.
                            nuevoEstadoParaClase = 'Pendiente'; 
                            nuevoEstadoParaTexto = 'Pendiente';
                        }
                    }

                    statusIndicator.classList.add(`status-${nuevoEstadoParaClase}`);
                    estadosModificados[pedidoId] = nuevoEstadoParaTexto;

                    console.log(`Pedido ${pedidoId}: Estado cambiado a ${nuevoEstadoParaTexto}`);
                    // Aquí es donde enviarías el FETCH/AJAX para actualizar la DB si lo necesitas
                    // fetch('actualizar_estado_pedido.php', { method: 'POST', body: JSON.stringify({ id: pedidoId, estado: nuevoEstadoParaTexto }) })
                    // .then(response => response.json())
                    // .then(data => console.log('Estado actualizado en BD:', data))
                    // .catch(error => console.error('Error al actualizar estado:', error));
                }
            });
        });


        // Lógica para los botones de DETALLES (fetch y mostrar modal)
        botonesDetalles.forEach(boton => {
            boton.addEventListener('click', function() {
                const pedidoId = this.getAttribute('data-pedido-id');
                console.log(`Clic en botón Detalles para Pedido ID: ${pedidoId}`); // Depuración

                // Mostrar el fondo difuminado/modal inmediatamente
                bloquearScroll();
                fondoDifuminado.style.display = 'flex'; 

                // Limpiar contenido previo y mostrar mensaje de carga
                pedidoNumeroDetalle.textContent = 'Cargando...';
                pedidoClienteDetalle.textContent = '';
                pedidoMeseroDetalle.textContent = '';
                pedidoEstadoDetalle.textContent = '';
                pedidoHoraDetalle.textContent = '';
                listaArticulosDetalle.value = "Cargando detalles...";
                totalPedidoDetalle.textContent = '';
                estadoHeader.className = 'detalles-header'; // Resetear clases
                
                // Realizar la petición Fetch
                fetch(`procedimientos/obtener_detalles_pedido.php?id=${pedidoId}`)
                    .then(response => {
                        console.log('Respuesta recibida del servidor (objeto Response):', response); 
                        if (!response.ok) {
                            // Si la respuesta no es OK (ej. 404, 500), intenta leer como texto para depurar
                            return response.text().then(text => {
                                console.error(`Error HTTP: ${response.status}, Respuesta (texto):`, text); 
                                throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                            });
                        }
                        return response.json(); // Esperamos JSON
                    })
                    .then(data => {
                        console.log("Datos recibidos del servidor (JSON parseado):", data); 

                        if (data && !data.error) { // Verificar si hay datos y no es un error explícito del PHP
                            // Actualizar información básica del pedido en el modal
                            pedidoNumeroDetalle.textContent = `No. Pedido: ${data.id}`;
                            pedidoClienteDetalle.textContent = `Cliente: ${data.cliente}`;
                            pedidoMeseroDetalle.textContent = `Mesero: ${data.mesero}`;
                            pedidoHoraDetalle.textContent = data.hora || '--:--';

                            // Lógica para el estado y color del encabezado del modal
                            let estadoParaMostrar = data.estado; 
                            // Si el estado fue modificado en el front-end por el botón de status, usa ese
                            if (estadosModificados.hasOwnProperty(pedidoId)) {
                                estadoParaMostrar = estadosModificados[pedidoId];
                                console.log(`Estado para mostrar (modificado por JS): ${estadoParaMostrar}`); 
                            } else {
                                console.log(`Estado para mostrar (de BD): ${estadoParaMostrar}`); 
                            }

                            // Limpiar clases de estado anteriores y aplicar la nueva
                            estadoHeader.className = 'detalles-header'; 
                            if (estadoParaMostrar === 'En preparacion') {
                                pedidoEstadoDetalle.textContent = 'En preparación';
                                estadoHeader.classList.add('header-enpreparacion'); 
                            } else if (estadoParaMostrar === 'Listo') {
                                pedidoEstadoDetalle.textContent = 'Listo';
                                estadoHeader.classList.add('header-listo');
                            } else { // Si es 'Pendiente' o cualquier otro valor
                                pedidoEstadoDetalle.textContent = 'Pendiente';
                                estadoHeader.classList.add('header-pendiente');
                            }

                            // Formatear y mostrar artículos en textarea
                            let listaFormateada = '';
                            if (data.articulos && data.articulos.length > 0) {
                                data.articulos.forEach(item => {
                                    const cantidad = item.cantidad !== undefined ? item.cantidad : 'X';
                                    const nombre = item.nombreProducto || 'Artículo desconocido';
                                    const precio = item.precio !== undefined ? `$${parseFloat(item.precio).toFixed(2)}` : '$0.00';

                                    const lineaBase = `${cantidad} ${nombre}`;
                                    const espacioDisponible = 60 - lineaBase.length - precio.length;
                                    const rellenoGuiones = '-'.repeat(Math.max(1, espacioDisponible)); 
                                    
                                    listaFormateada += `${lineaBase} ${rellenoGuiones} ${precio}\n`;
                                });
                            } else {
                                listaFormateada = 'No hay artículos en este pedido.';
                            }
                            listaArticulosDetalle.value = listaFormateada;

                            // Actualizar total
                            totalPedidoDetalle.textContent = `Total: $${parseFloat(data.total || '0').toFixed(2)}`;

                        } else {
                            console.warn('Datos vacíos o con error recibidos en el bloque .then():', data);
                            alert('No se encontraron detalles para este pedido o hubo un error en los datos.');
                            fondoDifuminado.style.display = 'none'; 
                            desbloquearScroll();
                        }
                    })
                    .catch(error => {
                        console.error('Error en el fetch de detalles del pedido (bloque .catch()):', error);
                        listaArticulosDetalle.value = `Error al cargar los detalles del pedido: ${error.message}`;
                        fondoDifuminado.style.display = 'none'; 
                        desbloquearScroll();
                    });
            });
        });

        // Cerrar el modal al hacer clic en el botón de volver
        if (botonCerrar) {
            botonCerrar.addEventListener('click', function() {
                fondoDifuminado.style.display = 'none';
                desbloquearScroll();
            });
        }

        // Cerrar el modal al hacer clic fuera del contenedor
        if (fondoDifuminado) {
            fondoDifuminado.addEventListener('click', function(event) {
                if (event.target === fondoDifuminado) { 
                    fondoDifuminado.style.display = 'none';
                    desbloquearScroll();
                }
            });
        }
    });
    </script>
</body>
</html>