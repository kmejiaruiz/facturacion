<?php
// Conecta a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturacion");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $facturaId = $_GET['id'];

    // Realiza las acciones de reembolso (actualiza el estado, genera registros de reembolso, etc.)
    $sqlReembolso = "UPDATE facturas SET estado = 'Reembolsado' WHERE id = $facturaId";
    if ($conexion->query($sqlReembolso) === TRUE) {
        echo "Reembolso exitoso";
    } else {
        echo "Error en el reembolso: " . $conexion->error;
    }
}

// Cierra la conexión a la base de datos al final del archivo
$conexion->close();
