<?php
	class Producto{
		private $idProducto;
		private $nombre;
		private $descripcion;
		private $idCategoria;
		private $precio;
        private $imagen;

		function __construct(){

		}

		public function getIdProducto(){
			return $this->idProducto;
		}

		public function setIdProducto($idProducto){
			$this->idProducto = $idProducto;
		}

        public function getNombre(){
            return $this->nombre;
        }

        public function setNombre($nombre){
            $this->nombre = $nombre;
        }

		public function getDescripcion(){
			return $this->descripcion;
		}

		public function setDescripcion($descripcion){
			$this->descripcion = $descripcion;
		}

		public function getIdCategoria(){
			return $this->idCategoria;
		}

		public function setIdCategoria($idCategoria){
			$this->idCategoria = $idCategoria;
		}

        public function getPrecio(){
			return $this->precio;
		}

		public function setPrecio($precio){
			$this->precio = $precio;
		}
		
		public function getImagen(){
			return $this->imagen;
		}

		public function setImagen($imagen){
			$this->imagen = $imagen;
		}
	}
?>
