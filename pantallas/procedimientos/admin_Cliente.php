<?php
// Incluye los archivos necesarios para la clase CrudClientes y Cliente
require_once('crudClientes.php');
require_once('ClaseCliente.php');
// Se crea una nueva instancia de la clase CrudClientes
$crud= new crudClientes();
// Se crea una nueva instancia de la clase Cliente
$usuario= new Cliente();

// Si el elemento insertar no viene nulo, llama al crud e inserta un articulo
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

} // Verifica si se envió el formulario con la acción 'insertarConfiguracion'
else if (isset($_POST['insertarConfiguracion'])) {
    $usuario->setNombre($_POST['nombre']);
    $usuario->setPrimerApe($_POST['primerApe']);
    $usuario->setSegundoApe($_POST['segundoApe']);
    $usuario->setTelefono($_POST['telefono']);
    $usuario->setCalle($_POST['calle']);
    $usuario->setNumeroCasa($_POST['numeroCasa']);
    $usuario->setReferenciaCasa($_POST['referenciaCasa']);

    $crud->insertar($usuario);
    header('Location: ../nuevoClienteConfiguracion.php');


} // Verifica si se envió el formulario con la acción 'actualizar'
else if (ISSET($_POST['actualizar'])) {
    $usuario->setNombre($_POST['nombre']);
    $usuario->setPrimerApe($_POST['primerApe']);
    $usuario->setSegundoApe($_POST['segundoApe']);
    $usuario->setTelefono($_POST['telefono']);
    $usuario->setCalle($_POST['calle']);
    $usuario->setNumeroCasa($_POST['numeroCasa']);
    $usuario->setReferenciaCasa($_POST['referenciaCasa']);
    $usuario->setIdCliente($_POST['idCliente']);

    // Se llama al método 'actualizar' del objeto $crud para modificar el cliente en la base de datos
    $crud->actualizar($usuario);
    header('Location: ../gestionClientes.php');
} // Verifica si la acción es 'eliminar' 
else if ($_GET['accion'] == 'eliminar') {
    $idCliente = $_GET['idCliente'];
    $crud->eliminar($idCliente);
    header('Location: ../gestionClientes.php');
} else {
    // Si no se reconoce ninguna acción, redirige a la página 'agregarCliente.php'
    header('Location: agregarCliente.php');
}