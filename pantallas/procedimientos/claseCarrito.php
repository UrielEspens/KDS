<?php
// Define la clase ItemsCarrito
class ItemsCarrito {
    // Declara las propiedades privadas de la clase
    private $idItem;
    private $nombreItem;
    private $precioItem;
    private $cantidadItem;
    
    // Constructor
    function __construct(){
    }
    
    // Getters y setters
    public function getIdItem(){
        return $this->idItem;
    }
    public function setIdItem($idItem){
        $this->idItem = $idItem;
    }
    public function getNombreItem(){
        return $this->nombreItem;
    }
    public function setNombreItem($nombreItem){
        $this->nombreItem = $nombreItem;
    }

    public function getCantidadItem(){
        return $this->cantidadItem;
    }
    public function setCantidadItem($cantidadItem){
        $this->cantidadItem = $cantidadItem;
    }

    public function getPrecioItem(){
        return $this->precioItem;
    }
    public function setPrecioItem($precioItem){
        $this->precioItem = $precioItem;
    }
}
?>