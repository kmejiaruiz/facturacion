// Contenido de script.js

function confirmarCerrarSesion() {
  Swal.fire({
    title: "¿Estás seguro?",
    text: "¿Quieres cerrar la sesión?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, cerrar sesión",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // Realizar la solicitud AJAX al servidor para cerrar la sesión
      $.ajax({
        type: "POST",
        url: "../php/logout.php", // Ajusta la ruta según tu estructura de archivos
        data: { cerrar_sesion: true },
        dataType: "json",
        success: function (response) {
          // La solicitud fue exitosa, puedes realizar acciones adicionales si es necesario
          // ...
          // Después, puedes redirigir al usuario
          window.location.href = "../";
        },
        error: function (error) {
          // Ocurrió un error en la solicitud AJAX
          console.error("Error en la solicitud AJAX:", error);
          // Puedes manejar el error según tus necesidades
        },
      });
    }
  });
}
