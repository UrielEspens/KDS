<?php
// iniciarSesion.php
session_start();

require_once('conexion.php');


if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {

    
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    
    $db = Db::conectar();
    $select = $db->prepare('SELECT * FROM empleados WHERE usuario = :usuario AND contrasena = :contrasena');
    $select->bindValue(':usuario', $usuario);
    $select->bindValue(':contrasena', $contrasena);
    $select->execute();

    if ($select->rowCount() > 0) {
        $empleado = $select->fetch();
        $_SESSION['idEmpleado'] = $empleado['idEmpleado'];
        $_SESSION['usuario'] = $empleado['usuario'];
        $_SESSION['nombre'] = $empleado['nombre'];
        $_SESSION['primerApe'] = $empleado['primerApe'];
        $_SESSION['segundoApe'] = $empleado['segundoApe'];
        $_SESSION['puesto'] = $empleado['puesto'];
        $_SESSION['imagen'] = $empleado['imagen'];
        header('Location: ../inicio.php');
        exit();
    } else {
        header('Location: ../index.php?error=Usuario o contraseña incorrectos');
        exit();
    }

    
}
?>