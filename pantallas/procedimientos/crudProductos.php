<?php
require_once('conexion.php');
require_once('claseProducto.php');
require_once('claseCategoria.php');
require_once('claseInfoProducto.php');

class CrudProductos {

    public function __construct(){}

    // Muestra la representación HTML de un producto en la vista de usuario
    public function mostrarProducto(InfoProducto $producto) {
        echo '<div class="menu-item" data-product-id="' . $producto->getIdProducto() . '" data-categoria-id="' . $producto->getIdCategoria() . '">';
        echo '<img src="data:image/jpeg;base64, ' . base64_encode($producto->getImagen()) . '" alt="' . ($producto->getNombre()) . '">';
        echo '<div class="item-details">';
        echo '<h3>' . $producto->getNombre() . '</h3>';
        echo '<p>' . $producto->getDescripcion() . '</p>';
        echo '<p class="item-price">$' . number_format($producto->getPrecio(), 2) . ' MXN</p>';
        echo '</div>';

        // Formulario para añadir el producto al carrito
        echo '<form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">';
        echo '<input type="hidden" name="accionCarrito" value="añadir">';
        echo '<input type="hidden" name="idProducto" value="' . $producto->getIdProducto() . '">';
        echo '<button type="submit" class="add-buttons btn">+</button>';
        echo '</form>';
        echo '</div>';
    }

    // Muestra la representación HTML de un producto en la vista de administración
    public function mostrarProductoEditable(InfoProducto $producto) {
        echo '<div class="menu-item" data-product-id="' . $producto->getIdProducto() . '" data-categoria-id="' . $producto->getIdCategoria() . '">';
        echo '<img src="data:image/jpeg;base64, ' . base64_encode($producto->getImagen()) . '" alt="' . ($producto->getNombre()) . '">';
        echo '<div class="item-details">';
        echo '<h3>' . $producto->getNombre() . '</h3>';
        echo '<p>' . $producto->getDescripcion() . '</p>';
        echo '<p class="item-price">$' . number_format($producto->getPrecio(), 2) . ' MXN</p>';
        echo '</div>';

        // Contenedor para los botones de acción de editar y eliminar
        echo '<div class="botones-accion">';
        echo '<a href="modificarProducto.php?idProducto=' . $producto->getIdProducto() . '" class="btn botonEditar"><img src="../img/inicio/lapiz.png" alt="Editar"></a>';
        echo '<a href="eliminarProducto.php?idProducto=' . $producto->getIdProducto() . '" class="btn botonEliminar"><img src="../img/inicio/basurero.png" alt="Eliminar"></a>';
        echo '</div>';
        
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

    //Obtiene un producto específico por su ID
    public function obtenerProductoPorId($idProducto) {
            $db = Db::conectar();
            
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

    // Busca productos por nombre o descripción
    public function buscarProductos($textoBusqueda, $tipoBusqueda = 'inicio') {
        $db = Db::conectar();
        $resultados = [];
        $textoBusquedaUpper = strtoupper($textoBusqueda);
        $buscandoProducto = 'SELECT * FROM producto WHERE ';
        $buscandoProducto .= 'UPPER(nombre) LIKE :textoBusqueda OR UPPER(descripcion) LIKE :textoBusqueda';
        $param = $textoBusquedaUpper . '%';
        $buscandoProducto .= ' ORDER BY nombre ASC'; 
        $select = $db->prepare($buscandoProducto);
        $select->bindValue(':textoBusqueda', $param);
        $select->execute();

        // Itera sobre los resultados y crea objetos InfoProducto
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

    // Actualiza un producto existente en la base de datos
    public function actualizarProducto($idProducto, $nombre, $descripcion, $precio, $idCategoria, $imagen = null) {
        $db = Db::conectar();
        if ($imagen !== null) {
            $stmt = $db->prepare("UPDATE producto SET nombre = :nombre, descripcion = :descripcion, precio = :precio, idCategoria = :idCategoria, imagen = :imagen WHERE idProducto = :idProducto");
            $stmt->bindValue(':imagen', $imagen, PDO::PARAM_LOB);
        } else {
            $stmt = $db->prepare("UPDATE producto SET nombre = :nombre, descripcion = :descripcion, precio = :precio, idCategoria = :idCategoria WHERE idProducto = :idProducto");
        }
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':descripcion', $descripcion);
        $stmt->bindValue(':precio', $precio);
        $stmt->bindValue(':idCategoria', $idCategoria); 
        $stmt->bindValue(':idProducto', $idProducto);

        return $stmt->execute();
        
    }
    // Elimina un producto de la base de datos por su ID
    public function eliminarProducto($idProducto) {      
        $db = Db::conectar();
        $stmt = $db->prepare("DELETE FROM producto WHERE idProducto = :id");
        $stmt->bindParam(':id', $idProducto, PDO::PARAM_INT);
        return $stmt->execute();
    }
    // Agrega un nuevo producto a la base de datos
    public function agregarProducto($producto) { 
        $db = Db::conectar();
        $insert = $db->prepare("INSERT INTO producto (nombre, descripcion, precio, idCategoria, imagen) VALUES (:nombre, :descripcion, :precio, :idCategoria, :imagen)");
        $insert->bindValue(':nombre', $producto->getNombre());
        $insert->bindValue(':descripcion', $producto->getDescripcion());
        $insert->bindValue(':precio', $producto->getPrecio()); 
        $insert->bindValue(':idCategoria', $producto->getIdCategoria());
        $insert->bindValue(':imagen', $producto->getImagen()); 
        return $insert->execute();
    }
    // Inserta una nueva categoría en la base de datos
    public function insertarNuevaCategoria($nombre) { 
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO categorias (nombre) VALUES (:nombre);');
        $insert->bindValue(':nombre', $nombre); 
        return $insert->execute();
    }
        // Elimina una categoría de la base de datos por su ID
        public function eliminarCategoriaPorId($idCategoria) {
            $db = Db::conectar();
            $delete = $db->prepare('DELETE FROM categorias WHERE idCategoria = :idCategoria;');
            $delete->bindParam(':idCategoria', $idCategoria);
            $delete->execute();
            return $delete->rowCount() > 0;
    }
}
?>