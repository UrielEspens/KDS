<?php
	class Empleado{
        private $idEmpleado;
		private $usuario;
        private $contrasena;
		private $nombre;
		private $primerApe;
        private $segundoApe;
        private $puesto;
        private $imagen;

		function __construct(){
		}

        public function getIdEmpleado(){
            return $this->IdEmpleado;
        }
        public function setIdEmpleado($idEmpleado){
            $this->idEmpleado = $idEmpleado;
        }

        public function getUsuario(){
            return $this->usuario;
        }
        public function setUsuario($usuario){
            $this->usuario = $usuario;
        }

        public function getContrasena(){
            return $this->contrasena;
        }
        public function setContrasena($contrasena){
            $this->contrasena = $contrasena;
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

        public function getPuesto(){
            return $this->puesto;
        }
        public function setPuesto($puesto){
            $this->puesto = $puesto;
        }

        public function getImagen(){
            return $this->imagen;
        }
        public function setImagen($imagen){
            $this->imagen = $imagen;
        }
	}
?>

        