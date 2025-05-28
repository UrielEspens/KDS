<?php
require_once('conexion.php');
require_once('clasePago.php');

class CrudPagos {
    public function __construct(){}

    public function registrarPago(Pago $pago) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO pagos (idPedido, montoPagado, metodoPago, idEmpleadoCajero)
                                VALUES (:idPedido, :montoPagado, :metodoPago, :idEmpleadoCajero);');

        $idPedido = $pago->getIdPedido();
        $montoPagado = $pago->getMontoPagado();
        $metodoPago = $pago->getMetodoPago();
        $idEmpleadoCajero = $pago->getIdEmpleadoCajero();

        $insert->bindParam(':idPedido', $idPedido, PDO::PARAM_INT);
        $insert->bindParam(':montoPagado', $montoPagado, PDO::PARAM_STR);
        $insert->bindParam(':metodoPago', $metodoPago, PDO::PARAM_STR);
        $insert->bindParam(':idEmpleadoCajero', $idEmpleadoCajero, PDO::PARAM_INT);

        return $insert->execute();
    }
}
?>