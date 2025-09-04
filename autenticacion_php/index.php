<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}


$rol = $_SESSION['rol'] ?? 'usuario';

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
    body { background: #74a7dbff; }
    .card { transition: transform 0.2s ease-in-out; }
    .card:hover { transform: translateY(-5px); }
  </style>
</head>
<body>

  
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="index.php">💻 Tecnobog</a>
      <div class="d-flex">
        <?php if ($rol === 'admin'): ?>
          <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalAgregar">
            ➕ Agregar Producto
          </button>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger">🚪 Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <h2 class="text-center mb-4">📦 Catálogo de Productos</h2>

    <div class="row g-4">
      <?php while ($row = pg_fetch_assoc($result)): ?>
        <div class="col-md-4">
          <div class="card shadow-sm">
            <img src="<?= $row['imagen'] ? htmlspecialchars($row['imagen']) : 'placeholder.jpg' ?>" class="card-img-top" alt="Imagen Producto">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
              <p class="card-text text-muted"><?= htmlspecialchars($row['descripcion']) ?></p>
              <p class="fw-bold text-primary">$<?= number_format($row['precio'], 2) ?></p>
              <p class="text-secondary">Stock: <?= $row['cantidad'] ?></p>
              <?php if ($rol === 'admin'): ?>
                <div class="d-flex justify-content-between">
                  <a href="editar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                  <a href="eliminar.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este producto?')">🗑️ Eliminar</a>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  
  <?php if ($rol === 'admin'): ?>
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
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <?php if ($rol === 'admin'): ?>
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
  </script>
  <?php endif; ?>
</body>
</html>
