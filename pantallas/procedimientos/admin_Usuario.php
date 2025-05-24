<?php
//incluye la clase Usuarios y CrudUsuarios
require_once('crudUsuarios.php');
require_once('ClaseUsuario.php');

$crud= new CrudUsuarios();
$usuario= new Empleado();

	// si el elemento insertar no viene nulo llama al crud e inserta un articulo
	if (isset($_POST['insertar'])) {
        $usuario->setUsuario($_POST['nombreUsuario']);  
        $usuario->setContrasena($_POST['contrasena']);
        $usuario->setNombre($_POST['nombre']);
        $usuario->setPrimerApe($_POST['primerApe']);
        $usuario->setSegundoApe($_POST['segundoApe']);
        $usuario->setPuesto($_POST['puesto']);
        $usuario->setImagen($_POST['imagen']);

		$crud->insertar($usuario);
		header('Location: ../pantallas/agregarUsuario.php');
	
    }else if (ISSET($_POST['actualizar'])){
        $usuario->setUsuario($_POST['nombreUsuario']);  
        $usuario->setContraseña($_POST['contraseña']);
        $usuario->setNombre($_POST['nombre']);
        $usuario->setPrimerApe($_POST['primerApe']);
        $usuario->setSegundoApe($_POST['segundoApe']);
        $usuario->setPuesto($_POST['puesto']);
        $usuario->setImagen($_POST['imagen']);

        $crud->actualizar($usuario);
        header('Location: agregarUsuario.php');
    }

    else if(isset($_POST['listar'])){
        $usuarios=$crud->obtenerListaUsuarios();
        header('Location: agregarUsuario.php');

    }
    else if(isset($_POST['buscar'])){
        $idEmpleado=$_POST['idEmpleado'];
        $usuario=$crud->obtenerUsuario($idEmpleado);
        header('Location: agregarUsuario.php');

    }
    else if ($_GET['accion'] == 'eliminar') {
        $idEmpleado = $_GET['idEmpleado'];
        $crud->eliminar($idEmpleado);
        header('Location: ../pantallas/gestionUsuarios.php');
        exit();
    }
    //Muestra los datos del empleado a traves del id 
    else if($_GET['accion'] == 'b'){
        $idEmpleado = $_GET['idEmpelado'];
        $crud -> obtenerUsuario($idEmpleado);
        header('Location: verPerfil.php');
    }
    else {
        header('Location: agregarUsuario.php');
    }