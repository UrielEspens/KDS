<?php
// procedimientos/crudCarrito.php

require_once('conexion.php'); // Asegúrate de que esta ruta sea correcta

class CrudCarrito {
    // Constructor de la clase (vacío según tu preferencia)
    public function __construct(){}

    /**
     * Obtiene el ID del carrito activo para un empleado.
     * Si no existe, devuelve null.
     * @param int $idEmpleado El ID del empleado.
     * @return int|null El ID del carrito activo, o null si no se encuentra.
     */
    public function obtenerIdCarritoActivoPorEmpleado($idEmpleado) {
        $db = Db::conectar();
        $select = $db->prepare('SELECT idCarrito 
                                FROM carrito_sesion 
                                WHERE idEmpleado = :idEmpleado 
                                AND estado = \'activo\' LIMIT 1;');
        $select->bindParam(':idEmpleado', $idEmpleado);
        $select->execute();
        $result = $select->fetch();
        return $result ? $result['idCarrito'] : null;
    }

    /**
     * Crea un nuevo carrito vacío para un empleado y devuelve su ID.
     * @param int $idEmpleado El ID del empleado para el que se crea el carrito.
     * @return int|false El ID del nuevo carrito insertado, o false si falla.
     */
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

    /**
     * Agrega un producto al carrito en la DB o actualiza su cantidad si ya existe.
     * Es crucial que la tabla `carrito_items` tenga un índice único en `(idCarrito, idProducto)`
     * para que `ON DUPLICATE KEY UPDATE` funcione correctamente.
     * @param int $idCarrito El ID del carrito.
     * @param int $idProducto El ID del producto.
     * @param int $cantidad La cantidad a añadir/actualizar.
     * @param float $precioUnitario El precio unitario del producto en el momento de la adición.
     * @return bool True en éxito, false en fallo.
     */
    public function agregarOActualizarItemEnCarritoDB($idCarrito, $idProducto, $cantidad, $precioUnitario) {
        $db = Db::conectar();
        $sql = "INSERT INTO carrito_items (idCarrito, idProducto, cantidad, precioUnitario)
                VALUES (:idCarrito, :idProducto, :cantidad, :precioUnitario)
                ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad), precioUnitario = VALUES(precioUnitario)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idCarrito', $idCarrito);
        $stmt->bindParam(':idProducto', $idProducto);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':precioUnitario', $precioUnitario); // Usar STR para precisión de decimales
        return $stmt->execute();
    }

    /**
     * Actualiza la cantidad de un producto en el carrito de la DB.
     * Si la cantidad es <= 0, elimina el item.
     * @param int $idCarrito El ID del carrito.
     * @param int $idProducto El ID del producto.
     * @param int $nuevaCantidad La nueva cantidad del producto.
     * @return bool True en éxito, false en fallo.
     */
    public function actualizarCantidadItemCarritoDB($idCarrito, $idProducto, $nuevaCantidad) {
        $db = Db::conectar();
        if ($nuevaCantidad <= 0) {
            return $this->eliminarItemDeCarritoDB($idCarrito, $idProducto);
        }
        $sql = "UPDATE carrito_items SET cantidad = :nuevaCantidad WHERE idCarrito = :idCarrito AND idProducto = :idProducto";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nuevaCantidad', $nuevaCantidad);
        $stmt->bindParam(':idCarrito', $idCarrito);
        $stmt->bindParam(':idProducto', $idProducto);
        return $stmt->execute();
    }

    /**
     * Elimina un producto del carrito en la DB.
     * @param int $idCarrito El ID del carrito.
     * @param int $idProducto El ID del producto a eliminar.
     * @return bool True en éxito, false en fallo.
     */
    public function eliminarItemDeCarritoDB($idCarrito, $idProducto) {
        $db = Db::conectar();
        $sql = "DELETE FROM carrito_items WHERE idCarrito = :idCarrito AND idProducto = :idProducto";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idCarrito', $idCarrito);
        $stmt->bindParam(':idProducto', $idProducto);
        return $stmt->execute();
    }

    /**
     * Vacía todos los productos de un carrito específico en la DB.
     * @param int $idCarrito El ID del carrito a vaciar.
     * @return bool True en éxito, false en fallo.
     */
    public function vaciarCarritoDB($idCarrito) {
        $db = Db::conectar();
        $sql = "DELETE FROM carrito_items WHERE idCarrito = :idCarrito";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idCarrito', $idCarrito);
        return $stmt->execute();
    }

    /**
     * Obtiene todos los items de un carrito específico desde la DB.
     * Devuelve un array de arrays asociativos (listos para el $_SESSION['carrito']).
     * @param int $idCarrito El ID del carrito a obtener los ítems.
     * @return array Un array de ítems del carrito.
     */
    public function obtenerItemsDeCarritoDB($idCarrito) {
        $db = Db::conectar();
        // Unimos con la tabla 'producto' para obtener el nombre
        $sql = "SELECT ci.idProducto AS id, p.nombre, ci.precioUnitario AS precio, ci.cantidad
                FROM carrito_items ci
                JOIN producto p ON ci.idProducto = p.idProducto
                WHERE ci.idCarrito = :idCarrito";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idCarrito', $idCarrito);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Marca un carrito como 'finalizado' en la base de datos.
     * Esto se usa cuando el carrito se convierte en un pedido real y ya no es un carrito 'activo'.
     * @param int $idCarrito El ID del carrito a finalizar.
     * @return bool True en éxito, false en fallo.
     */
    public function finalizarCarrito($idCarrito) {
        $db = Db::conectar();
        $sql = "UPDATE carrito_sesion SET estado = 'finalizado' WHERE idCarrito = :idCarrito";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idCarrito', $idCarrito);
        return $stmt->execute();
    }
}
?>