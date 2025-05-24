<?php
// Incluye la clase CrudCliente y ClaseCliente utilizando rutas absolutas para mayor robustez
require_once(__DIR__ . '/crudCliente.php');
require_once(__DIR__ . '/ClaseCliente.php');

$crud = new CrudCliente();
$cliente = new Cliente();

// Se verifica si el elemento 'insertar' está presente en el POST
if (isset($_POST['insertar'])) {
    // Si 'insertar' está presente, se recogen los datos del formulario
    $cliente->setNombre($_POST['nombre']);
    $cliente->setPrimerApe($_POST['primerApe']);
    $cliente->setSegundoApe($_POST['segundoApe']);
    $cliente->setTelefono($_POST['telefono']);
    $cliente->setCalle($_POST['calle']);
    $cliente->setNumeroCasa($_POST['numeroCasa']);
    $cliente->setReferenciaCasa($_POST['referenciaCasa']);

    // Se llama al método insertar del CrudCliente para guardar el nuevo cliente
    $crud->insertar($cliente);
    header('Location: ../pantallas/nuevoCliente.php?mensaje=ClienteAgregado');
    exit();
}
// Si no es una acción de insertar, redirigir a la página principal
else {
    header('Location: ../pantallas/inicio.php');
    exit();
}
?>