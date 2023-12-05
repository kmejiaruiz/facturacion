<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Factura generada</title>
    <style>
        .custom-toast {
            background-color: #4CAF50;
            color: white;
        }

        .progress-container {
            height: 5px;
            width: 100%;
            background-color: #ccc;
            position: fixed;
            bottom: 0;
            left: 0;
            transition: opacity 1.5s;
        }

        .progress-bar {
            height: 100%;
            width: 0;
            background-color: #4CAF50;
            position: absolute;
            border-radius: 8px;
            animation: progressAnimation 2.5s forwards;
        }

        @keyframes progressAnimation {
            100% {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: ../index.php");
        exit();
    }

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $nombreUsuario = $_SESSION['usuario'];
    date_default_timezone_set('America/Managua');
    $fechaHoraFacturacion = date("d-m-Y H:i:s");

    $facturaGenerada = isset($_SESSION['facturaGenerada']) ? $_SESSION['facturaGenerada'] : false;

    if (!$facturaGenerada) {
        echo "
        <script>
        const nuevaAlerta = Toastify({
            text:'No hay facturas en sistema.',
            duration:16000,
            gravity:'top',
            position:'right',
            backgroundColor:'red',
            stopOnHover:false,
            stopOnFocus:false,
            close:true,
        }).showToast();
        </script>
        ";
    } else {
        $cliente = isset($_SESSION['cliente']) ? $_SESSION['cliente'] : '';
        $productos = isset($_SESSION['productos']) ? $_SESSION['productos'] : [];

        if (!empty($productos)) {
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
            echo '<button class="button" onclick="imprimirFactura()">Imprimir Factura</button>';
        } else {
            echo "<div class='contenedor-alerta'><div class='alerta-error'>Error, la factura se encuentra vacía. Intente más tarde.</div></div>";
        }
    }
    ?>

    <button class="button" onclick="regresar()">Regresar a la Página Inicial</button>

    <script>
        function imprimirFactura() {
            window.print();
        }

        function regresar() {
            window.location.href = "./";
        }
    </script>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const facturaId = urlParams.get('factura_id');

        if (facturaId) {
            const progressContainer = document.createElement("div");
            progressContainer.classList.add("progress-container");

            const progressBar = document.createElement("div");
            progressBar.classList.add("progress-bar");
            progressContainer.appendChild(progressBar);

            document.body.appendChild(progressContainer);

            const customToast = Toastify({
                text: `Factura No. ${facturaId} generada correctamente.`,
                duration: 2500,
                gravity: "top",
                position: "right",
                backgroundColor: "green",
                className: "custom-toast",
                stopOnFocus: false,
                stopOnHover: false,
                callback: () => {
                    progressContainer.remove();
                },
                close: true,
            }).showToast();

            let progressValue = 0;
            const updateProgressBar = () => {
                progressValue += 10;
                progressBar.style.width = `${progressValue}%`;

                if (progressValue < 100) {
                    setTimeout(updateProgressBar, 250);
                } else {
                    setTimeout(() => {
                        progressContainer.style.transition = "opacity 1.5s";
                        progressContainer.style.opacity = 0;
                        customToast.hideToast();
                    }, 1000);
                };

                setTimeout(updateProgressBar, 250);
            };

            updateProgressBar();
        }
    </script>
</body>

</html>