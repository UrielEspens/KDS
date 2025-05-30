<?php
// obtener_detalles_pedido.php

// Simulación de datos de pedidos (para cuando no hay base de datos)
if (isset($_GET['id'])) {
    $pedido_id = $_GET['id'];

    // Simula los detalles para el pedido con ID 1 (estado rojo/pendiente)
    if ($pedido_id == 1) {
        $detalles_simulados = [
            'id' => 1,
            'cliente' => 'Cliente de prueba 1',
            'mesero' => 'Mesero Prueba',
            'hora' => '19:05',
            'articulos' => [
                ['cantidad' => 2, 'nombre' => 'Hamburguesa', 'precio' => '85.00'],
                ['cantidad' => 1, 'nombre' => 'Papas Fritas', 'precio' => '45.00'],
                ['cantidad' => 2, 'nombre' => 'Refresco', 'precio' => '30.00'],
                ['cantidad' => 2, 'nombre' => 'Hamburguesa', 'precio' => '85.00'],
                ['cantidad' => 1, 'nombre' => 'Papas Fritas', 'precio' => '45.00'],
                ['cantidad' => 2, 'nombre' => 'Refresco', 'precio' => '30.00'],
                ['cantidad' => 2, 'nombre' => 'Hamburguesa', 'precio' => '85.00'],
                ['cantidad' => 1, 'nombre' => 'Papas Fritas', 'precio' => '45.00'],
                ['cantidad' => 2, 'nombre' => 'Refresco', 'precio' => '30.00'],
                ['cantidad' => 2, 'nombre' => 'Hamburguesa', 'precio' => '85.00'],
                ['cantidad' => 1, 'nombre' => 'Papas Fritas', 'precio' => '45.00'],
                ['cantidad' => 2, 'nombre' => 'Refresco', 'precio' => '30.00'],
                ['cantidad' => 2, 'nombre' => 'Hamburguesa', 'precio' => '85.00'],
                ['cantidad' => 1, 'nombre' => 'Papas Fritas', 'precio' => '45.00'],
                ['cantidad' => 2, 'nombre' => 'Refresco', 'precio' => '30.00'],
                ['cantidad' => 2, 'nombre' => 'Hamburguesa', 'precio' => '85.00'],
                ['cantidad' => 1, 'nombre' => 'Papas Fritas', 'precio' => '45.00'],
                ['cantidad' => 2, 'nombre' => 'Refresco', 'precio' => '30.00']
            ],
            'total' => '275.00',
            'estado' => 'pendiente' // Añadimos el estado 'red'
        ];
        echo json_encode($detalles_simulados);
        exit(); // Importante detener la ejecución después de enviar la respuesta simulada
    } elseif ($pedido_id == 2) {
        // Simula los detalles para el pedido con ID 2 (estado amarillo/en preparación)
        $detalles_simulados = [
            'id' => 2,
            'cliente' => 'Otro Cliente',
            'mesero' => 'Mesero 2',
            'hora' => '19:15',
            'articulos' => [
                ['cantidad' => 1, 'nombre' => 'Pizza Grande', 'precio' => '180.00'],
                ['cantidad' => 3, 'nombre' => 'Agua', 'precio' => '20.00']
            ],
            'total' => '240.00',
            'estado' => 'preparacion' // Añadimos el estado 'yellow'
        ];
        echo json_encode($detalles_simulados);
        exit();
    } elseif ($pedido_id == 3) {
        // Simula los detalles para un nuevo pedido con ID 3 (estado verde/listo)
        $detalles_simulados = [
            'id' => 3,
            'cliente' => 'Tercer Cliente',
            'mesero' => 'Mesero 1',
            'hora' => '19:20',
            'articulos' => [
                ['cantidad' => 1, 'nombre' => 'Ensalada', 'precio' => '70.00'],
                ['cantidad' => 1, 'nombre' => 'Jugo de Naranja', 'precio' => '35.00']
            ],
            'total' => '105.00',
            'estado' => 'listo' // Añadimos el estado 'green'
        ];
        echo json_encode($detalles_simulados);
        exit();
    }
}

/*
// obtener_detalles_pedido.php

// Configuración de la base de datos (reemplaza con tus credenciales)
$servername = "localhost";
$username = "tu_usuario_db";
$password = "tu_contraseña_db";
$dbname = "tu_nombre_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del pedido desde la petición GET
$pedido_id = $_GET['id'];

// Consulta SQL para obtener los detalles del pedido (ajusta esto a tu estructura de base de datos)
$sql = "SELECT
            o.id AS pedido_id,
            o.cliente,
            o.mesero,
            TIME_FORMAT(o.fecha_hora, '%H:%i') AS hora,
            GROUP_CONCAT(oi.cantidad, ' ', p.nombre, ' - ', oi.precio_unitario SEPARATOR '<br>') AS articulos_concat,
            o.total
        FROM
            ordenes o
        JOIN
            orden_items oi ON o.id = oi.orden_id
        JOIN
            productos p ON oi.producto_id = p.id
        WHERE
            o.id = ?
        GROUP BY
            o.id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Formatear los artículos como un array para facilitar su manejo en JavaScript
    $articulos_array = [];
    $articulos = explode('<br>', $row['articulos_concat']);
    foreach ($articulos as $articulo) {
        $partes = explode(' ', $articulo);
        if (count($partes) >= 3) {
            $cantidad = $partes[0];
            $nombre_partes = array_slice($partes, 1, -2);
            $nombre = implode(' ', $nombre_partes);
            $precio = end($partes);
            $articulos_array[] = ['cantidad' => $cantidad, 'nombre' => $nombre, 'precio' => $precio];
        }
    }
    $response = [
        'id' => $row['pedido_id'],
        'cliente' => $row['cliente'],
        'mesero' => $row['mesero'],
        'hora' => $row['hora'],
        'articulos' => $articulos_array,
        'total' => $row['total']
    ];
    echo json_encode($response);
} else {
    echo json_encode(null); // Devolver null si no se encuentra el pedido
}

$stmt->close();
$conn->close();
*/
?>