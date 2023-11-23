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
    session_start();

    // Verifica si no hay una sesión activa
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../index.php");
        exit();
    }

    // Inicia la sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Para obtener el usuario que facturo junto a la hora de facturacion
    // Obtén el nombre de usuario de la sesión
    date_default_timezone_set('America/Managua');
    $nombreUsuario = $_SESSION['usuario'];

    // Obtiene la fecha y hora actual
    $fechaHoraFacturacion = date("d-m-Y H:i:s");
    // FIN

    // Verificar si la factura ha sido generada
    $facturaGenerada = isset($_SESSION['facturaGenerada']) ? $_SESSION['facturaGenerada'] : false;

    // Si la factura no se ha generado, mostrar un mensaje
    if (!$facturaGenerada) {
        echo "<div class='contenedor-alerta'><div class='alerta-error'>No hay facturas disponibles. Intente mas tarde.</div></div>";
    } else {
        // Obtener información almacenada en la sesión
        $cliente = isset($_SESSION['cliente']) ? $_SESSION['cliente'] : '';
        $productos = isset($_SESSION['productos']) ? $_SESSION['productos'] : [];

        // Resto del código para generar la factura
        echo "<div class='contenedor-alerta'><div class='alerta'>Factura generada con éxito &check;</div></div>";

        if (!empty($productos)) {
            // Mostrar la factura
            echo "<div class='contenedor-fecha'>";
            echo "<span style=''>Facturado por: $nombreUsuario</span> <br>";
            echo "<span>Fecha y hora: $fechaHoraFacturacion</span>";
            echo "</div>";
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
            echo "<div class='contenedor-alerta'><div class='alerta-error'>Error, la factura se encuentra vacia. Intente mas tarde</div></div>";
        }
    } ?>

    <button onclick="imprimirFactura()">Imprimir Factura</button>
    <button onclick="regresar()">Regresar a la Página Inicial</button>

    <script>
        function imprimirFactura() {
            window.print();
        }

        function regresar() {
            window.location.href = "./";
        }
    </script>
</body>

</html>