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
            <div class="title"> KDS</div>
        </div>
    </div>
    <div class = "container">
        <h1>INICIO DE SESION</h1>
        <img src="../img/general/usuario.png" alt="Usuario" width="120" height="120"><br>
        <img src="../img/general/puntos.png" alt="puntos" width="100" height="38">
        <br>
        <hr>
        <?php if (isset($_GET['error'])): ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php endif; ?>
        <hr>
        <!-- Formulario de inicio de sesión -->
        <form action="procedimientos/iniciarSesion.php" method="post">

            <label for="usuario" class = "labelTittle" ><strong>Usuario:</strong></label><br>
            <input type="text" name="usuario" id="usuario"  placeholder="Usuario" required>

            <label for="contraseña" class = "labelTittle"><strong>Contraseña:</strong></label><br>
            <input type="password" name="contrasena" id="contrasena"  placeholder="Contraseña" required>

            <input type="submit" class ="btnRegistrar" value="Ingresar">
        </form>
        
    </div>
</body>
</html>