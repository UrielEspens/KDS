<?php
    // Definir la clase Pedido
	class Pedido{
        // Declara las propiedades privadas de la clase
		private $idPedido;
		private $estado;
		private $fechaHora;
		private $montoTotal;
		private $tipoPago;
		private $nombreCliente;
		private $apellidoCliente;
		private $nombreEmpleado;
		private $apellidoEmpleado;

        // Constructor
		function __construct(){
		}

        // Getters y setters
        public function getIdPedido(){
            return $this->idPedido;
        }

        public function setIdPedido($idPedido){
            $this->idPedido = $idPedido;
        }

        public function getEstado(){
            return $this->estado;
        }

        public function setEstado($estado){
            $this->estado = $estado;
        }

        public function getFechaHora(){
            return $this->fechaHora;
        }

        public function setFechaHora($fechaHora){
            $this->fechaHora = $fechaHora;
        }

        public function getMontoTotal(){
            return $this->montoTotal;
        }

        public function setMontoTotal($montoTotal){
            $this->montoTotal = $montoTotal;
        }

        public function getTipoPago(){
            return $this->tipoPago;
        }

        public function setTipoPago($tipoPago){
            $this->tipoPago = $tipoPago;
        }

        public function getNombreCliente(){
            return $this->nombreCliente;
        }

        public function setNombreCliente($nombreCliente){
            $this->nombreCliente = $nombreCliente;
        }

        public function getApellidoCliente(){
            return $this->apellidoCliente;
        }

        public function setApellidoCliente($apellidoCliente){
            $this->apellidoCliente = $apellidoCliente;
        }

        public function getNombreEmpleado(){
            return $this->nombreEmpleado;
        }

        public function setNombreEmpleado($nombreEmpleado){
            $this->nombreEmpleado = $nombreEmpleado;
        }

        public function getApellidoEmpleado(){
            return $this->apellidoEmpleado;
        }

        public function setApellidoEmpleado($apellidoEmpleado){
            $this->apellidoEmpleado = $apellidoEmpleado;
        }
	}
?>