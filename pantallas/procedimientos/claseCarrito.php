<?php
class ItemsCarrito {
    private $nombreItem;
    private $cantidadItem;
    private $precioItem;

    function __construct(){
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