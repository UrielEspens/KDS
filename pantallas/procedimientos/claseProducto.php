<?php
// Definir la clase Producto
class Producto {
    // Declara las propiedades privadas de la clase
    private $nombreProducto;
    private $precioProducto;
    private $cantidadProducto;

    // Constructor
    function __construct() {
    }

    // Getters y setters
    public function getNombreProducto() {
        return $this->nombreProducto;
    }

    public function setNombreProducto($nombreProducto) {
        $this->nombreProducto = $nombreProducto;
    }

    public function getPrecioProducto() {
        return $this->precioProducto;
    }

    public function setPrecioProducto($precioProducto) {
        $this->precioProducto = $precioProducto;
    }

    public function getCantidadProducto() {
        return $this->cantidadProducto;
    }

    public function setCantidadProducto($cantidadProducto) {
        $this->cantidadProducto = $cantidadProducto;
    }
}
?>