<html>
    <head>

    </head>
    <body>
        <div class="carrito-items-container">
            <?php
                $totalCarrito = 0;
                foreach ($_SESSION['carrito'] as $item):
                    $precio = $item['precio'];
                    $cantidad = $item['cantidad'];

                    $subtotalProducto = $precio * $cantidad;
                    $totalCarrito += $subtotalProducto;
            ?>
                
                    <div class="carrito-item">
                        <div class="item-contenedor-izq">
                            <div class="item-info">
                                <span class="item-cantidad">&nbsp;<?php echo $cantidad; ?>&nbsp;</span>
                                <span class="item-nombre"><?php echo $item['nombre'] ?? 'Producto Desconocido'; ?></span>
                            </div>
                            <span class="item-precio-unitario">$<?php echo number_format($precio, 2); ?> </span><br>
                        </div>

                        <div class="item-contenedor-der">
                            <span class="item-subtotal">Subtotal: $<?php echo number_format($subtotalProducto, 2); ?></span>

                            <div class="item-botones">
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="restar">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn">-</button>
                                </form>
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="sumar">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn">+</button>
                                </form>
                                <form action="procedimientos/adminCarrito.php" method="post" style="display: inline-block;">
                                    <input type="hidden" name="accionCarrito" value="borrar">
                                    <input type="hidden" name="idProducto" value="<?php echo $item['id']; ?>">
                                    <button class="btn" type="submit" style="background-color: red; color: white;">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
    </body>

</html>