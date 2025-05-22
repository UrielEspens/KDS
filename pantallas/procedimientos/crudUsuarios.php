<?php
// incluye la clase Db
require_once('conexion.php');
require_once('ClaseUsuario.php');


	class CrudUsuarios{
		// constructor de la clase
		public function __construct(){}

		// Funcion para insertar usuarios
		public function insertar($usuario){
			$db=Db::conectar();
			$insert=$db->prepare('INSERT INTO empleados(usuario,contrasena,nombre,primerApe,segundoApe,puesto,imagen) values(:usuario, :contrasena, :nombre, :primerApe, :segundoApe, :puesto, :imagen)');
			$insert->bindValue(':usuario',$usuario->getUsuario());
			$insert->bindValue(':contrasena',$usuario->getContrasena());
			$insert->bindValue(':nombre',$usuario->getNombre());
			$insert->bindValue(':primerApe',$usuario->getPrimerApe());
			$insert->bindValue(':segundoApe',$usuario->getSegundoApe());
			$insert->bindValue(':puesto',$usuario->getPuesto());
			$insert->bindValue(':imagen',$usuario->getImagen());
			/*
			echo "<pre>";
				var_dump([
					':usuario' => $usuario->getUsuario(),
					':contraseña' => $usuario->getContraseña(),
					':nombre' => $usuario->getNombre(),
					':primerApe' => $usuario->getPrimerApe(),
					':segundoApe' => $usuario->getSegundoApe(),
					':puesto' => $usuario->getPuesto(),	
					':imagen' => $usuario->getImagen()
				]);
			echo "</pre>";
			exit;
			*/
			$insert->execute();
		}

		// Funcion para actualizar usuarios
		public function actualizar($usuario){
			$db=Db::conectar();
			$update=$db->prepare('UPDATE empleados SET usuario=:usuario, contraseña=:contraseña, nombre=:nombre, primerApe=:primerApe, segundoApe=:segundoApe, puesto=:puesto, imagen=:imagen WHERE idEmpleado=:idEmpleado');
			$update->bindValue('usuario',$usuario->getUsuario());
			$update->bindValue('contraseña',$usuario->getContraseña());
			$update->bindValue('nombre',$usuario->getNombre());
			$update->bindValue('primerApe',$usuario->getPrimerApe());
			$update->bindValue('segundoApe',$usuario->getSegundoApe());
			$update->bindValue('puesto',$usuario->getPuesto());
			$update->bindValue('imagen',$usuario->getImagen());
			$update->execute();
		}

		// Funcion para eliminar usuarios
		public function eliminar($idEmpleado){
			$db=Db::conectar();
			$delete=$db->prepare('DELETE FROM empleados WHERE idEmpleado=:idEmpleado');
			$delete->bindValue('idEmpleado',$idEmpleado);
			$delete->execute();
		}
		
		// Funcion para obtener un usuario
		public function obtenerUsuario($idEmpleado){
			$db=Db::conectar();
			$select=$db->prepare('SELECT * FROM empleados WHERE idEmpleado=:idEmpleado');
			$select->bindValue('idEmpleado',$idEmpleado);
			$select->execute();

			$usuario= $select->fetch();
			$myUsuario = new Empleado();
			$myUsuario -> setIdEmpleado($usuario['idEmpleado']);
			$myUsuario -> setUsuario($usuario['usuario']);
			$myUsuario -> setContraseña($usuario['contraseña']);
			$myUsuario -> setNombre($usuario['nombre']);
			$myUsuario -> setPrimerApe($usuario['primerApe']);
			$myUsuario -> setSegundoApe($usuario['segundoApe']);
			$myUsuario -> setPuesto($usuario['puesto']);
			$myUsuario -> setImagen($usuario['imagen']);
			return $myUsuario;
		}

		// Funcion para obtener la lista de usuarios
		public function obtenerListaUsuarios(){
			$db=Db::conectar();
			$listaUsuarios = [];
			$select=$db->query('SELECT * FROM empleados');

			foreach(select ->fetchAll() as $usuario){
				$myUsuario= new Empleado();
				$myUsuario->setIdEmpleado($usuario['idEmpleado']);
				$myUsuario->setUsuario($usuario['usuario']);
				$myUsuario->setContraseña($usuario['contraseña']);
				$myUsuario->setNombre($usuario['nombre']);
				$myUsuario->setPrimerApe($usuario['primerApe']);
				$myUsuario->setSegundoApe($usuario['segundoApe']);
				$myUsuario->setPuesto($usuario['puesto']);
				$myUsuario->setImagen($usuario['imagen']);
				$listaUsuarios[]=$myUsuario;
			}
			return $listaUsuarios;
		}
	}	

?>
