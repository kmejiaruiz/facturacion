<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Agrega esto en el head de tu HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <title>Factura generada</title>
</head>
<style>
    .custom-toast {
        background-color: #4CAF50;
        color: white;
        /* padding: 16px; */
        /* border-radius: 8px; */
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
        animation: progressAnimation 1.5s forwards;
    }

    @keyframes progressAnimation {
        100% {
            width: 100%;
        }
    }
</style>

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
        // echo "<div class='contenedor-alerta'><div class='alerta'>Factura generada con éxito &check;</div></div>";

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

    <button class="button" clas onclick="imprimirFactura()">Imprimir Factura</button>
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
        // Obtén el ID de la factura de la URL
        const urlParams = new URLSearchParams(window.location.search);
        const facturaId = urlParams.get('factura_id');

        // Si hay un ID de factura en la URL, muestra la notificación Toastify con un botón de cierre
        if (facturaId) {

            // Crear el contenedor de la barra de progreso
            const progressContainer = document.createElement("div");
            progressContainer.classList.add("progress-container");

            // Crear la barra de progreso
            const progressBar = document.createElement("div");
            progressBar.classList.add("progress-bar");
            progressContainer.appendChild(progressBar);

            // Agregar el contenedor de la barra de progreso al cuerpo del documento
            document.body.appendChild(progressContainer);

            // Mostrar la notificación
            const customToast = Toastify({
                text: "Mensaje con Barra de Progreso",
                duration: 1500, // Duración en milisegundos (1.5 segundos)
                gravity: "top",
                position: "right",
                backgroundColor: "green",
                className: "custom-toast",
                stopOnFocus: false,
                stopOnHover: false,
                callback: () => {
                    progressContainer.remove(); // Limpiar la barra de progreso al finalizar la notificación
                },
                close: true,
            }).showToast();

            // Actualizar la barra de progreso cada segundo
            let progressValue = 0;
            const updateProgressBar = () => {
                progressValue += 10; // Incrementar el valor de la barra de progreso
                progressBar.style.width = `${progressValue}%`;

                if (progressValue < 100) {
                    setTimeout(updateProgressBar, 150); // Actualizar cada 150 milisegundos
                } else {
                    setTimeout(() => {
                        progressContainer.style.transition = "opacity 1.5s";
                        progressContainer.style.opacity = 0;
                        customToast.hideToast();
                    }, 1000);
                };

                setTimeout(updateProgressBar, 150); // Iniciar la actualización después de 150 milisegundos
            }
        }
    </script>
</body>

</html>