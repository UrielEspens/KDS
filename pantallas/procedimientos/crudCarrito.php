<?php
// procedimientos/crudCarrito.php

require_once('conexion.php');
require_once('claseCarrito.php'); // Asegúrate de que esta clase define ItemsCarrito
require_once('claseInfoProducto.php'); // Necesario si crudCarrito.php usa InfoProducto para obtener datos completos

class CrudCarrito {
    public function __construct(){}

    public function obtenerIdCarritoActivoPorEmpleado($idEmpleado) {
        $db = Db::conectar();
        $estado = 'activo';
        // Agregamos un ORDER BY idCarrito DESC para asegurarnos de tomar el más reciente si hubiera varios por alguna razón
        $select = $db->prepare('SELECT idCarrito
                                FROM carrito_sesion
                                WHERE idEmpleado = :idEmpleado
                                AND estado = :estado
                                ORDER BY idCarrito DESC LIMIT 1;');
        $select->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $select->bindParam(':estado', $estado, PDO::PARAM_STR);
        $select->execute();
        $result = $select->fetch(PDO::FETCH_ASSOC); // Usar FETCH_ASSOC para un array asociativo
        return $result ? (int)$result['idCarrito'] : null;
    }

    public function crearCarritoParaEmpleado($idEmpleado) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO carrito_sesion (idEmpleado, estado)
                                VALUES (:idEmpleado, "activo");'); // Asegurar que se inserte como 'activo'
        $insert->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        if ($insert->execute()) {
            return (int)$db->lastInsertId();
        }
        return false;
    }


    public function agregarOActualizarItemEnCarritoDB($idCarrito, $idProducto, $cantidad, $precioUnitario) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO carrito_items (idCarrito, idProducto, cantidad, precioUnitario)
                                VALUES (:idCarrito, :idProducto, :cantidad, :precioUnitario)
                                ON DUPLICATE KEY UPDATE
                                    cantidad = cantidad + VALUES(cantidad),
                                    precioUnitario = VALUES(precioUnitario);'); // Actualiza precio unitario si cambia
        $insert->bindParam(':idCarrito', $idCarrito, PDO::PARAM_INT);
        $insert->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        $insert->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $insert->bindParam(':precioUnitario', $precioUnitario, PDO::PARAM_STR); // Precio puede ser float
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
        $update->bindParam(':nuevaCantidad', $nuevaCantidad, PDO::PARAM_INT);
        $update->bindParam(':idCarrito', $idCarrito, PDO::PARAM_INT);
        $update->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        return $update->execute();
    }

    public function eliminarItemDeCarritoDB($idCarrito, $idProducto) {
        $db = Db::conectar();
        $delete = $db->prepare('DELETE FROM carrito_items
                                WHERE idCarrito = :idCarrito
                                AND idProducto = :idProducto;');
        $delete->bindParam(':idCarrito', $idCarrito, PDO::PARAM_INT);
        $delete->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        return $delete->execute();
    }

    public function vaciarCarritoDB($idCarrito) {
        $db = Db::conectar();
        $delete = $db->prepare('DELETE FROM carrito_items
                                WHERE idCarrito = :idCarrito;');
        $delete->bindParam(':idCarrito', $idCarrito, PDO::PARAM_INT);
        return $delete->execute();
    }

    public function obtenerItemsDeCarrito($idEmpleado) {
        $db = Db::conectar();
        $listaItems = [];
        $idCarrito = $this->obtenerIdCarritoActivoPorEmpleado($idEmpleado); 

        if (!$idCarrito) {
            return $listaItems; // No hay carrito activo, retornar array vacío
        }

        $sql_items = 'SELECT p.idProducto AS id, p.nombre, ci.precioUnitario AS precio, ci.cantidad
                      FROM carrito_items ci
                      INNER JOIN producto p ON ci.idProducto = p.idProducto
                      WHERE ci.idCarrito = :idCarrito';

        $stmt_items = $db->prepare($sql_items);
        $stmt_items->bindParam(':idCarrito', $idCarrito, PDO::PARAM_INT);
        $stmt_items->execute();

        foreach ($stmt_items->fetchAll() as $item) { 
            $myItem = new ItemsCarrito();
            $myItem->setIdItem((int)$item['id']);
            $myItem->setNombreItem($item['nombre']);
            $myItem->setPrecioItem((float)$item['precio']);
            $myItem->setCantidadItem((int)$item['cantidad']);
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
        $stmt = $db->prepare('SELECT ci.idProducto AS id, p.nombre,
                                     ci.precioUnitario AS precio,
                                     ci.cantidad
                               FROM carrito_items ci
                               INNER JOIN producto p ON ci.idProducto = p.idProducto
                               WHERE ci.idCarrito = :idCarrito;');
        $stmt->bindParam(':idCarrito', $idCarrito);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>