<?php
//incluye la clase Usuarios y CrudUsuarios
require_once('crudClientes.php');
require_once('ClaseCliente.php');

$crud= new crudClientes();
$usuario= new Cliente();

// si el elemento insertar no viene nulo llama al crud e inserta un articulo
if (isset($_POST['insertar'])) {
    $usuario->setNombre($_POST['nombre']);
    $usuario->setPrimerApe($_POST['primerApe']);
    $usuario->setSegundoApe($_POST['segundoApe']);
    $usuario->setTelefono($_POST['telefono']);
    $usuario->setCalle($_POST['calle']);
    $usuario->setNumeroCasa($_POST['numeroCasa']);
    $usuario->setReferenciaCasa($_POST['referenciaCasa']);

    $crud->insertar($usuario);
    header('Location: ../nuevoCliente.php');

}else if (isset($_POST['insertarConfiguracion'])) {
    $usuario->setNombre($_POST['nombre']);
    $usuario->setPrimerApe($_POST['primerApe']);
    $usuario->setSegundoApe($_POST['segundoApe']);
    $usuario->setTelefono($_POST['telefono']);
    $usuario->setCalle($_POST['calle']);
    $usuario->setNumeroCasa($_POST['numeroCasa']);
    $usuario->setReferenciaCasa($_POST['referenciaCasa']);

    $crud->insertar($usuario);
    header('Location: ../nuevoClienteConfiguracion.php');


}else if (ISSET($_POST['actualizar'])) {
    $usuario->setNombre($_POST['nombre']);
    $usuario->setPrimerApe($_POST['primerApe']);
    $usuario->setSegundoApe($_POST['segundoApe']);
    $usuario->setTelefono($_POST['telefono']);
    $usuario->setCalle($_POST['calle']);
    $usuario->setNumeroCasa($_POST['numeroCasa']);
    $usuario->setReferenciaCasa($_POST['referenciaCasa']);
    $usuario->setIdCliente($_POST['idCliente']);

    $crud->actualizar($usuario);
    header('Location: ../gestionClientes.php');
} else if ($_GET['accion'] == 'eliminar') {
    $idCliente = $_GET['idCliente'];
    $crud->eliminar($idCliente);
    header('Location: ../gestionClientes.php');
} else {
    header('Location: agregarCliente.php');
}