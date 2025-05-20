<?php
// incluye la clase Db
require_once('conexion.php');

	class CrudPedidos{
		// constructor de la clase
		public function __construct(){}

		public function obtenerDetallesPedido($idPedido){
        $db=Db::conectar();
        $listaDetalles = [];

        // Obtener la información principal del pedido
        $selectPedido = $db->prepare('SELECT  	p.idPedido,
												p.estado,
												p.fechaHora AS horaCreacion,
												c.nombre AS nombreCliente,
												c.primerApe AS apellidoCliente,
												e.nombre AS nombreEmpleado,
												e.primerApe AS apellidoEmpleado
												FROM pedido p
												INNER JOIN cliente c ON p.idCliente = c.idCliente
												INNER JOIN empleado e ON p.idEmpleado = e.idEmpleado
												WHERE p.idPedido = :idPedido');
			$selectPedido->bindValue(':idPedido', $idPedido);
			$selectPedido->execute();
			$pedido = $selectPedido->fetch(PDO::FETCH_ASSOC);

			if ($pedido) {
				$listaDetalles['pedido'] = $pedido;

				// Obtener los artículos del pedido
				$selectArticulos = $db->prepare('SELECT
														dp.cantidad,
														prod.nombre AS nombreProducto,
														prod.precio AS precio
													FROM detalles_pedido dp
													INNER JOIN producto prod ON dp.idProducto = prod.idProducto
													WHERE dp.idPedido = :idPedido
				');
				$selectArticulos->bindValue(':idPedido', $idPedido);
				$selectArticulos->execute();
				$listaDetalles['productos'] = $selectArticulos->fetchAll(PDO::FETCH_ASSOC);

				return $listaDetalles;
			} else {
				return null; // Devolver null si no se encuentra el pedido
			}
		}
        // Search
		public function obtenerArticulo($idProducto){
			$db=Db::conectar();
			$select=$db->prepare('SELECT * FROM producto WHERE idProducto=:idProducto');
			$select->bindValue('idProducto',$idProducto);
			$select->execute();

			$producto=$select->fetch();
			$myProducto= new Producto();
			$myProducto->setIdProducto($producto['idProducto']);
			$myProducto->setNombre($producto['nombre']);
			$myProducto->setDescripcion($producto['descripcion']);
			$myProducto->setIdCategoria($producto['idCategoria']);
			$myProducto->setPrecio($producto['precio']);
			$myProducto->setImagen($producto['imagen']);
			return $myProducto;
		}

		// Create
		public function insertar($producto){
			$db=Db::conectar();
			$insert=$db->prepare('INSERT INTO producto values(:idProducto, :nombre, :descripcion, :idCategoria, :precio, :imagen)');
			$insert->bindValue('idProducto',$producto->getIdProducto());
			$insert->bindValue('nombre',$producto->getNombre());
			$insert->bindValue('descripcion',$producto->getDescripcion());
			$insert->bindValue('idCategoria',$producto->getIdCategoria());
			$insert->bindValue('precio',$producto->getPrecio());
			$insert->bindValue('imagen',$producto->getImagen());
			$insert->execute();
		}

        public function mostrarPedidos(){
		$db=Db::conectar();
		$listaPedidos=[];
		$select=$db->query('SELECT  p.idPedido,
									p.estado,
									c.nombre AS nombreCliente,
									c.primerApe AS apellidoCliente
							FROM pedido p
							INNER JOIN cliente c ON p.idCliente = c.idCliente;');

		foreach($select->fetchAll() as $pedido){
			$myPedido= new Pedido();
			$myPedido->setIdPedido($pedido['idPedido']);
			$myPedido->setEstado($pedido['estado']);
			$myPedido->setNombreCliente($pedido['nombreCliente']);
			$myPedido->setApellidoCliente($pedido['apellidoCliente']);
			$listaPedidos[]=$myPedido;
		}
		return $listaPedidos;
	}

		public function mostrarDetalles(){ 
			$db=Db::conectar();
			$listaPedidos=[];
			$select=$db->query('SELECT 	p.idPedido,
										p.estado,
										c.nombre AS nombreCliente,
										c.primerApe AS apellidoCliente
										FROM pedido p
										INNER JOIN cliente c ON p.idCliente = c.idCliente;');

			foreach($select->fetchAll() as $detallePedido){
				$myPedido= new DetallesPedido();
				$myPedido->setIdPedido($detallePedido['idPedido']);
                $myPedido->setEstado($detallePedido['estado']);
                $myPedido->setNombreCliente($detallePedido['nombreCliente']);
                $myPedido->setApellidoCliente($detallePedido['apellidoCliente']);
				$listaPedidos[]=$myPedido;
			}
			return $listaPedidos;
		}


		// Read
		public function mostrarProducto(){ 
			$db=Db::conectar();
			$listaProductos=[];
			$select=$db->query('SELECT * FROM producto');

			foreach($select->fetchAll() as $producto){
				$myProducto= new Producto();
				$myProducto->setIdProducto($producto['idProducto']);
				$myProducto->setNombre($producto['nombre']);
				$myProducto->setDescripcion($producto['descripcion']);
                $myProducto->setIdCategoria($producto['idCategoria']);
				$myProducto->setPrecio($producto['precio']);
                $myProducto->setImagen($producto['imagen']);
				$listaProductos[]=$myProducto;
			}
			return $listaProductos;
		}











		// Delete
		public function eliminar($cveArticulo){
			$db=Db::conectar();

			// Eliminar de la tabla existencias
			$eliminarExistencia = $db->prepare('DELETE FROM existencias WHERE cveArticulo = :cveArticulo');
            $eliminarExistencia->bindValue('cveArticulo', $cveArticulo);
            $eliminarExistencia->execute();

			$eliminar=$db->prepare('DELETE FROM articulos WHERE cveArticulo=:cveArticulo');
			$eliminar->bindValue('cveArticulo',$cveArticulo);
			$eliminar->execute();
		}

		

		// Update
		public function actualizar($articulo){
			$db=Db::conectar();
			$actualizar=$db->prepare('UPDATE articulos SET descripcion=:descripcion, 
			descuento=:descuento, iva=:iva, precio=:precio  WHERE cveArticulo=:cveArticulo');
			$actualizar->bindValue('cveArticulo',$articulo->getCveArticulo());
			$actualizar->bindValue('descripcion',$articulo->getDescripcion());
			$actualizar->bindValue('descuento',$articulo->getDescuento());
			$actualizar->bindValue('iva',$articulo->getIva());
			$actualizar->bindValue('precio',$articulo->getPrecio());
			$actualizar->execute();
		}
	}
?>
