<?php
// procedimientos/crudEmpleados.php

require_once('conexion.php'); // Asegúrate de que esta ruta sea correcta
require_once('claseEmpleados.php'); // Incluye la clase Empleado

class CrudEmpleados {
    public function __construct() {
        // Constructor vacío
    }

    /**
     * Verifica las credenciales de un empleado para el inicio de sesión.
     * @param string $usuario El nombre de usuario.
     * @param string $contrasena La contraseña (sin hash en este ejemplo).
     * @return Empleado|null Un objeto Empleado si las credenciales son válidas, o null si no.
     */
    public function verificarCredenciales($usuario, $contrasena) {
        $db = Db::conectar();
        $sql = 'SELECT idEmpleado, nombre, primerApe, segundoApe, usuario, contrasena, puesto FROM empleados WHERE usuario = :usuario AND contrasena = :contrasena LIMIT 1';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $contrasena); // ¡ADVERTENCIA: Contraseña en texto plano!
        $stmt->execute();

        if ($empleadoData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $empleado = new Empleado();
            $empleado->setIdEmpleado($empleadoData['idEmpleado']);
            $empleado->setNombre($empleadoData['nombre']);
            $empleado->setPrimerApe($empleadoData['primerApe']);
            $empleado->setSegundoApe($empleadoData['segundoApe']);
            $empleado->setUsuario($empleadoData['usuario']);
            $empleado->setContrasena($empleadoData['contrasena']); // No se recomienda devolver la contraseña
            $empleado->setPuesto($empleadoData['puesto']);
            return $empleado;
        }
        return null;
    }

    /**
     * Obtiene los detalles de un empleado por su ID.
     * @param int $idEmpleado El ID del empleado.
     * @return Empleado|null Un objeto Empleado si se encuentra, o null si no.
     */
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

    /**
     * Muestra una lista de todos los empleados.
     * @return array Un array de objetos Empleado.
     */
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

    /**
     * Inserta un nuevo empleado en la base de datos.
     * @param Empleado $empleado El objeto Empleado a insertar.
     * @return bool True en éxito, false en fallo.
     */
    public function insertar(Empleado $empleado) {
        $db = Db::conectar();
        // Nota: idEmpleado puede ser AUTO_INCREMENT, si es así, no lo incluyas en el INSERT.
        $sql = 'INSERT INTO empleados (nombre, primerApe, segundoApe, usuario, contrasena, puesto) VALUES (:nombre, :primerApe, :segundoApe, :usuario, :contrasena, :puesto)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nombre', $empleado->getNombre(), PDO::PARAM_STR);
        $stmt->bindParam(':primerApe', $empleado->getPrimerApe(), PDO::PARAM_STR);
        $stmt->bindParam(':segundoApe', $empleado->getSegundoApe(), PDO::PARAM_STR);
        $stmt->bindParam(':usuario', $empleado->getUsuario(), PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $empleado->getContrasena(), PDO::PARAM_STR); // ¡ADVERTENCIA!
        $stmt->bindParam(':puesto', $empleado->getPuesto(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Actualiza un empleado existente en la base de datos.
     * @param Empleado $empleado El objeto Empleado con los datos actualizados.
     * @return bool True en éxito, false en fallo.
     */
    public function actualizar(Empleado $empleado) {
        $db = Db::conectar();
        $sql = 'UPDATE empleados SET nombre = :nombre, primerApe = :primerApe, segundoApe = :segundoApe, usuario = :usuario, contrasena = :contrasena, puesto = :puesto WHERE idEmpleado = :idEmpleado';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nombre', $empleado->getNombre(), PDO::PARAM_STR);
        $stmt->bindParam(':primerApe', $empleado->getPrimerApe(), PDO::PARAM_STR);
        $stmt->bindParam(':segundoApe', $empleado->getSegundoApe(), PDO::PARAM_STR);
        $stmt->bindParam(':usuario', $empleado->getUsuario(), PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $empleado->getContrasena(), PDO::PARAM_STR); // ¡ADVERTENCIA!
        $stmt->bindParam(':puesto', $empleado->getPuesto(), PDO::PARAM_STR);
        $stmt->bindParam(':idEmpleado', $empleado->getIdEmpleado(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Elimina un empleado de la base de datos por su ID.
     * @param int $idEmpleado El ID del empleado a eliminar.
     * @return bool True en éxito, false en fallo.
     */
    public function eliminar($idEmpleado) {
        $db = Db::conectar();
        $sql = 'DELETE FROM empleados WHERE idEmpleado = :idEmpleado';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>