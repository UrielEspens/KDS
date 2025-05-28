<?php
require_once('conexion.php');
require_once('clasePago.php');

class CrudPago {
    public function __construct(){}

    public function insertarPago(Pago $pago) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO pago (idPedido, metodoPago, montoPagado, fechaPago) VALUES (:idPedido, :metodoPago, :montoPagado, NOW())');
        $insert->bindValue(':idPedido', $pago->getIdPedido());
        $insert->bindValue(':metodoPago', $pago->getMetodoPago());
        $insert->bindValue(':montoPagado', $pago->getMontoPagado()); // Usar montoPagado para el total final
        return $insert->execute();
    }

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
            $pago->setFechaHoraPago($registro['fechaHoraPago']);
            return $pago;
        }
        return null;
    }
}
?>