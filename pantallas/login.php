<!DOCTYPE html>
<html>
<head>
    <title>Inicio de sesion/login</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
    <link rel="stylesheet" href="../estilos/login.css">
</head>
<body>
    <div class="header">
        <div class="logo-title">
            <img src="../img/general/plato.png" alt="Home" height="45px">
            <div class="title"> KDS - INICIO DE SESION</div>
        </div>
    </div>
    <div class = "container">
        <h1>Inicio de sesion</h1><br><br><br>
        <img src="../img/general/usuario.png" alt="Usuario" width="120" height="120"><br>
        <img src="../img/general/puntos.png" alt="puntos" width="100" height="38">
        <br><br>

        <form action="inicio.php" method="post">

        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" id="usuario"><br><br>

        <label for="contraseña">Contraseña:</label>
        <input type="text" name="contraseña" id="contraseña"><br><br>

        <a href="inicio.php"><input type="submit" value="Ingresar"></a>
    </form>
        
    </div>
</body>
</html>