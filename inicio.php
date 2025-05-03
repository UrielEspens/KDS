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
        <!-- Orden 1 -->
        
        
        <div class="order-card">
        <button class="botonStatus">
            <div class="status-indicator status-red"></div>
        </button>
        <button class="botonDetalles">
            <div class="order-details">
            <div class="order-number">No. Pedido: 1</div>
            <div class="customer-name">nombreCliente</div>
            <div class="check-icon"></div>
            </div>
        </button>
        <button class="botonCheck">
            <img src="img/controlar.png" alt="Listo" width="25px" height="27px"></a>
        </button> 
        
        </div>

        <div class="order-card">
        <button class="botonStatus">
            <div class="status-indicator status-yellow"></div>
        </button>
        <button class="botonDetalles">
            <div class="order-details">
            <div class="order-number">No. Pedido: 2</div>
            <div class="customer-name">nombreCliente</div>
            <div class="check-icon"></div>
            </div>
        </button>
        <button class="botonCheck">
            <img src="img/controlar.png" alt="Listo" width="25px" height="27px"></a>
        </button> 
        
        </div>

        <div class="order-card">
        <button class="botonStatus">
            <div class="status-indicator status-green"></div>
        </button>
        <button class="botonDetalles">
            <div class="order-details">
            <div class="order-number">No. Pedido: 3</div>
            <div class="customer-name">nombreCliente</div>
            <div class="check-icon"></div>
            </div>
        </button>
        <button class="botonCheck">
            <img src="img/controlar.png" alt="Listo" width="25px" height="27px"></a>
        </button> 
        
        </div>

        <div class="order-card">
        <button class="botonStatus">
            <div class="status-indicator status-red"></div>
        </button>
        <button class="botonDetalles">
            <div class="order-details">
            <div class="order-number">No. Pedido: 1</div>
            <div class="customer-name">nombreCliente</div>
            <div class="check-icon"></div>
            </div>
        </button>
        <button class="botonCheck">
            <img src="img/controlar.png" alt="Listo" width="25px" height="27px"></a>
        </button> 
        
        </div>

        <div class="order-card">
        <button class="botonStatus">
            <div class="status-indicator status-red"></div>
        </button>
        <button class="botonDetalles">
            <div class="order-details">
            <div class="order-number">No. Pedido: 1</div>
            <div class="customer-name">nombreCliente</div>
            <div class="check-icon"></div>
            </div>
        </button>
        <button class="botonCheck">
            <img src="img/controlar.png" alt="Listo" width="25px" height="27px"></a>
        </button> 
        
        </div>

        <div class="order-card">
        <button class="botonStatus">
            <div class="status-indicator status-yellow"></div>
        </button>
        <button class="botonDetalles">
            <div class="order-details">
            <div class="order-number">No. Pedido: 1</div>
            <div class="customer-name">nombreCliente</div>
            <div class="check-icon"></div>
            </div>
        </button>
        <button class="botonCheck">
            <img src="img/controlar.png" alt="Listo" width="25px" height="27px"></a>
        </button> 
        
    
        
        
    </div>
</body>
</html>