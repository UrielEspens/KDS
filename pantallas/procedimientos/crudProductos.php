<?php
require_once('conexion.php');
require_once('claseProducto.php');
require_once('claseCategoria.php');
require_once('claseInfoProducto.php');

class CrudProductos {

    public function __construct(){}

    public function mostrarProducto(InfoProducto $producto) {
        echo '<div class="menu-item" data-product-id="' . $producto->getIdProducto() . '" data-categoria-id="' . $producto->getIdCategoria() . '">';
        echo '<img src="' . $producto->getImagen() . '" alt="' . ($producto->getNombre()) . '">';
        echo '<div class="item-details">';
        echo '<h3>' . $producto->getNombre() . '</h3>';
        echo '<p>' . $producto->getDescripcion() . '</p>';
        echo '<p class="item-price">$' . number_format($producto->getPrecio(), 2) . ' MXN</p>';
        echo '</div>';

        // ¡CAMBIO AQUÍ! El formulario ahora apunta a adminCarrito.php
        echo '<form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">';
        echo '<input type="hidden" name="accionCarrito" value="añadir">';
        echo '<input type="hidden" name="idProducto" value="' . $producto->getIdProducto() . '">';
        echo '<button type="submit" class="add-buttons btn">+</button>';
        echo '</form>';
        echo '</div>';
    }
    // Método para obtener todas las categorías
    public function obtenerCategorias(){
        $db = Db::conectar();
        $listaCategorias = [];
        $select = $db->query('SELECT idCategoria, nombre FROM categorias ORDER BY nombre');

        foreach($select->fetchAll() as $categoriaData){
            $myCategoria = new Categoria();
            $myCategoria->setIdCategoria($categoriaData['idCategoria']);
            $myCategoria->setNombre($categoriaData['nombre']);
            $listaCategorias[] = $myCategoria;
        }
        return $listaCategorias;
    }

    // Método para obtener todos los productos
    public function obtenerTodosLosProductos(){
        $db = Db::conectar();
        $listaProductos = [];
         $select = $db->query('SELECT   p.idProducto,
                                        p.nombre,
                                        p.descripcion,
                                        p.precio,
                                        p.imagen,
                                        c.nombre AS nombreCategoria,
                                        p.idCategoria
                                        FROM
                                            producto p
                                        INNER JOIN categorias c ON p.idCategoria = c.idCategoria
                                        ORDER BY p.nombre;');
        foreach($select->fetchAll() as $productoData){
            $myProducto = new InfoProducto();
            $myProducto->setIdProducto($productoData['idProducto']);
            $myProducto->setNombre($productoData['nombre']);
            $myProducto->setDescripcion($productoData['descripcion']);
            $myProducto->setPrecio($productoData['precio']);
            $myProducto->setImagen($productoData['imagen']);
            $myProducto->setIdCategoria($productoData['idCategoria']); 
            $listaProductos[] = $myProducto;
        }
        return $listaProductos;
      
    }

    public function obtenerProductoPorId($idProducto) {
            $db = Db::conectar();
            // CAMBIO CLAVE: Usar prepare() en lugar de query() para consultas con parámetros
            $select = $db->prepare('SELECT *
                                    FROM producto
                                    WHERE idProducto = :idProducto;');
            $select->bindValue('idProducto',$idProducto);
			$select->execute();

			$producto=$select->fetch();
			$myProducto= new InfoProducto();
			$myProducto->setIdProducto($producto['idProducto']);
			$myProducto->setNombre($producto['nombre']);
			$myProducto->setDescripcion($producto['descripcion']);
			$myProducto->setIdCategoria($producto['idCategoria']);
			$myProducto->setPrecio($producto['precio']);
			$myProducto->setImagen($producto['imagen']);
			return $myProducto;
    }



    // Método para obtener productos por categoría
    public function obtenerProductosPorCategoria($idCategoria){
        $db = Db::conectar();
        $listaProductos = [];
            $select = $db->prepare('SELECT
                                            p.idProducto,
                                            p.nombre,
                                            p.descripcion,
                                            p.precio,
                                            p.imagen,
                                            c.nombre AS nombreCategoria
                                        FROM
                                            producto p
                                        INNER JOIN categorias c ON p.idCategoria = c.idCategoria
                                        WHERE p.idCategoria = :idCategoria
                                        ORDER BY p.nombre;');
            $select->bindValue(':idCategoria', $idCategoria);
            $select->execute();
            foreach($select->fetchAll() as $productoData){
                $myProducto = new InfoProducto();
                $myProducto->setIdProducto($productoData['idProducto']);
                $myProducto->setNombre($productoData['nombre']);
                $myProducto->setDescripcion($productoData['descripcion']);
                $myProducto->setPrecio($productoData['precio']);
                $myProducto->setImagen($productoData['imagen']);
                $myProducto->setIdCategoria($idCategoria); 
                $listaProductos[] = $myProducto;
            }
            return $listaProductos;
    }

    public function buscarProductos($textoBusqueda, $tipoBusqueda = 'inicio') {
        $db = Db::conectar();
        $resultados = [];
        $textoBusquedaUpper = strtoupper($textoBusqueda);
        // La consulta SQL ahora es fija para "inicio"
        $buscandoProducto = 'SELECT * FROM producto WHERE ';
        $buscandoProducto .= 'UPPER(nombre) LIKE :textoBusqueda OR UPPER(descripcion) LIKE :textoBusqueda';
        $param = $textoBusquedaUpper . '%';
        $buscandoProducto .= ' ORDER BY nombre ASC'; 
        $select = $db->prepare($buscandoProducto);
        $select->bindValue(':textoBusqueda', $param);
        $select->execute();

        foreach ($select->fetchAll() as $productoData) {
            $producto = new InfoProducto();
            $producto->setIdProducto($productoData['idProducto']);
            $producto->setNombre($productoData['nombre']);
            $producto->setDescripcion($productoData['descripcion']);
            $producto->setIdCategoria($productoData['idCategoria']);
            $producto->setPrecio($productoData['precio']);
            $producto->setImagen($productoData['imagen']);
            $resultados[] = $producto;
        }
        return $resultados;
    }
}
?>