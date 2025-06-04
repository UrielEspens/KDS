<?php
// Define la clase Categoria
class Categoria {
    // Declara las propiedades privadas de la clase
    private $idCategoria;
    private $nombre;

    // Constructor
    function __construct(){
    }

    // Getters y setters
    public function getIdCategoria(){
        return $this->idCategoria;
    }
    public function setIdCategoria($idCategoria){
        $this->idCategoria = $idCategoria;
    }

    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
}
?>