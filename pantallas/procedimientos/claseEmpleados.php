<?php
// procedimientos/claseEmpleado.php

class Empleado {
    private $idEmpleado;
    private $nombre;
    private $primerApe;
    private $segundoApe;
    private $usuario;
    private $contrasena; 
    private $puesto;

    public function __construct() {
        // Constructor vacío
    }

    // Getters
    public function getIdEmpleado() {
        return $this->idEmpleado;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getPrimerApe() {
        return $this->primerApe;
    }

    public function getSegundoApe() {
        return $this->segundoApe;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getContrasena() {
        return $this->contrasena;
    }

    public function getPuesto() {
        return $this->puesto;
    }

    // Setters
    public function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setPrimerApe($primerApe) {
        $this->primerApe = $primerApe;
    }

    public function setSegundoApe($segundoApe) {
        $this->segundoApe = $segundoApe;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setContrasena($contrasena) {
        $this->contrasena = $contrasena;
    }

    public function setPuesto($puesto) {
        $this->puesto = $puesto;
    }
}
?>