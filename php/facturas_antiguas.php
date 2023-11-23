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
precio AS precio_individual,
(cantidad * precio) AS precio_final
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
    <title>Facturas Antiguas</title>
</head>

<body>

    <!-- <h1>Facturas Antiguas</h1> -->

    <?php
    // Verifica si hay facturas almacenadas
    if ($resultado && $resultado->num_rows > 0) {
        echo "<div class='contenedor-alerta'><div class='informacion-alerta'>Facturas Antiguas&excl;</div></div>";
        echo "<table>";
        echo '<tr>
        <th>ID Factura</th>
        <th>Cliente</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Precio Final</th>
        <th>Acciones</th>
    </tr>';

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$fila['factura_id']}</td>";
            echo "<td>{$fila['cliente']}</td>";
            echo "<td>{$fila['producto']}</td>";
            echo "<td>{$fila['cantidad']}</td>";
            echo "<td>{$fila['precio_individual']}</td>";
            echo "<td>{$fila['precio_final']}</td>";
    ?>
            <td>
                <a href="#" data-micromodal-trigger="modalReembolso" data-id="<?php echo $fila['factura_id']; ?>">Reembolso</a>
            </td>
    <?php
            echo "</tr>";
        }

        echo "</table>";
        echo "<button onclick='imprimirFactura()'>Imprimir Factura</button>";
    } else {
        echo "<div class='contenedor-alerta'><div class='alerta-error'>No hay facturas guardadas con anterioridad.</div></div>";
    }

    // Cierra la conexión
    $conexion->close();
    ?>
    <button onclick="regresar()">Regresar a la Página Inicial</button>

    <script>
        function imprimirFactura() {
            window.print();
        }

        function regresar() {
            window.location.href = "./";
        }
    </script>


    <!-- modal -->
    <div id="modalReembolso" class="modal micromodal-slide" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1" data-micromodal-close>
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modalReembolsoTitle">
                <!-- Contenido del modal -->
                <div class="modal__content">
                    <h2 id="modalReembolsoTitle">Confirmar Reembolso</h2>
                    <p>¿Estás seguro de que deseas realizar el reembolso?</p>
                    <p>Puedes modificar el estado de la factura antes de confirmar.</p>
                    <select id="estadoReembolso">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Reembolsado">Reembolsado</option>
                        <!-- Agrega más opciones según tus necesidades -->
                    </select>

                    <!-- Pie del modal -->
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
            var nuevoEstado = document.getElementById('estadoReembolso').value;

            // Realizar una solicitud AJAX para actualizar el estado en el servidor
            $.ajax({
                type: 'POST',
                url: './actualizar_estado.php', // Nombre del archivo PHP que manejará la actualización
                data: {
                    id: facturaId,
                    estado: nuevoEstado
                },
                success: function(response) {
                    alert(response + ", operacion exitosa"); // Puedes manejar la respuesta del servidor aquí
                    // Cierra el modal
                    MicroModal.close('modalReembolso');
                },
                error: function(error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        });
    </script>

</body>

</html>