let productos = [];

function agregarProducto() {
  // Obtener los valores de los campos del formulario
  const cliente = document.getElementById("cliente").value.trim();
  const producto = document.getElementById("producto").value.trim();
  const cantidad = document.getElementById("cantidad").value.trim();
  const precio = document.getElementById("precio").value.trim();

  // Verificar si alguno de los campos está vacío
  if (cliente === "" || producto === "" || cantidad === "" || precio === "") {
    Toastify({
      text: "No pueden haber campos vacios.",
      duration: 3500,
      gravity: "top",
      position: "center",
      style: {
        background: "red",
      },
      close: true,
      closeColor: "#000",
      closePosition: "top left",
    }).showToast();
    return;
  }

  // Crear un nuevo objeto producto
  const nuevoProducto = {
    cliente: cliente,
    nombre: producto,
    cantidad: cantidad,
    precio: precio,
  };

  // Agregar el nuevo producto a la lista
  productos.push(nuevoProducto);

  // Mostrar la lista de productos
  mostrarProductos();

  // Limpiar campos del formulario
  document.getElementById("producto").value = "";
  document.getElementById("cantidad").value = "";
  document.getElementById("precio").value = "";
}

function mostrarProductos() {
  const listaProductos = document.getElementById("lista-productos");
  listaProductos.innerHTML = "";

  // Recorrer la lista de productos y mostrarlos en el HTML
  productos.forEach((producto) => {
    const item = document.createElement("li");
    item.className = "list-group-item";
    item.innerHTML = `${producto.cliente} - Producto: ${producto.nombre} - Cantidad: ${producto.cantidad} - Precio: ${producto.precio}`;
    listaProductos.appendChild(item);
  });

  // Habilitar o deshabilitar el botón de generar factura
  const generarFacturaBtn = document.getElementById("generarFacturaBtn");
  generarFacturaBtn.disabled = productos.length === 0;
}

document
  .getElementById("formulario")
  .addEventListener("submit", function (event) {
    // Verificar si hay productos antes de enviar el formulario
    if (productos.length === 0) {
      Toastify({
        text: "No se pudo generar la factura, revise la informacion insertada.",
        duration: 3500,
        gravity: "top",
        position: "center",
        style: {
          background: "red",
        },
        close: true,
      }).showToast();
      event.preventDefault(); // Evita que el formulario se envíe
    } else {
      // Agregar la lista de productos al formulario antes de enviarlo
      const inputProductos = document.createElement("input");
      inputProductos.type = "hidden";
      inputProductos.name = "productos";
      inputProductos.value = JSON.stringify(productos);
      this.appendChild(inputProductos);
    }
  });
