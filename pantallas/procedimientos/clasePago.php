<?php
    class Pago {
        private $idPago;
        private $idPedido;
        private $montoPagado;
        private $metodoPago;
        private $fechaPago;
        private $idEmpleadoCajero;

        public function __construct(){}

        public function getIdPago(){ 
            return $this->idPago; 
        }

        public function setIdPago($idPago){ 
            $this->idPago = $idPago; 
        }

        public function getIdPedido(){ 
            return $this->idPedido; 
        }

        public function setIdPedido($idPedido){ 
            $this->idPedido = $idPedido; 
        }

        public function getMontoPagado(){
            return $this->montoPagado; 
        }

        public function setMontoPagado($montoPagado){ 
            $this->montoPagado = $montoPagado; 
        }

        public function getMetodoPago(){ 
            return $this->metodoPago; 
        }

        public function setMetodoPago($metodoPago){ 
            $this->metodoPago = $metodoPago; 
        }

        public function getFechaPago(){ 
            return $this->fechaPago; 
        }

        public function setFechaPago($fechaPago){ 
            $this->fechaPago = $fechaPago; 
        }

        public function getIdEmpleadoCajero(){ 
            return $this->idEmpleadoCajero; 
        }

        public function setIdEmpleadoCajero($idEmpleadoCajero){ 
            $this->idEmpleadoCajero = $idEmpleadoCajero; 
        }
    }
?>