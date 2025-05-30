<?php
session_start(); // Iniciar sesión para mensajes de éxito/error
require_once 'conexion.php';
require_once 'claseCategoria.php'; // Asegúrate de que esta clase exista y esté correctamente definida
require_once 'crudProductos.php'; // Asumo que CrudProductos puede manejar la inserción de categorías, si no, deberías tener un CrudCategorias.php

$mensajeError = '';


$nombreCategoria = $_POST['nombre'];

$crudProductos = new CrudProductos(); 
$exito = $crudProductos->insertarNuevaCategoria($nombreCategoria);

if ($exito) {
    $_SESSION['mensaje_exito'] = 'Categoría "' . $nombreCategoria . '" agregada con éxito.';
} else {
    $_SESSION['mensaje_error'] = 'Hubo un error al guardar la categoría en la base de datos.';
}


// Redireccionar siempre al final, ya sea por éxito o error
header('location: ../agregarCategoria.php'); // Redirecciona de vuelta al formulario de agregar categoría
exit;