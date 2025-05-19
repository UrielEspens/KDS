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

    function bloquearScroll() {
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden'; // Para navegadores que no soportan body
    }

    function desbloquearScroll() {
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
    }

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
            bloquearScroll(); // Bloquear el scroll al mostrar los detalles
            fondoDifuminado.style.display = 'flex';

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
        desbloquearScroll(); // Desbloquear el scroll al cerrar los detalles
    });

    // Cerrar el modal al hacer clic fuera del contenedor
    fondoDifuminado.addEventListener('click', function(event) {
        if (event.target === fondoDifuminado) {
            fondoDifuminado.style.display = 'none';
            desbloquearScroll(); // Desbloquear el scroll al cerrar el modal
        }
    });
});