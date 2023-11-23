function actualizarHoraFecha() {
  var ahora = new Date();
  var hora = ahora.getHours();
  var minutos = ahora.getMinutes();
  var segundos = ahora.getSeconds();
  var fecha = ahora.toISOString().split("T")[0];

  // Agregar ceros a la izquierda si es necesario
  hora = hora < 10 ? "0" + hora : hora;
  minutos = minutos < 10 ? "0" + minutos : minutos;
  segundos = segundos < 10 ? "0" + segundos : segundos;

  // Actualizar los elementos HTML
  document.getElementById("hora-actual").innerText =
    "Hora: " + hora + ":" + minutos + ":" + segundos;
  document.getElementById("fecha-actual").innerText = "Fecha: " + fecha;
}

// Llamar a la función para actualizar la hora y la fecha cada segundo
setInterval(actualizarHoraFecha, 1000);

// Llamar a la función una vez al cargar la página para mostrar la hora inicial
actualizarHoraFecha();
