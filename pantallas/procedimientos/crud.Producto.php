<?php
require_once('conexion.php');
require_once('claseProducto.php');
require_once('claseCategoria.php');
require_once('claseInfoProducto.php');

class CrudProducto {

    public function __construct(){}


    public function insertar(InfoProducto $producto){
        $db = Db::conectar();
        $insert = $db->prepare(
            'INSERT INTO producto(nombre, descripcion, idCategoria, precio, imagen)
             VALUES(:nombre, :descripcion, :idCategoria, :precio, :imagen)'
        );
        $insert->bindValue('nombre', $producto->getNombre());
        $insert->bindValue('descripcion', $producto->getDescripcion());
        $insert->bindValue('idCategoria', $producto->getIdCategoria(), PDO::PARAM_INT);
        $insert->bindValue('precio', $producto->getPrecio(), PDO::PARAM_STR);
        $insert->bindValue('imagen', $producto->getImagen(), PDO::PARAM_LOB);
        $insert->execute();
    }


    public function obtenerProducto($idProducto){
        $db = Db::conectar();
        $select = $db->prepare('SELECT * FROM producto WHERE idProducto=:idProducto');
        $select->bindValue('idProducto', $idProducto, PDO::PARAM_INT);
        $select->execute();
        $productoData = $select->fetch(PDO::FETCH_ASSOC);

        if ($productoData) {
            $myProducto = new InfoProducto();
            $myProducto->setIdProducto($productoData['idProducto']);
            $myProducto->setNombre($productoData['nombre']);
            $myProducto->setDescripcion($productoData['descripcion']);
            $myProducto->setIdCategoria($productoData['idCategoria']);
            $myProducto->setPrecio($productoData['precio']);
            $myProducto->setImagen($productoData['imagen']);
            return $myProducto;
        }
        return null; // Retorna null si el producto no se encuentra
    }


    public function obtenerTodosLosProductos(){
        $db = Db::conectar();
        $listaProductos = [];
        $select = $db->query('SELECT * FROM producto ORDER BY nombre ASC');

        foreach($select->fetchAll(PDO::FETCH_ASSOC) as $productoData){
            $myProducto = new InfoProducto();
            $myProducto->setIdProducto($productoData['idProducto']);
            $myProducto->setNombre($productoData['nombre']);
            $myProducto->setDescripcion($productoData['descripcion']);
            $myProducto->setIdCategoria($productoData['idCategoria']);
            $myProducto->setPrecio($productoData['precio']);
            $myProducto->setImagen($productoData['imagen']);
            $listaProductos[] = $myProducto;
        }
        return $listaProductos;
    }

    public function actualizar(InfoProducto $producto){
        $db = Db::conectar();
        // Si la imagen es null, no la actualizamos en la base de datos
        if ($producto->getImagen() === null) {
            $update = $db->prepare(
                'UPDATE producto SET nombre=:nombre, descripcion=:descripcion, idCategoria=:idCategoria, precio=:precio
                 WHERE idProducto=:idProducto'
            );
        } else {
            $update = $db->prepare(
                'UPDATE producto SET nombre=:nombre, descripcion=:descripcion, idCategoria=:idCategoria, precio=:precio, imagen=:imagen
                 WHERE idProducto=:idProducto'
            );
            $update->bindValue('imagen', $producto->getImagen(), PDO::PARAM_LOB);
        }

        $update->bindValue('nombre', $producto->getNombre());
        $update->bindValue('descripcion', $producto->getDescripcion());
        $update->bindValue('idCategoria', $producto->getIdCategoria(), PDO::PARAM_INT);
        $update->bindValue('precio', $producto->getPrecio(), PDO::PARAM_STR);
        $update->bindValue('idProducto', $producto->getIdProducto(), PDO::PARAM_INT);
        $update->execute();
    }

    public function eliminar($idProducto){
        $db = Db::conectar();
        $delete = $db->prepare('DELETE FROM producto WHERE idProducto=:idProducto');
        $delete->bindValue('idProducto', $idProducto, PDO::PARAM_INT);
        $delete->execute();
    }

    public function mostrarProducto(InfoProducto $producto) {
        echo '<div class="menu-item" data-product-id="' . $producto->getIdProducto() . '" data-categoria-id="' . $producto->getIdCategoria() . '">';
        echo '<img src="../pantallas/procedimientos/mostrarImagen.php?idProducto=' . $producto->getIdProducto() . '" alt="' . htmlspecialchars($producto->getNombre()) . '">';
        echo '<div class="item-details">';
        echo '<h3>' . htmlspecialchars($producto->getNombre()) . '</h3>';
        echo '<p>' . htmlspecialchars($producto->getDescripcion()) . '</p>';
        echo '<p class="item-price">$' . number_format($producto->getPrecio(), 2) . ' MXN</p>';
        echo '</div>';
        echo '<form action="procedimientos/adminCarrito.php" method="post" class="add-to-cart-form">';
        echo '<input type="hidden" name="accionCarrito" value="añadir">';
        echo '<input type="hidden" name="idProducto" value="' . $producto->getIdProducto() . '">';
        echo '<button type="submit" class="add-buttons btn">+</button>';
        echo '</form>';
        echo '</div>';
    }

    public function mostrarProductoModificar(InfoProducto $producto) {
        echo '<div class="menu-item" data-product-id="' . $producto->getIdProducto() . '">';
        echo '<img src="../pantallas/procedimientos/mostrarImagen.php?idProducto=' . $producto->getIdProducto() . '" alt="' . htmlspecialchars($producto->getNombre()) . '">';
        echo '<div class="item-details">';
        echo '<h3>' . htmlspecialchars($producto->getNombre()) . '</h3>';
        echo '<p>' . htmlspecialchars($producto->getDescripcion()) . '</p>';
        echo '<p class="item-price">$' . number_format($producto->getPrecio(), 2) . ' MXN</p>';
        echo '</div>';
        echo '<div class="admin-buttons">';
        echo '<a href="modificarProducto.php?idProducto=' . $producto->getIdProducto() . '" class="btn-icon btn-edit-icon">';
        echo '<img src="../img/general/lapiz.png" alt="Modificar" title="Modificar Producto" class="icon-button-img">';
        echo '</a>';
        echo '<a href="eliminarProducto.php?idProducto=' . $producto->getIdProducto() . '" class="btn-icon btn-delete-icon">';
        echo '<img src="../img/general/basurero.png" alt="Eliminar" title="Eliminar Producto" class="icon-button-img">';
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }


    public function buscarProductos($textoBusqueda, $tipoBusqueda = 'inicio') {
        $db = Db::conectar();
        $resultados = [];
        $textoBusquedaUpper = strtoupper($textoBusqueda);
        $buscandoProducto = 'SELECT * FROM producto WHERE ';
        $buscandoProducto .= 'UPPER(nombre) LIKE :textoBusqueda OR UPPER(descripcion) LIKE :textoBusqueda';
        $param = '%' . $textoBusquedaUpper . '%';
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