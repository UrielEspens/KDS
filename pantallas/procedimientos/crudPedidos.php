<?php
require_once('conexion.php');
require_once('clasePedido.php');
require_once('claseDetallesPedido.php');

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


    public function obtenerTodosLosPedidosParaHistorial() {
        $db = Db::conectar();
        $listaPedidos = [];
        $select = $db->query('
            SELECT p.idPedido, p.fechaHora,
                   hp.montoFinal, hp.tipoPago,
                   c.nombre AS nombreCliente, c.primerApe AS apellidoCliente
            FROM pedido p
            INNER JOIN historial_pedidos hp ON p.idPedido = hp.idPedidoFinal -- <-- CAMBIO AQUÍ: hp.idPedidoFinal
            INNER JOIN cliente c ON p.idCliente = c.idCliente
            ORDER BY p.idPedido DESC
        ');

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

        $query = '
            SELECT p.idPedido, p.fechaHora,
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