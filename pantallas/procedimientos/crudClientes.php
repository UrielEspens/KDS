<?php
require_once('conexion.php');
require_once('claseCliente.php');

class crudClientes{
    public function __construct(){}
    // Funcion para insertar clientes
    public function insertar($cliente){
        $db=Db::conectar();
        $insert=$db->prepare('INSERT INTO cliente(nombre,primerApe,segundoApe,telefono,calle,numeroCasa,referenciaCasa) values(:nombre,:primerApe,:segundoApe,:telefono,:calle,:numeroCasa,:referenciaCasa)');
        $insert->bindValue(':nombre',$cliente->getNombre());
        $insert->bindValue(':primerApe',$cliente->getPrimerApe());
        $insert->bindValue(':segundoApe',$cliente->getSegundoApe());
        $insert->bindValue(':telefono',$cliente->getTelefono());
        $insert->bindValue(':calle',$cliente->getCalle());
        $insert->bindValue(':numeroCasa',$cliente->getNumeroCasa());
        $insert->bindValue(':referenciaCasa',$cliente->getReferenciaCasa());

        $insert->execute();
    }
    //Funcion para actualizar clientes
    public function actualizar($cliente){
        $db=Db::conectar();
        $update=$db->prepare('UPDATE cliente SET nombre=:nombre, primerApe=:primerApe, segundoApe=:segundoApe, telefono=:telefono, calle=:calle, numeroCasa=:numeroCasa, referencia=:referencia WHERE idCliente=:idCliente');
        $update->bindValue(':idCliente',$cliente->getIdCliente());
        $update->bindValue(':nombre',$cliente->getNombre());
        $update->bindValue(':primerApe',$cliente->getPrimerApe());
        $update->bindValue(':segundoApe',$cliente->getSegundoApe());
        $update->bindValue(':telefono',$cliente->getTelefono());
        $update->bindValue(':calle',$cliente->getCalle());
        $update->bindValue(':numeroCasa',$cliente->getNumeroCasa());
        $update->bindValue(':referenciaCasa',$cliente->getReferenciaCasa());

        $update->execute();
    }
    //Funcion para eliminar clientes
    public function eliminar($idCliente){
        $db=Db::conectar();
        $delete=$db->prepare('DELETE FROM cliente WHERE idCliente=:idCliente');
        $delete->bindValue(':idCliente',$idCliente);
        $delete->execute();
    }
    //Funcion para obtener clientes desde la gestion de clientes a través de su id
    public function obtenerCliente($idCliente){
        $db=Db::conectar();
        $select=$db->prepare('SELECT * FROM cliente WHERE idCliente=:idCliente');
        $select->bindValue(':idCliente',$idCliente);
        $select->execute();

        $cliente= $select->fetch();
        $myCliente = new Cliente();
        $myCliente -> setIdCliente($cliente['idCliente']);
        $myCliente -> setNombre($cliente['nombre']);
        $myCliente -> setPrimerApe($cliente['primerApe']);
        $myCliente -> setSegundoApe($cliente['segundoApe']);
        $myCliente -> setTelefono($cliente['telefono']);
        $myCliente -> setCalle($cliente['calle']);
        $myCliente -> setNumeroCasa($cliente['numeroCasa']);
        $myCliente -> setReferenciaCasa($cliente['referenciaCasa']);

        return $myCliente;
    }
    //Funcion para obtener todos los clientes de la bd
    public function obtenerClientes(){
        $db=Db::conectar();
        $select=$db->query('SELECT * FROM cliente');
        $listaClientes=[];
        foreach($select->fetchAll() as $cliente){
            $myCliente = new Cliente();
            $myCliente -> setIdCliente($cliente['idCliente']);
            $myCliente -> setNombre($cliente['nombre']);
            $myCliente -> setPrimerApe($cliente['primerApe']);
            $myCliente -> setSegundoApe($cliente['segundoApe']);
            $myCliente -> setTelefono($cliente['telefono']);
            $myCliente -> setCalle($cliente['calle']);
            $myCliente -> setNumeroCasa($cliente['numeroCasa']);
            $myCliente -> setReferenciaCasa($cliente['referenciaCasa']);

            $listaClientes[]=$myCliente;
        }
        return $listaClientes;
    }
    //Funcion para obtener la lista de clientes a traves de una busqueda en gestion de clientes
    public function buscarClientesBusqueda($busqueda){
        $db=Db::conectar();
        $select=$db->prepare('SELECT * FROM cliente WHERE nombre LIKE :busqueda OR primerApe LIKE :busqueda OR segundoApe LIKE :busqueda');
        $select->bindValue(':busqueda', '%'.$busqueda.'%');
        $select->execute();

        $listaClientes=[];
        foreach($select->fetchAll() as $cliente){
            $myCliente = new Cliente();
            $myCliente -> setIdCliente($cliente['idCliente']);
            $myCliente -> setNombre($cliente['nombre']);
            $myCliente -> setPrimerApe($cliente['primerApe']);
            $myCliente -> setSegundoApe($cliente['segundoApe']);
            $myCliente -> setTelefono($cliente['telefono']);
            $myCliente -> setCalle($cliente['calle']);
            $myCliente -> setNumeroCasa($cliente['numeroCasa']);
            $myCliente -> setReferenciaCasa($cliente['referenciaCasa']);

            $listaClientes[]=$myCliente;
        }
        return $listaClientes;
    }
}
?>
