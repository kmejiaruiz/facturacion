function actualizarHoraFecha() {
  const ahora = new Date();
  const hora = ahora.getHours().toString().padStart(2, "0");
  const minutos = ahora.getMinutes().toString().padStart(2, "0");
  const segundos = ahora.getSeconds().toString().padStart(2, "0");
  const fecha = ahora.toISOString().split("T")[0];

  // Actualizar los elementos HTML usando template literals
  document.getElementById(
    "hora-actual"
  ).innerText = `Hora: ${hora}:${minutos}:${segundos}`;
  document.getElementById("fecha-actual").innerText = `Fecha: ${fecha}`;
}

// Llamar a la función para actualizar la hora y la fecha cada segundo
setInterval(actualizarHoraFecha, 1000);

// Llamar a la función una vez al cargar la página para mostrar la hora inicial
actualizarHoraFecha();
