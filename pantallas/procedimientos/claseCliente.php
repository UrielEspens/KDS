<?php
class Cliente {
    private $idCliente;
    private $nombre;
    private $primerApe;
    private $segundoApe;
    private $telefono;
    private $calle;
    private $numeroCasa;
    private $referenciaCasa;
    
    function __construct(){
    }

    public function getIdCliente(){
        return $this->idCliente;
    }

    public function setIdCliente($idCliente){
        $this->idCliente = $idCliente;
    }

    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getPrimerApe(){
        return $this->primerApe;
    }
    public function setPrimerApe($primerApe){
        $this->primerApe = $primerApe;
    }

    public function getSegundoApe(){
        return $this->segundoApe;
    }
    public function setSegundoApe($segundoApe){
        $this->segundoApe = $segundoApe;
    }

    public function getTelefono(){
        return $this->telefono;
    }
    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }

    public function getCalle(){
        return $this->calle;
    }
    public function setCalle($calle){
        $this->calle = $calle;
    }

    public function getNumeroCasa(){
        return $this->numeroCasa;
    }
    public function setNumeroCasa($numeroCasa){
        $this->numeroCasa = $numeroCasa;
    }

    public function getReferenciaCasa(){
        return $this->referenciaCasa;
    }
    public function setReferenciaCasa($referenciaCasa){
        $this->referenciaCasa = $referenciaCasa;
    }
}
?>