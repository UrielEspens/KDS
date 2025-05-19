<?php
// incluye la clase Db
require_once('conexion.php');

	class CrudArticulo{
		// constructor de la clase
		public function __construct(){}

        // Search
		public function obtenerArticulo($cveArticulo){
			$db=Db::conectar();
			$select=$db->prepare('SELECT * FROM articulos WHERE cveArticulo=:cveArticulo');
			$select->bindValue('cveArticulo',$cveArticulo);
			$select->execute();

			$articulo=$select->fetch();
			$myArticulo= new Articulo();
			$myArticulo->setcveArticulo($articulo['cveArticulo']);
			$myArticulo->setDescripcion($articulo['descripcion']);
			$myArticulo->setDescuento($articulo['descuento']);
			$myArticulo->setIva($articulo['iva']);
			$myArticulo->setPrecio($articulo['precio']);
			return $myArticulo;
		}
        
		// Create
		public function insertar($articulo){
			$db=Db::conectar();
			$insert=$db->prepare('INSERT INTO articulos values(:cveArticulo, :descripcion, :descuento, :iva, :precio)');
			$insert->bindValue('cveArticulo',$articulo->getCveArticulo());
			$insert->bindValue('descripcion',$articulo->getDescripcion());
			$insert->bindValue('descuento',$articulo->getDescuento());
			$insert->bindValue('iva',$articulo->getIva());
			$insert->bindValue('precio',$articulo->getPrecio());
			$insert->execute();

			// insertar en la tabla existencias
			$insertExistencia = $db->prepare('INSERT INTO existencias (cveArticulo, existencia) VALUES (:cveArticulo, :existencia)');
            $insertExistencia->bindValue('cveArticulo', $articulo->getCveArticulo());
            $insertExistencia->bindValue('existencia', 0); // Default amount is 0
            $insertExistencia->execute();
		}

		// Read
		public function mostrar(){ 
			$db=Db::conectar();
			$listaArticulos=[];
			$select=$db->query('SELECT * FROM articulos');

			foreach($select->fetchAll() as $articulo){
				$myArticulo= new Articulo();
				$myArticulo->setCveArticulo($articulo['cveArticulo']);
				$myArticulo->setDescripcion($articulo['descripcion']);
				$myArticulo->setDescuento($articulo['descuento']);
				$myArticulo->setIva($articulo['iva']);
				$myArticulo->setPrecio($articulo['precio']);
				$listaArticulos[]=$myArticulo;
			}
			return $listaArticulos;
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
