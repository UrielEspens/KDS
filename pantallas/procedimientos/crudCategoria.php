<?php
require_once(__DIR__ . '/conexion.php');
require_once(__DIR__ . '/claseCategoria.php');

class CrudCategoria {
    public function __construct(){}

    // Función para insertar una nueva categoría
    public function insertar($categoria){
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO categorias(nombre) VALUES(:nombre)');
        $insert->bindValue('nombre', $categoria->getNombre());
        $insert->execute();
    }

    // Función para obtener una categoría por su ID
    public function obtenerCategoria($idCategoria){
        $db = Db::conectar();
        $select = $db->prepare('SELECT idCategoria, nombre FROM categorias WHERE idCategoria=:idCategoria');
        $select->bindValue('idCategoria', $idCategoria);
        $select->execute();
        $categoriaData = $select->fetch();
        
        if ($categoriaData) {
            $myCategoria = new Categoria();
            $myCategoria->setIdCategoria($categoriaData['idCategoria']);
            $myCategoria->setNombre($categoriaData['nombre']);
            return $myCategoria;
        }
        return null; // Retorna null si la categoría no se encuentra
    }

    // Función para obtener la lista de todas las categorías
    public function obtenerListaCategorias(){
        $db = Db::conectar();
        $listaCategorias = [];
        $select = $db->query('SELECT idCategoria, nombre FROM categorias ORDER BY nombre ASC');

        foreach($select->fetchAll() as $categoriaData){
            $myCategoria = new Categoria();
            $myCategoria->setIdCategoria($categoriaData['idCategoria']);
            $myCategoria->setNombre($categoriaData['nombre']);
            $listaCategorias[] = $myCategoria;
        }
        return $listaCategorias;
    }

    // Función para actualizar una categoría existente
    public function actualizar($categoria){
        $db = Db::conectar();
        $update = $db->prepare('UPDATE categorias SET nombre=:nombre WHERE idCategoria=:idCategoria');
        $update->bindValue('nombre', $categoria->getNombre());
        $update->bindValue('idCategoria', $categoria->getIdCategoria());
        $update->execute();
    }

    // Función para eliminar una categoría por su ID
    public function eliminar($idCategoria){
        $db = Db::conectar();
        $delete = $db->prepare('DELETE FROM categorias WHERE idCategoria=:idCategoria');
        $delete->bindValue('idCategoria', $idCategoria);
        $delete->execute();
    }
}
?>