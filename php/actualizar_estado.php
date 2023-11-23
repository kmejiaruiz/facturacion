<?php
// Conexión a la base de datos (ajusta los parámetros según tu configuración)
$conexion = new mysqli("localhost", "root", "", "facturacion");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['estado'])) {
    $facturaId = $_POST['id'];
    $nuevoEstado = $_POST['estado'];

    // Realiza la actualización del estado en la base de datos
    $sql = "UPDATE facturas SET estado = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nuevoEstado, $facturaId);

    if ($stmt->execute()) {
        echo "Estado actualizado correctamente";
    } else {
        echo "Error al actualizar el estado: " . $conexion->error;
    }
}

// Cierra la conexión a la base de datos
$conexion->close();
