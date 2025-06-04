<?php
// Incluye la clase Articulo y CrudArticulo
require_once('crudPedidos.php');
require_once('claseDetallesPedido.php');

// Se crea una nueva instancia de la clase CrudPedidos
$crud= new CrudPedidos();
// Se crea una nueva instancia de la clase DetallesPedido
$detallesPedido= new DetallesPedido();

	// Si el elemento insertar no viene nulo, llama al crud e inserta un articulo
	if (isset($_POST['insertar'])) {
		$articulo->setCveArticulo($_POST['cveArticulo']);
		$articulo->setDescripcion($_POST['descripcion']);
		$articulo->setDescuento($_POST['descuento']);
		$articulo->setIva($_POST['iva']);
		$articulo->setPrecio($_POST['precio']);
		// Llama a la función insertar definida en el crud
		$crud->insertar($articulo);
		header('Location: index.php');
	// Si el elemento de la vista con nombre actualizar no viene nulo, llama al crud y actualiza el articulo
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
		// Llama a la función insertar definida en el crud
		$crud->insertar($articulo);
		header('Location: mostrar.php');
	}elseif(isset($_GET['borrar'])){
		$crud->eliminar($_GET['cveArticulo']);
		header('Location: mostrar.php');
	// Si la variable accion enviada por GET es == 'e', llama al crud y elimina un articulo
	}elseif ($_GET['accion']=='e') {
		header('Location: borado.php');
	// Si la variable accion enviada por GET es == 'a', envía a la página actualizar.php
	}elseif($_GET['accion']=='a'){
		header('Location: actualizar.php');
	}
	elseif($_GET['accion']=='b'){
		header('Location: mostrarArticulo.php');
	}
?>
