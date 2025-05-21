<?php
require_once('conexion.php');
require_once('claseProducto.php');
require_once('claseCategoria.php');

class CrudProductos {

    public function __construct(){}

    public function mostrarProducto(InfoProducto $producto) { // Asegúrate de que el tipo sea Producto, no InfoProducto si esa es la clase.
        echo '<div class="menu-item" data-product-id="' . $producto->getIdProducto() . '" data-categoria-id="' . $producto->getIdCategoria() . '">';
        echo '<img src="' . $producto->getImagen() . '" alt="' . $producto->getNombre() . '">';
        echo '<div class="item-details">';
        echo '<h3>' . $producto->getNombre() . '</h3>';
        echo '<p>' . $producto->getDescripcion() . '</p>';
        echo '<p class="item-price">$' . number_format($producto->getPrecio(), 2) . ' MXN</p>';
        echo '</div>';
        echo '<button class="add-buttons">+</button>';
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
            $myProducto->setIdCategoria($productoData['idCategoria']); // Asignar el ID de la categoría
            $listaProductos[] = $myProducto;
        }
        return $listaProductos;
      
    }

    // Método para obtener productos por categoría (si lo necesitas para la búsqueda/filtro)
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
}
?>