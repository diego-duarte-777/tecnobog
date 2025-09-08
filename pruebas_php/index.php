<?php
include("conectar.php");
include("header.php");

$sql = "SELECT * FROM productos ORDER BY id DESC";
$result = pg_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos - Tecnobog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2 class="text-center mb-4">📦 Catálogo de Productos</h2>
  <div class="row g-4">
    <?php while ($row = pg_fetch_assoc($result)): ?>
      <div class="col-md-4">
        <div class="card shadow-sm">
          <img src="<?= htmlspecialchars($row['imagen']) ?>" class="card-img-top" alt="Imagen Producto">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['nombre']) ?></h5>
            <p class="card-text text-muted"><?= htmlspecialchars($row['descripcion']) ?></p>
            <p class="fw-bold text-primary">$<?= number_format($row['precio'], 2) ?></p>
            <p class="text-secondary">Stock: <?= $row['cantidad'] ?></p>
            <?php if ($row['cantidad'] > 0): ?>
              <button class="btn btn-success w-100" onclick="comprar(<?= $row['id'] ?>)">🛒 Comprar</button>
            <?php else: ?>
              <button class="btn btn-secondary w-100" disabled>Sin stock</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script>
async function comprar(producto_id) {
  if (!confirm("¿Seguro que quieres comprar este producto?")) return;

  let data = new FormData();
  data.append("producto_id", producto_id);

  let res = await fetch("api_comprar.php", { method: "POST", body: data });
  let r = await res.json();

  if (r.success) {
    alert("✅ Compra realizada");
    location.reload();
  } else {
    alert("❌ " + r.error);
  }
}
</script>
</body>
</html>
