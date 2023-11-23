<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar factura</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>

    <?php
    // Verificar si la solicitud proviene de index.php
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $paginaAutorizada = 'http://localhost/facturacion/index.php';  // Reemplaza con la URL correcta de tu aplicación

    if (strpos($referer, $paginaAutorizada) === false) {
        // Redirigir al usuario a index.php si la solicitud no proviene de la página autorizada
        header("Location: ../index.php");
        exit();
    }




    session_start();

    // Verificar si ya se ha generado la factura
    $facturaGenerada = isset($_SESSION['facturaGenerada']) ? $_SESSION['facturaGenerada'] : false;



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Procesar la información del formulario
        $cliente = $_POST["cliente"];
        $productos = json_decode($_POST["productos"], true);

        // Guardar información en la sesión
        $_SESSION['cliente'] = $cliente;
        $_SESSION['productos'] = $productos;

        // Conexión a la base de datos (ajusta los parámetros según tu configuración)
        $conexion = new mysqli("localhost", "root", "", "facturacion");

        // Verifica la conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Prepara la consulta SQL
        $stmt = $conexion->prepare("INSERT INTO facturas (cliente, producto, cantidad, precio) VALUES (?, ?, ?, ?)");

        // Enlaza los parámetros
        $stmt->bind_param("ssdd", $cliente, $productoNombre, $cantidad, $precio);

        foreach ($productos as $producto) {
            $productoNombre = $producto["nombre"];
            $cantidad = $producto["cantidad"];
            $precio = $producto["precio"];

            // Insertar en la base de datos
            $stmt->execute();
        }

        // Cierra la conexión y el statement
        $stmt->close();
        $conexion->close();

        // Marcar la factura como generada en la sesión
        $_SESSION['facturaGenerada'] = true;

        // Redirigir al usuario a obtener_factura.php
        header("Location: obtener_factura.php");
        exit(); // Asegura que el script se detenga después de redirigir
    }
    ?>

</body>

</html>