<?php
// iniciarSesion.php
session_start(); // Inicia la sesión para poder usar variables de sesión

require_once('conexion.php'); // Incluye la conexión a la base de datos

// Verifica si se recibieron los datos del formulario de inicio de sesión
if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {

    // Obtiene los datos enviados por el formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    
    // Conecta a la base de datos y prepara la consulta para buscar el usuario
    $db = Db::conectar();
    $select = $db->prepare('SELECT * FROM empleados WHERE usuario = :usuario AND contrasena = :contrasena');
    $select->bindValue(':usuario', $usuario); // Asigna el valor del usuario al parámetro
    $select->bindValue(':contrasena', $contrasena); // Asigna el valor de la contraseña al parámetro
    $select->execute(); // Ejecuta la consulta

    // Si se encontró un usuario con esos datos
    if ($select->rowCount() > 0) {
        $empleado = $select->fetch(); // Obtiene los datos del usuario
        // Guarda los datos del usuario en variables de sesión
        $_SESSION['idEmpleado'] = $empleado['idEmpleado'];
        $_SESSION['usuario'] = $empleado['usuario'];
        $_SESSION['nombre'] = $empleado['nombre'];
        $_SESSION['primerApe'] = $empleado['primerApe'];
        $_SESSION['segundoApe'] = $empleado['segundoApe'];
        $_SESSION['puesto'] = $empleado['puesto'];
        $_SESSION['imagen'] = $empleado['imagen'];
        // Redirige al usuario a la página de inicio
        header('Location: ../inicio.php');
        exit();
    } else {
        // Si no se encontró el usuario, redirige al login con mensaje de error
        header('Location: ../index.php?error=Usuario o contraseña incorrectos');
        exit();
    }
}
?>