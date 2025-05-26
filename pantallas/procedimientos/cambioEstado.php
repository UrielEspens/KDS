<?php
session_start();
require_once('conexion.php');
require_once('clasePedido.php');

$idPedido = $_POST['idPedido'];
$estado = $_POST['estado'];

$db = Db::conectar();
if ($estado == 'pendiente') {
    $nuevoEstado = 'preparacion';
    $update = $db->prepare('UPDATE pedido SET estado = :nuevoEstado WHERE idPedido = :idPedido');
    $update->bindParam(':nuevoEstado', $nuevoEstado);
    $update->bindParam(':idPedido', $idPedido);
    $update->execute();
} elseif ($estado == 'preparacion') {
    $nuevoEstado = 'listo';
    $update = $db->prepare('UPDATE pedido SET estado = :nuevoEstado WHERE idPedido = :idPedido');
    $update->bindParam(':nuevoEstado', $nuevoEstado);
    $update->bindParam(':idPedido', $idPedido); 
    $update->execute();
} 

header('Location: ../inicio.php');
exit();
?>