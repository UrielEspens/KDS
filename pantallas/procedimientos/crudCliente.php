<?php
// incluye la clase Db y ClaseCliente
require_once(__DIR__ . '/conexion.php');    // Asegúrate de que conexion.php esté en el mismo directorio
require_once(__DIR__ . '/ClaseCliente.php'); // Asegúrate de que ClaseCliente.php esté en el mismo directorio

class CrudCliente {
    // constructor de la clase
    public function __construct(){}

    // Funcion para insertar clientes
    public function insertar($cliente){
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO cliente(nombre, primerApe, segundoApe, telefono, calle, numeroCasa, referenciaCasa) VALUES(:nombre, :primerApe, :segundoApe, :telefono, :calle, :numeroCasa, :referenciaCasa)');
        $insert->bindValue('nombre', $cliente->getNombre());
        $insert->bindValue('primerApe', $cliente->getPrimerApe());
        $insert->bindValue('segundoApe', $cliente->getSegundoApe());
        $insert->bindValue('telefono', $cliente->getTelefono());
        $insert->bindValue('calle', $cliente->getCalle());
        $insert->bindValue('numeroCasa', $cliente->getNumeroCasa());
        $insert->bindValue('referenciaCasa', $cliente->getReferenciaCasa());
        $insert->execute();
    }

    // Funcion para actualizar clientes (mantenido por si decides implementarlo)
    public function actualizar($cliente){
        $db = Db::conectar();
        $update = $db->prepare('UPDATE cliente SET nombre=:nombre, primerApe=:primerApe, segundoApe=:segundoApe, telefono=:telefono, calle=:calle, numeroCasa=:numeroCasa, referenciaCasa=:referenciaCasa WHERE idCliente=:idCliente');
        $update->bindValue('nombre', $cliente->getNombre());
        $update->bindValue('primerApe', $cliente->getPrimerApe());
        $update->bindValue('segundoApe', $cliente->getSegundoApe());
        $update->bindValue('telefono', $cliente->getTelefono());
        $update->bindValue('calle', $cliente->getCalle());
        $update->bindValue('numeroCasa', $cliente->getNumeroCasa());
        $update->bindValue('referenciaCasa', $cliente->getReferenciaCasa());
        $update->bindValue('idCliente', $cliente->getIdCliente());
        $update->execute();
    }

    // Funcion para eliminar clientes (mantenido por si decides implementarlo)
    public function eliminar($idCliente){
        $db = Db::conectar();
        $delete = $db->prepare('DELETE FROM cliente WHERE idCliente=:idCliente');
        $delete->bindValue('idCliente', $idCliente);
        $delete->execute();
    }

    // Funcion para obtener un cliente (mantenido por si decides implementarlo)
    public function obtenerCliente($idCliente){
        $db = Db::conectar();
        $select = $db->prepare('SELECT * FROM cliente WHERE idCliente=:idCliente');
        $select->bindValue('idCliente', $idCliente);
        $select->execute();

        $clienteData = $select->fetch();
        $myCliente = new Cliente();
        $myCliente->setIdCliente($clienteData['idCliente']);
        $myCliente->setNombre($clienteData['nombre']);
        $myCliente->setPrimerApe($clienteData['primerApe']);
        $myCliente->setSegundoApe($clienteData['segundoApe']);
        $myCliente->setTelefono($clienteData['telefono']);
        $myCliente->setCalle($clienteData['calle']);
        $myCliente->setNumeroCasa($clienteData['numeroCasa']);
        $myCliente->setReferenciaCasa($clienteData['referenciaCasa']);
        return $myCliente;
    }

    // Funcion para obtener la lista de clientes (mantenido por si decides implementarlo)
    public function obtenerListaClientes(){
        $db = Db::conectar();
        $listaClientes = [];
        $select = $db->query('SELECT * FROM cliente');

        foreach($select->fetchAll() as $clienteData){
            $myCliente = new Cliente();
            $myCliente->setIdCliente($clienteData['idCliente']);
            $myCliente->setNombre($clienteData['nombre']);
            $myCliente->setPrimerApe($clienteData['primerApe']);
            $myCliente->setSegundoApe($clienteData['segundoApe']);
            $myCliente->setTelefono($clienteData['telefono']);
            $myCliente->setCalle($clienteData['calle']);
            $myCliente->setNumeroCasa($clienteData['numeroCasa']);
            $myCliente->setReferenciaCasa($clienteData['referenciaCasa']);
            $listaClientes[] = $myCliente;
        }
        return $listaClientes;
    }
}
?>