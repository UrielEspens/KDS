<?php
	class DetallesPedido{
		private $idPedido;
		private $estado;
        private $horaCreacion;
		private $nombreCliente;
		private $apellidoCliente;
        private $nombreEmpleado;
        private $apellidoEmpleado;
        private $articulos;

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

        public function getApellidoEmpleado(){
            return $this->apellidoEmpleado;
        }

        public function setApellidoEmpleado($apellidoEmpleado){
            $this->apellidoEmpleado = $apellidoEmpleado;
        }

        public function getHoraCreacion(){
            return $this->horaCreacion;
        }

        public function setHoraCreacion($horaCreacion){
            $this->horaCreacion = $horaCreacion;
        }   

        public function getArticulos(){
            return $this->articulos;
        }

        public function setArticulos($articulos){
            $this->articulos = $articulos;
        } 
	}
?>
