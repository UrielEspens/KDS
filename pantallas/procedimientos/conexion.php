<?php
    // Definir la clase DB para manejar la conexión a la base de datos
    class Db{
        // Declara una propiedad estática y privada para almacenar la instancia de la conexión PDO
        private static $conexion=NULL;
        // Constructor
        private function __construct (){}

        // Método estático público para obtener la conexión a la base de datos
        public static function conectar(){
            $pdo_options[PDO::ATTR_ERRMODE]=PDO::ERRMODE_EXCEPTION;
            // Crea una nueva instancia de PDO para conectarse a la base de datos.
            // Se conecta a 'mysql:host=localhost;dbname=kds' con el usuario 'root' y sin contraseña
            self::$conexion= new PDO('mysql:host=localhost;dbname=kds','root','',$pdo_options);
            return self::$conexion;
        }
    }
?>