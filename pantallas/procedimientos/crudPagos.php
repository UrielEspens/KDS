<?php
require_once('conexion.php');
require_once('clasePago.php');

class CrudPago {
    public function __construct(){}

    // Inserta un nuevo registro de pago en la base de datos
    public function insertarPago(Pago $pago) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO pago (idPedido, metodoPago, montoPagado, fechaPago, idEmpleado) VALUES (:idPedido, :metodoPago, :montoPagado, NOW(), :idEmpleado)');
        $insert->bindValue(':idPedido', $pago->getIdPedido());
        $insert->bindValue(':metodoPago', $pago->getMetodoPago());
        $insert->bindValue(':montoPagado', $pago->getMontoPagado());
        $insert->bindValue(':idEmpleado', $pago->getIdEmpleado());
        return $insert->execute();
    }

    // Obtiene un registro de pago de la base de datos utilizando su ID
    public function obtenerPagoPorId($idPago) {
        $db = Db::conectar();
        $select = $db->prepare('SELECT * FROM pago WHERE idPago = :idPago');
        $select->bindValue(':idPago', $idPago);
        $select->execute();
        $registro = $select->fetch();

        if ($registro) {
            $pago = new Pago();
            $pago->setIdPago($registro['idPago']);
            $pago->setIdPedido($registro['idPedido']);
            $pago->setMetodoPago($registro['metodoPago']);
            $pago->setMontoPagado($registro['montoPagado']);
            $pago->setFechaHoraPago($registro['fechaPago']);
            $pago->setIdEmpleado($registro['idEmpleado']);
            return $pago;
        }
        return null;
    }
}
?>