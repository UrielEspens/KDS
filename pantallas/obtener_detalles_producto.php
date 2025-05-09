<?php
// obtener_detalles_producto.php

if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Simulación de datos de productos (para cuando no hay base de datos)
    if ($producto_id == 1) {
        $detalles_simulados = [
            'id' => 1,
            'nombre' => 'Hamburguesa Clásica',
            'descripcion' => 'Deliciosa hamburguesa con carne de res, lechuga fresca, tomate jugoso, cebolla crujiente y queso cheddar.',
            'precio' => '85.00',
            'imagen' => '../img/productos/hamburguesa.png'
        ];
        echo json_encode($detalles_simulados);
        exit();
    } elseif ($producto_id == 2) {
        $detalles_simulados = [
            'id' => 2,
            'nombre' => 'Papas Fritas Grandes',
            'descripcion' => 'Una generosa porción de crujientes papas fritas, perfectas para acompañar cualquier platillo.',
            'precio' => '45.00',
            'imagen' => '../img/productos/papasFritas.png'
        ];
        echo json_encode($detalles_simulados);
        exit();
    } elseif ($producto_id == 3) {
        $detalles_simulados = [
            'id' => 3,
            'nombre' => 'Coca-Cola 600ml',
            'descripcion' => 'Refrescante bebida gaseosa Coca-Cola en su presentación de 600 mililitros.',
            'precio' => '25.00',
            'imagen' => '../img/productos/cocaCola.png'
        ];
        echo json_encode($detalles_simulados);
        exit();
    } elseif ($producto_id == 4) {
        $detalles_simulados = [
            'id' => 4,
            'nombre' => 'Agua Natural 600ml',
            'descripcion' => 'Botella de agua purificada de 600 mililitros, ideal para hidratarse.',
            'precio' => '15.00',
            'imagen' => '../img/productos/agua.png'
        ];
        echo json_encode($detalles_simulados);
        exit();
    } elseif ($producto_id == 5) {
        $detalles_simulados = [
            'id' => 5,
            'nombre' => 'Pastel de Chocolate',
            'descripcion' => 'Delicioso pastel de chocolate con un toque de sabor y aroma.',
            'precio' => '60.00',
            'imagen' => '../img/productos/pastelChocolate.png'
        ];
        echo json_encode($detalles_simulados);
        exit();
    }
    
}

// Si no se proporciona un ID o no se encuentra, devolvemos null
echo json_encode(null);
?>