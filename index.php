<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturando pedido.</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>
    <header>
        <!-- <h1>App de Facturación</h1> -->
        <a href="./php/facturas_antiguas.php" class="revisar-facturas">Revisar Facturas</a>
    </header>


    <form action="./php/factura.php" method="post" id="formulario">
        <h1>App de Facturación</h1>

        <label for="cliente">Cliente:</label>
        <input type="text" id="cliente" name="cliente" required>

        <label for="producto">Producto:</label>
        <input type="text" id="producto" name="producto">

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad">

        <label for="precio">Precio unitario:</label>
        <input type="number" id="precio" name="precio" step="0.01">

        <button type="button" onclick="agregarProducto()">Agregar Producto</button>
        <button type="submit" id="generarFacturaBtn">Generar Factura</button>
    </form>


    <div class="" id="lista-productos"></div>

    <script src="./js/script.js"></script>
</body>

</html>