<?php
require_once('conexion.php');
require_once('clasePedido.php');
require_once('claseDetallesPedido.php');
require_once('claseProducto.php');
require_once('claseCliente.php');
require_once('claseEmpleados.php');

class CrudPedidos{
    // constructor de la clase
    public function __construct(){}

    public function eliminarPedido($idPedido) {
        $db = Db::conectar();
        // Eliminar de detalles_pedido
        $deleteItems = $db->prepare('DELETE FROM detalles_pedido WHERE idPedido = :idPedido');
        $deleteItems->bindParam(':idPedido', $idPedido);
        $deleteItems->execute();

        $deleteHistorySummary = $db->prepare('DELETE FROM historial_pedidos WHERE idPedidoFinal = :idPedido');
        $deleteHistorySummary->bindParam(':idPedido', $idPedido);
        $deleteHistorySummary->execute();


        // Luego, eliminar el pedido principal
        $deletePedido = $db->prepare('DELETE FROM pedido WHERE idPedido = :idPedido');
        $deletePedido->bindParam(':idPedido', $idPedido);
        return $deletePedido->execute();
    }


    public function mostrarPedidos(){
        $db=Db::conectar();
        $listaPedidos=[];
        $select=$db->query('SELECT p.idPedido,
                                   p.estado,
                                   c.nombre AS nombreCliente,
                                   c.primerApe AS apellidoCliente,
                                   e.nombre AS nombreEmpleado,
                                   e.primerApe AS apellidoEmpleado
                            FROM pedido p
                            INNER JOIN cliente c ON p.idCliente = c.idCliente
                            INNER JOIN empleados e ON p.idEmpleado = e.idEmpleado;');

        foreach($select->fetchAll(PDO::FETCH_ASSOC) as $pedidoData){
            $myPedido = new Pedido();
            $myPedido->setIdPedido($pedidoData['idPedido']);
            $myPedido->setEstado($pedidoData['estado']);
            $myPedido->setNombreCliente($pedidoData['nombreCliente']);
            $myPedido->setApellidoCliente($pedidoData['apellidoCliente']);
            $myPedido->setNombreEmpleado($pedidoData['nombreEmpleado']);
            $myPedido->setApellidoEmpleado($pedidoData['apellidoEmpleado']);
            $listaPedidos[] = $myPedido;
        }
        return $listaPedidos;
    }

		public function mostrarDetalles($idPedido){
			$db = Db::conectar();
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

			if (!$detallePedido) {
				return null;
			}

			// Crea y popula el objeto DetallesPedido
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
		
		public function obtenerPedidoCompletoParaPago($idPedido) {
			$db = Db::conectar();

			// Consulta principal para obtener datos del pedido, cliente y empleado
			$selectPedido = $db->prepare('SELECT p.idPedido, p.estado, p.horaCreacion,
												c.idCliente, c.nombre AS nombreCliente, c.primerApe AS apellidoCliente,
												e.idEmpleado, e.nombre AS nombreEmpleado, e.primerApe AS apellidoEmpleado
										FROM pedido p
										INNER JOIN cliente c ON p.idCliente = c.idCliente
										INNER JOIN empleados e ON p.idEmpleado = e.idEmpleado
										WHERE p.idPedido = :idPedido;');
			$selectPedido->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
			$selectPedido->execute();
			$datosHeader = $selectPedido->fetch(PDO::FETCH_ASSOC);

			if (!$datosHeader) {
				return null;
			}

			$detallesPedido = new DetallesPedido();
			$detallesPedido->setIdPedido($datosHeader['idPedido']);
			$detallesPedido->setEstado($datosHeader['estado']);
			$detallesPedido->setHoraCreacion($datosHeader['horaCreacion']);
			$detallesPedido->setNombreCliente($datosHeader['nombreCliente']);
			$detallesPedido->setApellidoCliente($datosHeader['apellidoCliente']);
			$detallesPedido->setIdCliente($datosHeader['idCliente']);
			$detallesPedido->setNombreEmpleado($datosHeader['nombreEmpleado']);
			$detallesPedido->setApellidoEmpleado($datosHeader['apellidoEmpleado']);
			$detallesPedido->setIdEmpleado($datosHeader['idEmpleado']);

			$articulos = $this->obtenerProductosDePedido($idPedido);
			$detallesPedido->setArticulos($articulos);

			return $detallesPedido;
		}

		public function actualizarEstadoPedido($idPedido, $estado) {
			$db = Db::conectar();
			$update = $db->prepare('UPDATE pedido SET estado = :estado WHERE idPedido = :idPedido');
			$update->bindParam(':estado', $estado);
			$update->bindParam(':idPedido', $idPedido);
			return $update->execute();
    	}
	
		public function insertarPedido($idCliente, $idEmpleado) {
			$db = Db::conectar();
			$insert = $db->prepare('INSERT INTO pedido (idCliente, idEmpleado) VALUES (:idCliente, :idEmpleado)');
			$insert->bindValue('idCliente', $idCliente);
			$insert->bindValue('idEmpleado', $idEmpleado);
			$insert->execute();

			// Retornamos el id generado automáticamente
			return $db->lastInsertId();
		}

		public function insertarDetalleIndividual($idPedido, $idProducto, $cantidad) {
			$db = Db::conectar();
			$insert = $db->prepare('INSERT INTO detalles_pedido (idPedido, idProducto, cantidad) VALUES (:idPedido, :idProducto, :cantidad)');
			$insert->bindValue('idPedido', $idPedido);
			$insert->bindValue('idProducto', $idProducto);
			$insert->bindValue('cantidad', $cantidad);
			$insert->execute();
		}

		
		public function obtenerPedidoPorId($idPedido) {
        $db = Db::conectar(); // Asume que Db::conectar() está disponible y devuelve la conexión PDO
        $select = $db->prepare('SELECT 
                                    p.idPedido, 
									p.fechaHora AS horaCreacion,
									p.estado, 
									p.idEmpleado, 
									p.idCliente,
                                    e.nombre AS nombreEmpleado, 
									e.primerApe AS apellidoEmpleado,
                                    c.nombre AS nombreCliente, 
									c.primerApe AS apellidoCliente
                                FROM pedido p
                                INNER JOIN historial_pedidos hp ON p.idPedido = hp.idPedidoFinal -- <-- CAMBIO AQUÍ: hp.idPedidoFinal
                                INNER JOIN cliente c ON p.idCliente = c.idCliente
                                ORDER BY p.idPedido DESC;');

        foreach ($select->fetchAll(PDO::FETCH_ASSOC) as $pedidoData) {
            $myPedido = new Pedido();
            $myPedido->setIdPedido($pedidoData['idPedido']);
            $myPedido->setFechaHora($pedidoData['fechaHora']);
            $myPedido->setMontoTotal($pedidoData['montoFinal']);
            $myPedido->setTipoPago($pedidoData['tipoPago']);
            $myPedido->setNombreCliente($pedidoData['nombreCliente']);
            $myPedido->setApellidoCliente($pedidoData['apellidoCliente']);
            $listaPedidos[] = $myPedido;
        }
        return $listaPedidos;
    }

    public function buscarPedidosParaHistorial($idPedido = null, $fechaDesde = null, $fechaHasta = null, $tipoPago = null, $nombreCliente = null) {
        $db = Db::conectar();
        $listaPedidos = [];

        $query = 'SELECT p.idPedido, p.fechaHora,
                   hp.montoFinal, hp.tipoPago,
                   c.nombre AS nombreCliente, c.primerApe AS apellidoCliente
            FROM pedido p
            INNER JOIN historial_pedidos hp ON p.idPedido = hp.idPedidoFinal -- <-- CAMBIO AQUÍ: hp.idPedidoFinal
            INNER JOIN cliente c ON p.idCliente = c.idCliente
            WHERE 1=1
        ';

        if ($idPedido !== null && $idPedido !== '') {
            $query .= ' AND p.idPedido = :idPedido';
        }
        if ($fechaDesde !== null && $fechaDesde !== '') {
            $query .= ' AND p.fechaHora >= :fechaDesde';
        }
        if ($fechaHasta !== null && $fechaHasta !== '') {
            $query .= ' AND p.fechaHora <= :fechaHasta';
        }
        if ($tipoPago !== null && $tipoPago !== '' && $tipoPago !== 'todos') {
            $query .= ' AND hp.tipoPago = :tipoPago';
        }
        if ($nombreCliente !== null && $nombreCliente !== '') {
            $query .= ' AND (c.nombre LIKE :nombreCliente OR c.primerApe LIKE :nombreCliente)';
        }

        $query .= ' ORDER BY p.idPedido DESC';

        $select = $db->prepare($query);

        if ($idPedido !== null && $idPedido !== '') {
            $select->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        }
        if ($fechaDesde !== null && $fechaDesde !== '') {
            $select->bindValue(':fechaDesde', $fechaDesde . ' 00:00:00');
        }
        if ($fechaHasta !== null && $fechaHasta !== '') {
            $select->bindValue(':fechaHasta', $fechaHasta . ' 23:59:59');
        }
        if ($tipoPago !== null && $tipoPago !== '' && $tipoPago !== 'todos') {
            $select->bindValue(':tipoPago', $tipoPago, PDO::PARAM_STR);
        }
        if ($nombreCliente !== null && $nombreCliente !== '') {
            $select->bindValue(':nombreCliente', '%' . $nombreCliente . '%', PDO::PARAM_STR);
        }

        $select->execute();

        foreach ($select->fetchAll(PDO::FETCH_ASSOC) as $pedidoData) {
            $myPedido = new Pedido();
            $myPedido->setIdPedido($pedidoData['idPedido']);
            $myPedido->setFechaHora($pedidoData['fechaHora']);
            $myPedido->setMontoTotal($pedidoData['montoFinal']);
            $myPedido->setTipoPago($pedidoData['tipoPago']);
            $myPedido->setNombreCliente($pedidoData['nombreCliente']);
            $myPedido->setApellidoCliente($pedidoData['apellidoCliente']);
            $listaPedidos[] = $myPedido;
        }
        return $listaPedidos;
    }
}
?>