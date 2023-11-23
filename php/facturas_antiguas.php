<?php
// Conecta a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturacion");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta SQL para obtener facturas antiguas y su valor total agrupado por cliente, producto y precio
$sql = "SELECT cliente, producto, precio, COUNT(producto) AS cantidad, SUM(precio) AS total_producto FROM facturas GROUP BY cliente, producto, precio";
$resultado = $conexion->query($sql);

?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Facturas Antiguas</title>
</head>

<body>

    <!-- <h1>Facturas Antiguas</h1> -->

    <?php
    // Verifica si hay facturas almacenadas
    if ($resultado && $resultado->num_rows > 0) {
        echo "<div class='contenedor-alerta'><div class='informacion-alerta'>Facturas Antiguas&excl;</div></div>";
        echo '<table>';
        echo '<tr><th>Cliente</th><th>Producto</th><th>Precio Unitario</th><th>Cantidad</th><th>Total Producto</th></tr>';

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr><td>{$fila['cliente']}</td><td>{$fila['producto']}</td><td>{$fila['precio']}</td><td>{$fila['cantidad']}</td><td>{$fila['total_producto']}</td></tr>";
        }

        echo '</table>';
    } else {
        echo "<div class='contenedor-alerta'><div class='alerta-error'>No hay facturas guardadas con anterioridad.</div></div>";
    }

    // Cierra la conexión
    $conexion->close();
    ?>
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