<?php
require_once('procedimientos/crudProductos.php'); 
require_once('procedimientos/claseInfoProducto.php'); 
require_once('procedimientos/claseCategoria.php'); 
require_once('procedimientos/crudPedidos.php');
require_once('procedimientos/clasePedido.php');

$crud = new CrudPedidos();
$pedido = new Producto();
$listaPedidos = $crud->mostrarPedidos();
$crudProductos = new CrudProductos();

$resultadosBusqueda = [];
$mostrarResultadosBusqueda = false; 

// Lógica de búsqueda
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $textoBusqueda = $_GET['buscar'];
    $tipoBusqueda = 'inicio'; // Fijo a 'inicio'
    
    // Llama al método de búsqueda del CrudProductos
    $resultadosBusqueda = $crudProductos->buscarProductos($textoBusqueda, $tipoBusqueda);
    $mostrarResultadosBusqueda = true;
}

// Obtener todas las categorías y productos si no hay búsqueda activa o para la vista inicial
if (!$mostrarResultadosBusqueda) {
    $categorias = $crudProductos->obtenerCategorias();
    $todosLosProductos = $crudProductos->obtenerTodosLosProductos();
}
?>