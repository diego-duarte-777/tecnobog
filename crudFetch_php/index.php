<?php
include("conectar.php");


$sql = "SELECT * FROM productos ORDER BY id DESC";
$result = pg_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tienda de Computadores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #70a9e2ff;
    }
    .card {
      transition: transform 0.2s ease-in-out;
    }
    .card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>
<body>

 
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="index.php">🖥️ Tecnobog</a>
      <div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
          ➕ Agregar Producto
        </button>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <h2 class="text-center mb-4">📦 Catálogo de Productos</h2>

    <div class="row g-4">
      <?php while ($row = pg_fetch_assoc($result)): ?>
        <div class="col-md-4">
          <div class="card shadow-sm">
            <img src="<?= htmlspecialchars($row['imagen'] ?? 'https://via.placeholder.com/400x200?text=Producto') ?>" class="card-img-top" alt="Imagen Producto">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
              <p class="card-text text-muted"><?= htmlspecialchars($row['descripcion']) ?></p>
              <p class="fw-bold text-primary">$<?= number_format($row['precio'], 2) ?></p>
              <p class="text-secondary">Stock: <?= $row['cantidad'] ?></p>
              <div class="d-flex justify-content-between">
                <button class="btn btn-warning btn-sm" onclick="editarProducto(<?= $row['id'] ?>)">✏️ Editar</button>
                <button class="btn btn-danger btn-sm" onclick="eliminarProducto(<?= $row['id'] ?>)">🗑️ Eliminar</button>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

 
  <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="modalAgregarLabel">➕ Agregar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formAgregar" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre del producto</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
              <label for="descripcion" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label for="precio" class="form-label">Precio</label>
              <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
            </div>
            <div class="mb-3">
              <label for="cantidad" class="form-label">Cantidad</label>
              <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
            </div>
            <div class="mb-3">
              <label for="imagen" class="form-label">Imagen del producto</label>
              <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
            </div>
            <button type="submit" class="btn btn-success w-100">Guardar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    
    document.getElementById("formAgregar").addEventListener("submit", async (e) => {
      e.preventDefault();

      let formData = new FormData(e.target);

      let response = await fetch("api_agregar.php", {
        method: "POST",
        body: formData
      });

      let result = await response.json();
      if (result.success) {
        alert("Producto agregado con éxito ✅");
        location.reload(); 
      } else {
        alert("Error: " + result.error);
      }
    });

   
    async function editarProducto(id) {
      let nuevoNombre = prompt("Nuevo nombre del producto:");
      let nuevaDescripcion = prompt("Nueva descripción:");
      let nuevoPrecio = prompt("Nuevo precio:");
      let nuevaCantidad = prompt("Nueva cantidad:");

      let formData = new FormData();
      formData.append("id", id);
      formData.append("nombre", nuevoNombre);
      formData.append("descripcion", nuevaDescripcion);
      formData.append("precio", nuevoPrecio);
      formData.append("cantidad", nuevaCantidad);

      let response = await fetch("api_editar.php", {
        method: "POST",
        body: formData
      });

      let result = await response.json();
      if (result.success) {
        alert("Producto actualizado ✅");
        location.reload();
      } else {
        alert("Error: " + result.error);
      }
    }

    
    async function eliminarProducto(id) {
      if (!confirm("¿Seguro que quieres eliminar este producto?")) return;

      let response = await fetch("api_eliminar.php?id=" + id, {
        method: "DELETE"
      });

      let result = await response.json();
      if (result.success) {
        alert("Producto eliminado ✅");
        location.reload();
      } else {
        alert("Error: " + result.error);
      }
    }
  </script>
</body>
</html>
