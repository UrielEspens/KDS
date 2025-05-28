<?php
class Pago {
    private $idPago;
    private $idPedido;
    private $metodoPago;
    private $montoPagado;
    private $fechaHoraPago;

    public function __construct() {}

    // Getters
    public function getIdPago() {
        return $this->idPago;
    }

    public function getIdPedido() {
        return $this->idPedido;
    }

    public function getMetodoPago() {
        return $this->metodoPago;
    }

    public function getMontoPagado() {
        return $this->montoPagado;
    }

    public function getFechaHoraPago() {
        return $this->fechaHoraPago;
    }

    // Setters
    public function setIdPago($idPago) {
        $this->idPago = $idPago;
    }

    public function setIdPedido($idPedido) {
        $this->idPedido = $idPedido;
    }

    public function setMetodoPago($metodoPago) {
        $this->metodoPago = $metodoPago;
    }

    public function setMontoPagado($montoPagado) {
        $this->montoPagado = $montoPagado;
    }

    public function setFechaHoraPago($fechaHoraPago) {
        $this->fechaHoraPago = $fechaHoraPago;
    }
}
?>