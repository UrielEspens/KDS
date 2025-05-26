<?php

require_once('conexion.php');
require_once('claseCliente.php'); 

class CrudClientes {

    public function __construct(){}

    public function obtenerTodosLosClientes() {
        $db = Db::conectar();
        $listaClientes = [];
        $select = $db->query('SELECT idCliente, nombre, primerApe, segundoApe FROM cliente ORDER BY nombre ASC;');
        $select->execute();

        foreach ($select->fetchAll() as $clienteData) {
            $cliente = new Cliente();
            $cliente->setIdCliente($clienteData['idCliente']);
            $cliente->setNombre($clienteData['nombre']);
            $cliente->setPrimerApe($clienteData['primerApe']);
            $cliente->setSegundoApe($clienteData['segundoApe']);
            $listaClientes[] = $cliente;
        }
        return $listaClientes;
    }

    public function obtenerClientePorId($idCliente) {
        $consulta = "SELECT * FROM cliente WHERE idCliente = :idCliente";
        $db = Db::conectar();
        $select = $db->prepare($consulta);
        $select->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);
        $select->execute();

   
        $clienteData = $select->fetch(PDO::FETCH_ASSOC);


        if (!$clienteData) {
            return null;
        }


        $cliente = new Cliente();


        $cliente->setIdCliente($clienteData['idCliente']);
        $cliente->setNombre($clienteData['nombre']);
        $cliente->setPrimerApe($clienteData['primerApe']);
        $cliente->setSegundoApe($clienteData['segundoApe']);
        $cliente->setTelefono($clienteData['telefono']);
        $cliente->setCalle($clienteData['calle']);
        $cliente->setNumeroCasa($clienteData['numeroCasa']);
        $cliente->setReferenciaCasa($clienteData['referenciaCasa']);

        return $cliente;
    }
    

}
?>