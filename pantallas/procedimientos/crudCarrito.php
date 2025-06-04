<?php

require_once('conexion.php'); 
require_once('claseCarrito.php');

// Definir la clase CrudCarrito
class CrudCarrito {
    // Constructor
    public function __construct(){}

    // Obtiene el ID del carrito activo para un empleado específico
    // Un carrito se considera 'activo' si tiene ese estado en la base de datos
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

    // Crea un nuevo carrito de sesión para un empleado
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

    // Agrega un nuevo artículo al carrito o actualiza la cantidad si ya existe
    // Si el artículo ya está en el carrito, su cantidad se incrementará con la nueva cantidad
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

    // Actualiza la cantidad de un artículo específico en el carrito
    // Si la nueva cantidad es 0 o menos, el artículo se eliminará del carrito
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

    // Elimina un artículo específico del carrito
    public function eliminarItemDeCarritoDB($idCarrito, $idProducto) {
        $db = Db::conectar();
        $delete = $db->prepare('DELETE FROM carrito_items 
                                WHERE idCarrito = :idCarrito 
                                AND idProducto = :idProducto;');
        $delete->bindParam(':idCarrito', $idCarrito);
        $delete->bindParam(':idProducto', $idProducto);
        return $delete->execute();
    }

    // Vacía todos los artículos de un carrito específico
    public function vaciarCarritoDB($idCarrito) {
        $db = Db::conectar();
        $delete = $db->prepare('  DELETE FROM carrito_items 
                                    WHERE idCarrito = :idCarrito;');
        $delete->bindParam(':idCarrito', $idCarrito);
        return $delete->execute();
    }

    // Obtiene la lista de artículos en el carrito activo de un empleado
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

        // Si no se encuentra un carrito activo, retorna un array vacío
        if (!$resultado_id_carrito) {
            return $listaItems;
        }
        // Obtiene el ID del carrito
        $idCarrito = $resultado_id_carrito['idCarrito'];

        $sql_items = 'SELECT p.idProducto AS id, p.nombre, ci.precioUnitario AS precio, ci.cantidad
                      FROM carrito_items ci
                      INNER JOIN producto p ON ci.idProducto = p.idProducto
                      WHERE ci.idCarrito = :idCarrito';

        $stmt_items = $db->prepare($sql_items);
        $stmt_items->bindParam(':idCarrito', $idCarrito); 
        $stmt_items->execute();

        // Itera sobre los resultados y crea objetos ItemsCarrito
        foreach ($stmt_items->fetchAll() as $item) {
            $myItem = new ItemsCarrito();
            $myItem->setIdItem($item['id']);
            $myItem->setNombreItem($item['nombre']);
            $myItem->setPrecioItem($item['precio']);
            $myItem->setCantidadItem($item['cantidad']);
            $listaItems[] = $myItem;
            
        }
        // Retorna la lista de artículos del carrito
        return $listaItems;
    }

    // Finaliza un carrito de sesión, cambiando su estado a 'finalizado'
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

     // Obtiene los artículos de un carrito específico de la base de datos
     // Esta función parece ser similar a obtenerItemsDeCarrito, pero recibe idCarrito directamente
    public function obtenerItemsDeCarritoDB($idCarrito) {
        $db = Db::conectar();
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