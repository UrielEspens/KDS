<?php
session_start();
require_once('conexion.php');
require_once('clasePedido.php');

$idPedido = $_POST['idPedido'];
$estado = $_POST['estado'];

// Establecer la conexión a la base de datos
$db = Db::conectar();
// Verifica si el estado actual del pedido es 'pendiente'
if ($estado == 'pendiente') {
    // Si es 'pendiente', el nuevo estado será 'preparacion'
    $nuevoEstado = 'preparacion';
    $update = $db->prepare('UPDATE pedido SET estado = :nuevoEstado WHERE idPedido = :idPedido');
    $update->bindParam(':nuevoEstado', $nuevoEstado);
    $update->bindParam(':idPedido', $idPedido);
    $update->execute();
} elseif ($estado == 'preparacion') {
    // Si es 'preparacion', el nuevo estado será 'listo'
    $nuevoEstado = 'listo';
    $update = $db->prepare('UPDATE pedido SET estado = :nuevoEstado WHERE idPedido = :idPedido');
    $update->bindParam(':nuevoEstado', $nuevoEstado);
    $update->bindParam(':idPedido', $idPedido); 
    $update->execute();
} 

header('Location: ../inicio.php');
exit();
?>