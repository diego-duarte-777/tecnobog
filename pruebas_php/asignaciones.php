<?php
include("conectar.php");
include("header.php");

$personas = pg_query($conn, "SELECT * FROM personas ORDER BY id DESC");
$productos = pg_query($conn, "SELECT * FROM productos ORDER BY id DESC");

$sql = "SELECT a.id, p.nombre AS persona, pr.nombre AS producto, a.fecha_asignacion
        FROM asignaciones a
        JOIN personas p ON p.id = a.persona_id
        JOIN productos pr ON pr.id = a.producto_id
        ORDER BY a.id DESC";
$asignaciones = pg_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignaciones - Tecnobog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2 class="mb-4 text-center">📌 Asignaciones</h2>
  <form id="formAsignacion" class="mb-4">
    <select name="persona_id" class="form-select mb-2" required>
      <option value="">Seleccione persona</option>
      <?php while($p = pg_fetch_assoc($personas)): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
      <?php endwhile; ?>
    </select>
    <select name="producto_id" class="form-select mb-2" required>
      <option value="">Seleccione producto</option>
      <?php while($pr = pg_fetch_assoc($productos)): ?>
        <option value="<?= $pr['id'] ?>"><?= htmlspecialchars($pr['nombre']) ?></option>
      <?php endwhile; ?>
    </select>
    <button type="submit" class="btn btn-success w-100">Asignar</button>
  </form>

  <table class="table table-bordered">
    <thead class="table-secondary">
      <tr>
        <th>ID</th>
        <th>Persona</th>
        <th>Producto</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      <?php while($a = pg_fetch_assoc($asignaciones)): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><?= htmlspecialchars($a['persona']) ?></td>
          <td><?= htmlspecialchars($a['producto']) ?></td>
          <td><?= $a['fecha_asignacion'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
document.getElementById("formAsignacion").addEventListener("submit", async (e) => {
  e.preventDefault();
  let data = new FormData(e.target);
  let res = await fetch("api_asignacion.php", { method: "POST", body: data });
  let r = await res.json();
  if (r.success) { alert("📌 Producto asignado"); location.reload(); }
  else { alert("❌ " + r.error); }
});
</script>
</body>
</html>
