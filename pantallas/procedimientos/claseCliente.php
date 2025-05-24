<?php
class Cliente {
    private $idCliente;
    private $nombre;
    private $primerApe;
    private $segundoApe; // Opcional, si lo usas
    private $telefono;
    private $calle;
    private $numeroCasa;
    private $referenciaCasa;

    public function __construct() {
        // Constructor vacío
    }

    // Getters
    public function getIdCliente() {
        return $this->idCliente;
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

    public function getTelefono() {
        return $this->telefono;
    }

    public function getCalle() {
        return $this->calle;
    }

    public function getNumeroCasa() {
        return $this->numeroCasa;
    }

    public function getReferenciaCasa() {
        return $this->referenciaCasa;
    }

    // Setters
    public function setIdEmpleado($idCliente) {
        $this->idCliente = $idCliente;
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

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setCalle($calle) {
        $this->calle = $calle;
    }

    public function setNumeroCasa($numeroCasa) {
        $this->numeroCasa = $numeroCasa;
    }

    public function setReferenciaCasa($referenciaCasa) {
        $this->referenciaCasa = $referenciaCasa;
    }
}
?>