<!DOCTYPE html>
<html>
<head>
    <title>nombrePantalla</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/gestionUsuarios.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">nombrePantalla</div>
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
    <div class = "container">
        <div class = "containerBusquedaP">
            <div class = "containerBusqueda">
                <img src="../img/general/lupa.png" alt="busquedaLupa" height="20px" wight="20px" >
                <input type="text" placeholder="Buscar usuario" class="busqueda">
                <button class="buttonConfiguracion">Buscar</button>
            </div>
            <div class = "containerBusqueda">
                <button class="buttonConfiguracion">Chef</button>
                <button class="buttonConfiguracion">Mesero</button>
                <button class="buttonConfiguracion">Gerente</button>
                <button class="buttonConfiguracion">AGREGAR</button>
            </div>
        </div>
        <div class = "containerPerfiles">
              <div class = "containerInfo">
                <img src="../img/general/usuario.png" alt="usuario" height="40px" wight="40px" class="iconoPerfil">
                <div class = "infoPerfil">
                    <div class = "labelTittle">@nombrePerfil</div>
                    <div class = "labelConfiguracion">@puestoPerfil</div>
                </div>
                <div class = "containerBotones">  
                    <img src="../img/inicio/lapiz.png" alt="editar" height="20px" wight="20px" class="iconoEditar">
                    <img src="../img/inicio/basurero.png" alt="borrar" height="20px" wight="20px" class="iconoEliminar">
                </div>
              </div>
              <hr class = "linea">
              <div class = "containerInfo">
                <img src="../img/general/usuario.png" alt="usuario" height="40px" wight="40px" class="iconoPerfil">
                <div class = "infoPerfil">
                    <div class = "labelTittle">@nombrePerfil</div>
                    <div class = "labelConfiguracion">@puestoPerfil</div>
                </div>
                <div class = "containerBotones">  
                    <a href=""><img src="../img/inicio/lapiz.png" alt="editar" height="20px" wight="20px" class="iconoEditar"></a>
                    <a href=""><img src="../img/inicio/basurero.png" alt="borrar" height="20px" wight="20px" class="iconoEliminar"></a>
                </div>
              </div>
        </div>

    </div>

    
    <script src="../js/menuOpciones.js"></script>
</body>
</html>