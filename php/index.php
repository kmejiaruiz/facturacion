<?php
session_start();

// Verifica si no hay una sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturando pedido.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Toastify -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <!-- Toastify -->
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <header class="header">
        <a href="./facturas_antiguas.php" class="revisar-facturas">Revisar Facturas</a>
        <span class="span">Bienvenido <?php echo $_SESSION['usuario'] ?></span>
    </header>

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        main {
            flex: 1;
            margin-left: 20px;
        }

        .formulario {
            margin: 0;
            text-align: center;
        }

        footer {
            background-color: #f1f1f1;
            padding: 10px;
        }
    </style>

    <main class="d-flex" style="justify-content: space-around;">
        <div>
            <form action="./factura.php" method="post" id="formulario">
                <h1>App de Facturación</h1>

                <label for="cliente">Cliente:</label>
                <input type="text" id="cliente" name="cliente" required>

                <label for="producto">Producto:</label>
                <input type="text" id="producto" name="producto">

                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad">

                <label for="precio">Precio unitario:</label>
                <input type="number" id="precio" name="precio" step="0.01">

                <button class="button" type="button" onclick="agregarProducto()">Agregar Producto</button>
                <button class="button" type="submit" id="generarFacturaBtn">Generar Factura</button>
            </form>
        </div>
        <div style="margin-top: 80px;">
            <ul class="list-group list-group-flush" id="lista-productos"></ul>
        </div>
    </main>

    <!-- Footer con JavaScript para mostrar la hora actualizada y el botón de cerrar sesión -->
    <footer>
        <form class="formulario" method="post">
            <span id="hora-actual"></span>
            <span id="fecha-actual"></span>

            <button class="button" type="button" onclick="confirmarCerrarSesion()">Cerrar Sesión</button>
        </form>
    </footer>

    <script src="../js/script.js"></script>
    <script src="../js/hora.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script src='../js/swal_fire.js'></script>


</body>

</html>