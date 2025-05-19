<!DOCTYPE html>
<html>
<head>
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/agregarUsuario.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <a href="inicio.php"><img src="../img/general/hogar.png" alt="Home" height="30px"></a>
            <div class="title">AGREGAR USUARIO</div>
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
    <div class="container">
        <div>
            <img src="../img/general/puntos.png" alt="puntos" height="30px">
        </div>
        <div class="container2">
            <form action="../php/agregarUsuario.php" method="POST">
                <div>
                    <label for="nombre" class = "labelTittle">Usuario:</label><br>
                    <input type="text" id="nombre" name="nombre"  class = "input" required>
                </div>
                <div>
                    <label for="correo" class = "labelTittle">Nombre:</label><br>
                    <input type="email" id="correo" name="correo" class = "input" required>
                </div>
                <div>
                    <label for="telefono" class = "labelTittle">Apellido:</label><br>
                    <input type="tel" id="telefono" name="telefono" class = "input" required>
                </div>
                <div>
                    <label for="puesto" class = "labelTittle">Puesto:</label><br>
                    <select id="puesto" name="puesto"  class = "input"required>
                        <option value="mesero">Mesero</option>
                        <option value="cocinero">Chef</option>
                        <option value="admin">Cajero</option>
                        <option value="admin">Gerente</option>
                    </select>
                </div>
                <div>
                    <label for="telefono" class = "labelTittle">Contraseña:</label><br>
                    <input type="tel" id="telefono" class = "input" name="telefono" required>
                </div>
            </form>
            <div class = "containerPerfil">
                <img src="../img/general/Usuario.png" alt="Perfil" height="100px"><br><br>
                <button class = "btnRegistrar"type="submit">Agregar Usuario</button>
            </div>
            </div>
        </div>
    </div>
    
    <script src="../js/menuOpciones.js"></script>
</body>
</html>