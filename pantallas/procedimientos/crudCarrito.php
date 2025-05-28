<?php
// procedimientos/crudCarrito.php

require_once('conexion.php'); 
require_once('claseCarrito.php');

class CrudCarrito {
    public function __construct(){}

    public function obtenerIdCarritoActivoPorEmpleado($idEmpleado) {
        $db = Db::conectar();
        $estado = 'activo'; 
        $select = $db->prepare('SELECT idCarrito
                                FROM carrito_sesion
                                WHERE idEmpleado = :idEmpleado
                                AND estado = :estado; LIMIT 1;');
        $select->bindParam(':idEmpleado', $idEmpleado);
        $select->bindParam(':estado', $estado); 
        $select->execute();
        $result = $select->fetch();
        return $result ? $result['idCarrito'] : null;
    }

    public function crearCarritoParaEmpleado($idEmpleado) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO carrito_sesion (idEmpleado) 
                                VALUES (:idEmpleado);');
        $insert->bindParam(':idEmpleado', $idEmpleado);
        if ($insert->execute()) {
            return $db->lastInsertId();
        }
        return false;
    }

    public function agregarOActualizarItemEnCarritoDB($idCarrito, $idProducto, $cantidad, $precioUnitario) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO carrito_items (idCarrito, idProducto, cantidad, precioUnitario)
                                VALUES (:idCarrito, :idProducto, :cantidad, :precioUnitario)
                                ON DUPLICATE 
                                KEY UPDATE cantidad = cantidad + VALUES(cantidad), precioUnitario = VALUES(precioUnitario);');
        $insert->bindParam(':idCarrito', $idCarrito);
        $insert->bindParam(':idProducto', $idProducto);
        $insert->bindParam(':cantidad', $cantidad);
        $insert->bindParam(':precioUnitario', $precioUnitario);
        return $insert->execute();
    }

    public function actualizarCantidadItemCarritoDB($idCarrito, $idProducto, $nuevaCantidad) {
        $db = Db::conectar();
        if ($nuevaCantidad <= 0) {
            return $this->eliminarItemDeCarritoDB($idCarrito, $idProducto);
        }
        $update = $db->prepare('UPDATE carrito_items 
                                SET cantidad = :nuevaCantidad 
                                WHERE idCarrito = :idCarrito 
                                AND idProducto = :idProducto;');
        $update->bindParam(':nuevaCantidad', $nuevaCantidad);
        $update->bindParam(':idCarrito', $idCarrito);
        $update->bindParam(':idProducto', $idProducto);
        return $update->execute();
    }

    public function eliminarItemDeCarritoDB($idCarrito, $idProducto) {
        $db = Db::conectar();
        $delete = $db->prepare('DELETE FROM carrito_items 
                                WHERE idCarrito = :idCarrito 
                                AND idProducto = :idProducto;');
        $delete->bindParam(':idCarrito', $idCarrito);
        $delete->bindParam(':idProducto', $idProducto);
        return $delete->execute();
    }

    public function vaciarCarritoDB($idCarrito) {
        $db = Db::conectar();
        $delete = $db->prepare('  DELETE FROM carrito_items 
                                    WHERE idCarrito = :idCarrito;');
        $delete->bindParam(':idCarrito', $idCarrito);
        return $delete->execute();
    }

    public function obtenerItemsDeCarrito($idEmpleado) {
        $db = Db::conectar();
        $listaItems = []; 
        $estado = 'activo';
        $stmt_id_carrito = $db->prepare('   SELECT idCarrito 
                                            FROM carrito_sesion 
                                            WHERE idEmpleado = :idEmpleado 
                                            AND estado = :estado LIMIT 1');
        $stmt_id_carrito->bindParam(':idEmpleado', $idEmpleado);
        $stmt_id_carrito->bindParam(':estado', $estado);
        $stmt_id_carrito->execute();
        $resultado_id_carrito = $stmt_id_carrito->fetch();

        if (!$resultado_id_carrito) {
            return $listaItems;
        }

        $idCarrito = $resultado_id_carrito['idCarrito'];

        $sql_items = 'SELECT p.idProducto AS id, p.nombre, ci.precioUnitario AS precio, ci.cantidad
                      FROM carrito_items ci
                      INNER JOIN producto p ON ci.idProducto = p.idProducto
                      WHERE ci.idCarrito = :idCarrito';

        $stmt_items = $db->prepare($sql_items);
        $stmt_items->bindParam(':idCarrito', $idCarrito); 
        $stmt_items->execute();

        foreach ($stmt_items->fetchAll() as $item) {
            $myItem = new ItemsCarrito();
            $myItem->setIdItem($item['id']);
            $myItem->setNombreItem($item['nombre']);
            $myItem->setPrecioItem($item['precio']);
            $myItem->setCantidadItem($item['cantidad']);
            $listaItems[] = $myItem;
            
        }

        return $listaItems;
    }

    public function finalizarCarrito($idCarrito) {
        $db = Db::conectar();
        $estado = 'finalizado';
        $update = $db->prepare('UPDATE carrito_sesion 
                                SET estado = :estado 
                                WHERE idCarrito = :idCarrito');
        $update->bindParam(':estado', $estado);
        $update->bindParam(':idCarrito', $idCarrito);
        return $update->execute();
    }

    public function obtenerItemsDeCarritoDB($idCarrito) {
        $db = Db::conectar();
        // Unimos con la tabla 'producto' para obtener el nombre
        $stmt = $db->prepare('  SELECT  ci.idProducto AS id, p.nombre, 
                                        ci.precioUnitario AS precio, 
                                        ci.cantidad
                                FROM carrito_items ci
                                INNER JOIN carrito_sesion cs ON ci.idCarrito = cs.idCarrito
                                INNER JOIN producto p ON ci.idProducto = p.idProducto
                                WHERE cs.idEmpleado = :idEmpleado AND ci.idCarrito = :idCarrito;');
        $stmt->bindParam(':idCarrito', $idCarrito);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>