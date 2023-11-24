<?php
// Conexión a la base de datos (ajusta los parámetros según tu configuración)
$conexion = new mysqli("localhost", "root", "", "facturacion");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    //     $facturaId = $_POST['id'];
    //     $nuevoEstado = $_POST['estado'];

    //     // Realiza la actualización del estado en la base de datos
    //     $sql = "UPDATE facturas SET estado = ? WHERE id = ?";
    //     $stmt = $conexion->prepare($sql);
    //     $stmt->bind_param("si", $nuevoEstado, $facturaId);

    //     if ($stmt->execute()) {
    //         echo "Estado actualizado correctamente";
    //     } else {
    //         echo "Error al actualizar el estado: " . $conexion->error;
    //     }

    // Recupera los datos del formulario
    $facturaId = $_POST['id'];

    // Verifica si la factura ya ha sido reembolsada
    $sqlFacturaReembolsada = "SELECT estado FROM facturas WHERE id = ?";
    $stmtFacturaReembolsada = $conexion->prepare($sqlFacturaReembolsada);
    $stmtFacturaReembolsada->bind_param("i", $facturaId);
    $stmtFacturaReembolsada->execute();
    $stmtFacturaReembolsada->bind_result($reembolsado);
    $stmtFacturaReembolsada->fetch();
    $stmtFacturaReembolsada->close();

    if ($reembolsado == "Reembolsado") {
        $response = array(
            'success' => false,
            'message' => "No se puede reembolsar dos veces. Ya se ha hecho un reembolso."
        );
    } else {
        // Actualiza la factura original como reembolsada y cambia el estado a 'Reembolsado'
        $sqlActualizarFactura = "UPDATE facturas SET estado = 'Reembolsado' WHERE id = ?";
        $stmtActualizarFactura = $conexion->prepare($sqlActualizarFactura);
        $stmtActualizarFactura->bind_param("i", $facturaId);

        if ($stmtActualizarFactura->execute()) {
            // Obtén los detalles de la factura original
            $sqlDetallesFactura = "SELECT cliente, producto, cantidad, precio FROM facturas WHERE id = ?";
            $stmtDetallesFactura = $conexion->prepare($sqlDetallesFactura);
            $stmtDetallesFactura->bind_param("i", $facturaId);
            $stmtDetallesFactura->execute();
            $stmtDetallesFactura->bind_result($cliente, $producto, $cantidad, $precio);
            $stmtDetallesFactura->fetch();
            $stmtDetallesFactura->close();

            // Crea una nueva factura con valores opuestos para el reembolso
            $nuevaCantidad = -$cantidad;
            $nuevoPrecio = -$precio;

            $sqlNuevaFacturaReembolso = "UPDATE facturas SET cantidad = ?, precio = ? WHERE id = ?";
            $stmtNuevaFacturaReembolso = $conexion->prepare($sqlNuevaFacturaReembolso);
            $stmtNuevaFacturaReembolso->bind_param("ddi", $nuevaCantidad, $nuevoPrecio, $facturaId);

            if ($stmtNuevaFacturaReembolso->execute()) {
                $response = array(
                    'success' => true,
                    'message' => "Reembolso realizado correctamente."
                );
            } else {
                $response = array(
                    'success' => false,
                    'message' => "Error al crear la nueva factura de reembolso: " . $stmtNuevaFacturaReembolso->error
                );
            }

            // Cierra la declaración preparada de la nueva factura
            $stmtNuevaFacturaReembolso->close();
        } else {
            $response = array(
                'success' => false,
                'message' => "Error al actualizar la factura original como reembolsada: " . $stmtActualizarFactura->error
            );
        }

        // Cierra la declaración preparada de la factura original
        $stmtActualizarFactura->close();
    }

    // Cierra la conexión a la base de datos
    $conexion->close();

    // Envia la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
