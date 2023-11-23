let productos = [];

function agregarProducto() {
  const cliente = document.getElementById("cliente").value;
  const producto = document.getElementById("producto").value;
  const cantidad = document.getElementById("cantidad").value;
  const precio = document.getElementById("precio").value;

  if (
    cliente.trim() === "" ||
    producto.trim() === "" ||
    cantidad.trim() === "" ||
    precio.trim() === ""
  ) {
    alert("Todos los campos deben estar llenos.");
    return;
  }

  const nuevoProducto = {
    cliente: cliente,
    nombre: producto,
    cantidad: cantidad,
    precio: precio,
  };

  productos.push(nuevoProducto);
  mostrarProductos();

  // Limpiar campos del formulario
  document.getElementById("producto").value = "";
  document.getElementById("cantidad").value = "";
  document.getElementById("precio").value = "";
}

function mostrarProductos() {
  const listaProductos = document.getElementById("lista-productos");
  listaProductos.innerHTML = "";

  productos.forEach((producto) => {
    const item = document.createElement("div");
    item.innerHTML = `<p>${producto.cliente} - Producto: ${producto.nombre} - Cantidad: ${producto.cantidad} - Precio: ${producto.precio}</p>`;
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
      alert(
        "No se puede generar la factura debido a campos vacíos o inexistentes."
      );
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
