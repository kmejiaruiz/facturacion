<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Factura generada</title>
</head>

<body>
    <?php
    // Inicia la sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar si la factura ha sido generada
    $facturaGenerada = isset($_SESSION['facturaGenerada']) ? $_SESSION['facturaGenerada'] : false;

    // Si la factura no se ha generado, mostrar un mensaje
    if (!$facturaGenerada) {
        echo '<p>No hay factura disponible.</p>';
    } else {
        // Obtener información almacenada en la sesión
        $cliente = isset($_SESSION['cliente']) ? $_SESSION['cliente'] : '';
        $productos = isset($_SESSION['productos']) ? $_SESSION['productos'] : [];

        // Resto del código para generar la factura
        echo "<div class='contenedor-alerta'><div class='alerta'>Factura Generada &check;</div></div>";

        if (!empty($productos)) {
            // Mostrar la factura
            echo "<p><strong>Cliente:</strong> $cliente</p>";
            echo "<table>";
            echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Total</th></tr>";

            $totalFactura = 0;

            foreach ($productos as $producto) {
                $totalProducto = $producto["cantidad"] * $producto["precio"];
                $totalFactura += $totalProducto;

                echo "<tr>";
                echo "<td>{$producto['nombre']}</td>";
                echo "<td>{$producto['cantidad']}</td>";
                echo "<td>{$producto['precio']}</td>";
                echo "<td>$totalProducto</td>";
                echo "</tr>";
            }

            echo "<tr><td colspan='3'><strong>Total Factura</strong></td><td><strong>$totalFactura</strong></td></tr>";
            echo "</table>";
        } else {
            echo '<p>No hay productos en la factura.</p>';
        }
    } ?>

    <button onclick="imprimirFactura()">Imprimir Factura</button>
    <button onclick="regresar()">Regresar a la Página Inicial</button>

    <script>
        function imprimirFactura() {
            window.print();
        }

        function regresar() {
            window.location.href = "../index.php";
        }
    </script>
</body>

</html>