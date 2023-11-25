<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            margin: 0 !important;
        }

        form {
            width: 200px;
            margin: 0 auto;
            margin-top: 50px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php
    // Inicia la sesión
    session_start();

    // Verifica si ya hay una sesión activa y redirige
    if (isset($_SESSION['usuario'])) {
        header("Location: ./php/index.php");
        session_destroy();
        exit();
    }

    // Verifica si se ha enviado el formulario de inicio de sesión
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Conecta a la base de datos (reemplaza 'usuario', 'contraseña' y 'nombre_base_de_datos' con tus propios valores)
        $conexion = new mysqli("localhost", "root", "", "facturacion");

        // Verifica la conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Obtiene las credenciales del formulario
        $usuario = $_POST['usuario'];
        $contraseña = $_POST['contraseña'];

        // Consulta SQL para verificar las credenciales
        $consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' AND contraseña='$contraseña'";
        $resultado = $conexion->query($consulta);

        // Verifica si las credenciales son válidas
        if ($resultado && $resultado->num_rows > 0) {
            // Inicia la sesión y almacena el nombre de usuario en la variable de sesión
            $_SESSION['usuario'] = $usuario;
            header("Location: ./php/index.php"); // Redirige a la página principal
            exit();
        } else {
            echo '
            <script>
            Swal.fire({
                position: "top-center",
                icon: "error",
                title: "Credenciales incorrectas",
                showConfirmButton: false,
                timer: 1500
              });
            </script>';
        }

        // Cierra la conexión
        $conexion->close();
    }
    ?>

    <main style="display: flex; width: 100%; justify-content: center;">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required>

            <button class="button" type="submit">Iniciar Sesión</button>
        </form>
    </main>

</body>

</html>