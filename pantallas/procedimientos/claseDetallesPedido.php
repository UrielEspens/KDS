<?php
// Define la clase DetallesPedido
class DetallesPedido{
    // Declara las propiedades privadas de la clase
    private $idPedido;
    private $estado;
    private $horaCreacion;
    private $nombreCliente;
    private $apellidoCliente;
    private $idCliente;
    private $nombreEmpleado;
    private $apellidoEmpleado;
    private $idEmpleado;
    private $articulos = [];
    private $total;
    
    // Constructor
    function __construct(){
    }

    // Getters y setter para el pedido
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
    // Getters y setters para idCliente
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

    public function getIdCliente(){
        return $this->idCliente;
    }

    public function setIdCliente($idCliente){
        $this->idCliente = $idCliente;
    }
    
    // Getters y setters para idEmpleado
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

    public function getIdEmpleado(){
        return $this->idEmpleado;
    }

    public function setIdEmpleado($idEmpleado){
        $this->idEmpleado = $idEmpleado;
    }
    
    // Getters y setters para los articulos
    public function getHoraCreacion(){
        return $this->horaCreacion;
    }

    public function setHoraCreacion($horaCreacion){
        $this->horaCreacion = $horaCreacion;
    }

    public function getArticulos(){
        return $this->articulos;
    }

    public function setArticulos(array $articulos){
        $this->articulos = $articulos;
    }

    public function addArticulo(array $articulo){
        $this->articulos[] = $articulo;
    }

    public function getTotal(){
        return $this->total;
    }

    public function setTotal($total){
        $this->total = $total;
    }
}
?>