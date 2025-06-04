<?php
require_once('conexion.php');
require_once('claseEmpleados.php');

class CrudEmpleados {
    public function __construct() {
    }
    // Verifica las credenciales de un empleado en la base de datos
    public function verificarCredenciales($usuario, $contrasena) {
        $db = Db::conectar();
        $sql = 'SELECT idEmpleado, nombre, primerApe, segundoApe, usuario, contrasena, puesto FROM empleados WHERE usuario = :usuario AND contrasena = :contrasena LIMIT 1';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->execute();

        if ($empleadoData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $empleado = new Empleado();
            $empleado->setIdEmpleado($empleadoData['idEmpleado']);
            $empleado->setNombre($empleadoData['nombre']);
            $empleado->setPrimerApe($empleadoData['primerApe']);
            $empleado->setSegundoApe($empleadoData['segundoApe']);
            $empleado->setUsuario($empleadoData['usuario']);
            $empleado->setContrasena($empleadoData['contrasena']);
            $empleado->setPuesto($empleadoData['puesto']);
            return $empleado;
        }
        return null;
    }

    // Obtiene un empleado de la base de datos por su ID
    public function obtenerEmpleadoPorId($idEmpleado) {
        $db = Db::conectar();
        $sql = 'SELECT idEmpleado, nombre, primerApe, segundoApe, usuario, contrasena, puesto FROM empleados WHERE idEmpleado = :idEmpleado LIMIT 1';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idEmpleado', $idEmpleado);
        $stmt->execute();

        if ($empleadoData = $stmt->fetch()) {
            $empleado = new Empleado();
            $empleado->setIdEmpleado($empleadoData['idEmpleado']);
            $empleado->setNombre($empleadoData['nombre']);
            $empleado->setPrimerApe($empleadoData['primerApe']);
            $empleado->setSegundoApe($empleadoData['segundoApe']);
            $empleado->setUsuario($empleadoData['usuario']);
            $empleado->setContrasena($empleadoData['contrasena']);
            $empleado->setPuesto($empleadoData['puesto']);
            return $empleado;
        }
        return null;
    }

    // Muestra una lista de todos los empleados
    public function mostrarEmpleados() {
        $db = Db::conectar();
        $listaEmpleados = [];
        $select = $db->query('SELECT idEmpleado, nombre, primerApe, segundoApe, usuario, puesto FROM empleados');

        foreach ($select->fetchAll(PDO::FETCH_ASSOC) as $empleadoData) {
            $empleado = new Empleado();
            $empleado->setIdEmpleado($empleadoData['idEmpleado']);
            $empleado->setNombre($empleadoData['nombre']);
            $empleado->setPrimerApe($empleadoData['primerApe']);
            $empleado->setSegundoApe($empleadoData['segundoApe']);
            $empleado->setUsuario($empleadoData['usuario']);
            $empleado->setPuesto($empleadoData['puesto']);
            $listaEmpleados[] = $empleado;
        }
        return $listaEmpleados;
    }

    // Inserta un nuevo empleado en la base de datos
    public function insertar(Empleado $empleado) {
        $db = Db::conectar();
        $sql = 'INSERT INTO empleados (nombre, primerApe, segundoApe, usuario, contrasena, puesto) VALUES (:nombre, :primerApe, :segundoApe, :usuario, :contrasena, :puesto)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nombre', $empleado->getNombre(), PDO::PARAM_STR);
        $stmt->bindParam(':primerApe', $empleado->getPrimerApe(), PDO::PARAM_STR);
        $stmt->bindParam(':segundoApe', $empleado->getSegundoApe(), PDO::PARAM_STR);
        $stmt->bindParam(':usuario', $empleado->getUsuario(), PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $empleado->getContrasena(), PDO::PARAM_STR);
        $stmt->bindParam(':puesto', $empleado->getPuesto(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Actualiza un empleado existente en la base de datos
    public function actualizar(Empleado $empleado) {
        $db = Db::conectar();
        $sql = 'UPDATE empleados SET nombre = :nombre, primerApe = :primerApe, segundoApe = :segundoApe, usuario = :usuario, contrasena = :contrasena, puesto = :puesto WHERE idEmpleado = :idEmpleado';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nombre', $empleado->getNombre(), PDO::PARAM_STR);
        $stmt->bindParam(':primerApe', $empleado->getPrimerApe(), PDO::PARAM_STR);
        $stmt->bindParam(':segundoApe', $empleado->getSegundoApe(), PDO::PARAM_STR);
        $stmt->bindParam(':usuario', $empleado->getUsuario(), PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $empleado->getContrasena(), PDO::PARAM_STR);
        $stmt->bindParam(':puesto', $empleado->getPuesto(), PDO::PARAM_STR);
        $stmt->bindParam(':idEmpleado', $empleado->getIdEmpleado(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Elimina un empleado de la base de datos por su ID
    public function eliminar($idEmpleado) {
        $db = Db::conectar();
        $sql = 'DELETE FROM empleados WHERE idEmpleado = :idEmpleado';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>