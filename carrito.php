<?php
session_start();

// Acciones: reducir o eliminar
if (isset($_GET['accion']) && isset($_GET['producto'])) {
    $producto = $_GET['producto'];

    if ($_GET['accion'] === 'reducir') {
        if (isset($_SESSION['carrito'][$producto]['cantidad']) && $_SESSION['carrito'][$producto]['cantidad'] > 1) {
            $_SESSION['carrito'][$producto]['cantidad']--;
        } else {
            unset($_SESSION['carrito'][$producto]);
        }
    }

    if ($_GET['accion'] === 'eliminar') {
        unset($_SESSION['carrito'][$producto]);
    }

    // Redirigir para evitar reenvío de formulario
    header('Location: carrito.php');
    exit;
}

$carrito = $_SESSION['carrito'] ?? [];
?>
<!DOCTYPE html>
<html>
<head><title>Carrito</title></head>
<body>
  <h1>Tu Carrito</h1>
  <a href="index.php">← Volver a la tienda</a>
  <hr>

  <?php if (empty($carrito)): ?>
    <p>Carrito vacío.</p>
  <?php else: ?>
    <table border="1" cellpadding="10">
      <tr>
        <th>Producto</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Subtotal</th>
        <th>Acciones</th>
      </tr>
      <?php $total = 0; ?>
      <?php foreach ($carrito as $nombre => $info): ?>
        <?php
          $cantidad = $info['cantidad'] ?? 0;
          $subtotal = $info['precio'] * $cantidad;
          $total += $subtotal;
        ?>
        <tr>
          <td><?= htmlspecialchars($nombre) ?></td>
          <td>$<?= $info['precio'] ?></td>
          <td><?= $cantidad ?></td>
          <td>$<?= $subtotal ?></td>
          <td>
            <a href="?accion=reducir&producto=<?= urlencode($nombre) ?>">➖</a>
            <a href="?accion=eliminar&producto=<?= urlencode($nombre) ?>">🗑️</a>
          </td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3"><strong>Total:</strong></td>
        <td colspan="2"><strong>$<?= $total ?></strong></td>
      </tr>
    </table>
  <?php endif; ?>
</body>
</html>
