<?php
//incluye la clase Articulo y CrudArticulo
require_once('crudPedidos.php');
require_once('claseDetallesPedido.php');

$crud= new CrudPedidos();
$detallesPedido= new DetallesPedido();

	// si el elemento insertar no viene nulo llama al crud e inserta un articulo
	if (isset($_POST['insertar'])) {
		$articulo->setCveArticulo($_POST['cveArticulo']);
		$articulo->setDescripcion($_POST['descripcion']);
		$articulo->setDescuento($_POST['descuento']);
		$articulo->setIva($_POST['iva']);
		$articulo->setPrecio($_POST['precio']);
		//llama a la función insertar definida en el crud
		$crud->insertar($articulo);
		header('Location: index.php');
	// si el elemento de la vista con nombre actualizar no viene nulo, llama al crud y actualiza el articulo
	}elseif(isset($_POST['actualizar'])){
		$articulo->setCveArticulo($_POST['cveArticulo']);
		$articulo->setDescripcion($_POST['descripcion']);
		$articulo->setDescuento($_POST['descuento']);
		$articulo->setIva($_POST['iva']);
		$articulo->setPrecio($_POST['precio']);
		$crud->actualizar($articulo);
		header('Location: mostrar.php');
	}
	elseif(isset($_POST['nuevo'])) {
		$articulo->setCveArticulo($_POST['cveArticulo']);
		$articulo->setDescripcion($_POST['descripcion']);
		$articulo->setDescuento($_POST['descuento']);
		$articulo->setIva($_POST['iva']);
		$articulo->setPrecio($_POST['precio']);
		//llama a la función insertar definida en el crud
		$crud->insertar($articulo);
		header('Location: mostrar.php');
	}elseif(isset($_GET['borrar'])){
		$crud->eliminar($_GET['cveArticulo']);
		header('Location: mostrar.php');
	// si la variable accion enviada por GET es == 'e' llama al crud y elimina un articulo
	}elseif ($_GET['accion']=='e') {
		header('Location: borado.php');
	// si la variable accion enviada por GET es == 'a', envía a la página actualizar.php
	}elseif($_GET['accion']=='a'){
		header('Location: actualizar.php');
	}
	elseif($_GET['accion']=='b'){
		header('Location: mostrarArticulo.php');
	}
?>
