<?php
// Conecta a la base de datos
$conexion = new mysqli("localhost", "root", "", "facturacion");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta SQL para obtener facturas antiguas y su valor total agrupado por cliente, producto y precio
$sql = "SELECT
id AS factura_id,
cliente,
producto,
cantidad,
estado,
precio AS precio_individual,
(cantidad * (precio)) AS precio_final
FROM
facturas";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/micromodal.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Facturas Antiguas</title>
</head>

<body>

    <?php
    if ($resultado && $resultado->num_rows > 0) {
        echo "<table>";
        echo '<tr>
        <th>ID Factura</th>
        <th>Cliente</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Precio Final</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>';

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$fila['factura_id']}</td>";
            echo "<td>{$fila['cliente']}</td>";
            echo "<td>{$fila['producto']}</td>";
            echo "<td>{$fila['cantidad']}</td>";
            echo "<td>C$ {$fila['precio_individual']}</td>";

            $precioFinal = $fila['precio_final'];

            $facturaId = $fila['factura_id'];
            $sqlEstadoFactura = "SELECT estado FROM facturas WHERE id = ?";
            $stmtEstadoFactura = $conexion->prepare($sqlEstadoFactura);
            $stmtEstadoFactura->bind_param("i", $facturaId);
            $stmtEstadoFactura->execute();
            $stmtEstadoFactura->bind_result($reembolsado);
            $stmtEstadoFactura->fetch();
            $stmtEstadoFactura->close();

            if ($reembolsado == "Reembolsado") {
                $precioFinal = number_format(-$precioFinal, 2);
                echo "<td style='color:red;'>C$ $precioFinal</td>";
            } else {
                $precioFinal = number_format($precioFinal, 2);
                echo "<td style='color:green;'>C$ $precioFinal</td>";
            }

            echo "<td style='font-style:italic;'>{$fila['estado']}</td>";
    ?>
            <td style="text-align: center;">
                <a class="checkout" href="#!" data-micromodal-trigger="modalReembolso" data-id="<?php echo $fila['factura_id']; ?>">Reembolsar</a>
            </td>
    <?php
            echo "</tr>";
        }

        echo "</table>";
        echo "<button class='button' onclick='imprimirFactura()'>Imprimir Factura</button>";
    } else {
        echo "
        <script>
        const customAlert = Toastify({
            text:'No hay facturas en sistema, intente nuevamente',
            duration:16000,
            gravity:'top',
            position:'right',
            backgroundColor:'red',
            stopOnFocus:false,
            stopOnHover:false,
            close:true,
        }).showToast();
        </script>";
    }

    $conexion->close();
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

    <div id="modalReembolso" class="modal micromodal-slide" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modalReembolsoTitle">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                        Reembolsar factura?
                    </h2>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <div class="modal__content">
                    <h2 id="modalReembolsoTitle">Confirmar Reembolso</h2>
                    <p>¿Estás seguro de que deseas realizar el reembolso?</p>
                    <p>Esta acción no se puede deshacer.</p>

                    <div class="modal__footer">
                        <button class="modal__btn" data-micromodal-close aria-label="Cancelar">Cancelar</button>
                        <button class="modal__btn modal__btn-primary" id="confirmarReembolso" data-id="">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        MicroModal.init();

        document.querySelectorAll('[data-micromodal-trigger="modalReembolso"]').forEach(function(el) {
            el.addEventListener('click', function() {
                var facturaId = this.getAttribute('data-id');
                document.getElementById('confirmarReembolso').setAttribute('data-id', facturaId);
                MicroModal.show('modalReembolso');
            });
        });

        document.getElementById('confirmarReembolso').addEventListener('click', function() {
            var facturaId = this.getAttribute('data-id');
            $.ajax({
                type: 'POST',
                url: './actualizar_estado.php',
                data: {
                    id: facturaId,
                },
                success: function(response) {
                    MicroModal.close('modalReembolso');
                    setTimeout(function() {
                        if (response.success) {
                            Toastify({
                                text: response.message,
                                duration: 2500,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "green",
                                stopOnFocus: true,
                            }).showToast();
                        } else {
                            Toastify({
                                text: response.message,
                                duration: 2500,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "red",
                                stopOnFocus: false,
                                close: true,
                            }).showToast();
                        }
                        setTimeout(function() {
                            location.reload();
                        }, 2600);
                    }, 500);
                },
                error: function(error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const hasVisited = document.cookie.includes("visited=true");
            if (!hasVisited) {
                Swal.fire({
                    positon: "top-end",
                    icon: 'info',
                    title: 'Acceso Garantizado',
                    text: 'Bienvenido, aqui podra visualizar las facturas generadas anteriormente, asi mismo reembolsar en caso de ser necesario',
                    confirmButtonColor: "#3085d6",
                });
                const expirationDate = new Date();
                expirationDate.setDate(expirationDate.getDate() + 30);
                document.cookie = `visited=true; expires=${expirationDate.toUTCString()}`;
            }
        });
    </script>

</body>

</html>