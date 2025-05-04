<!DOCTYPE html>
<html>
<head>
    <title>Historial de pedidos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="img/hogar.png" alt="Home" height="30px"></a>
            <div class="title">HISTORIAL DE PEDIDOS</div>
        </div>
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
</body>
</html>