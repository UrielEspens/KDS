<?php
	class Pedido{
		private $idPedido;
		private $estado;
		private $nombreCliente;
		private $apellidoCliente;

		function __construct(){
		}

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

	}
?>
