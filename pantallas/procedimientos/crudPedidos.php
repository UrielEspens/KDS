<?php
require_once('conexion.php');

	class CrudPedidos{
		// constructor de la clase
		public function __construct(){}

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

		public function mostrarDetalles($idPedido){
			$db = Db::conectar(); // Obtiene la conexión PDO
			$select = $db->prepare(' SELECT pedido.idPedido,
											pedido.estado,
											cliente.nombre AS nombreCliente,
											cliente.primerApe AS apellidoCliente,
											empleados.nombre AS nombreEmpleado,
											empleados.primerApe AS apellidoEmpleado,
											pedido.fechaHora AS horaCreacion
											FROM
												pedido
											INNER JOIN cliente ON pedido.idCliente = cliente.idCliente
											INNER JOIN empleados ON pedido.idEmpleado = empleados.idEmpleado
											WHERE
												pedido.idPedido = :idPedido;' );
			$select->bindValue(':idPedido', $idPedido);
			$select->execute();

			$detallePedido = $select->fetch();

			// Si no se encontró ningún pedido, $detallePedido será false
			if (!$detallePedido) {
				return null; // O false, o lanza una excepción, según tu manejo de errores
			}

			// 6. Crea y popula el objeto DetallesPedido
			$myPedido = new DetallesPedido();
			$myPedido->setIdPedido($detallePedido['idPedido']);
			$myPedido->setEstado($detallePedido['estado']);
			$myPedido->setNombreCliente($detallePedido['nombreCliente']);
			$myPedido->setApellidoCliente($detallePedido['apellidoCliente']);
			$myPedido->setNombreEmpleado($detallePedido['nombreEmpleado']);
			$myPedido->setApellidoEmpleado($detallePedido['apellidoEmpleado']);
			$myPedido->setHoraCreacion($detallePedido['horaCreacion']);
			
			return $myPedido;
		}

		public function obtenerProductosDePedido($idPedido){
				$db = Db::conectar();
				$listaArticulos = []; // Aquí almacenaremos los objetos ArticuloPedido
				$select = $db->prepare('SELECT
													prod.nombre AS nombreProducto,
													prod.precio AS precioProducto,
													dp.cantidad AS cantidadProducto
												FROM
													detalles_pedido dp
												INNER JOIN producto prod ON dp.idProducto = prod.idProducto
												WHERE
													dp.idPedido = :idPedido;');
				$select->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
				$select->execute();

				foreach ($select->fetchAll() as $articuloData) {
					$myArticulo = new Producto();
					$myArticulo->setNombreProducto($articuloData['nombreProducto']);
					$myArticulo->setPrecioProducto($articuloData['precioProducto']);
					$myArticulo->setCantidadProducto($articuloData['cantidadProducto']);
					$listaArticulos[] = $myArticulo; // Añadimos el objeto a la lista
				}

				return $listaArticulos; // Retornamos el array de objetos ArticuloPedido
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
