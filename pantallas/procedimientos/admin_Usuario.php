<?php
// Incluye la clase Usuarios y CrudUsuarios
require_once('crudUsuarios.php');
require_once('ClaseUsuario.php');

// Crea una nueva instancia de la clase CrudUsuarios
$crud= new CrudUsuarios();
// Crea una nueva instancia de la clase Empleado
$usuario= new Empleado();

	// Si el elemento insertar no viene nulo llama, al crud e inserta un articulo
	if (isset($_POST['insertar'])) {
        $usuario->setUsuario($_POST['nombreUsuario']);  
        $usuario->setContrasena($_POST['contrasena']);
        $usuario->setNombre($_POST['nombre']);
        $usuario->setPrimerApe($_POST['primerApe']);
        $usuario->setSegundoApe($_POST['segundoApe']);
        $usuario->setPuesto($_POST['puesto']);
        $usuario->setImagen(file_get_contents($_FILES['imagen']['tmp_name']));

		$crud->insertar($usuario);
        header('Location: ../gestionUsuarios.php');
        exit();
	
    }else if (isset($_POST['actualizar'])){
        $usuario->setUsuario($_POST['usuario']);  
        $usuario->setContrasena($_POST['contrasena']);
        $usuario->setNombre($_POST['nombre']);
        $usuario->setPrimerApe($_POST['primerApe']);
        $usuario->setSegundoApe($_POST['segundoApe']);
        $usuario->setPuesto($_POST['puesto']);
        $usuario->setIdEmpleado($_POST['idEmpleado']);

        // Verifica si se subió una nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name'] != '') {
            // Si se subió una nueva imagen, obtiene su contenido binario
            $usuario->setImagen(file_get_contents($_FILES['imagen']['tmp_name']));
        } else {
            // Si no se subió imagen, se conserva la anterior
            $usuarioBD = $crud->obtenerUsuario($_POST['idEmpleado']);
            $usuario->setImagen($usuarioBD->getImagen());
        }

        $crud->actualizar($usuario);
        header('Location: ../gestionUsuarios.php');
        exit();
    }
    // Verifica si la acción es 'eliminar'
    else if ($_GET['accion'] == 'eliminar') {
        $idEmpleado = $_GET['idEmpleado'];
        $crud->eliminar($idEmpleado);
        header('Location: ../gestionUsuarios.php');
        exit();
    }
    
    else {
        header('Location: agregarUsuario.php');
    }