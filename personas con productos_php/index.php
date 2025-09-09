<?php
include("conectar.php");


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
  <title>Personas y Asignaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4 text-center">👤📌Gestión de Personas y Asignaciones</h2>

  
  <div class="mb-4 d-flex justify-content-between">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPersona">🧑 Registrar Persona</button>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAsignacion">📌 Asignar Producto</button>
  </div>

  
  <h4>🧑📋 Personas Registradas</h4>
  <table class="table table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Documento</th>
        <th>Correo</th>
        <th>Teléfono</th>
      </tr>
    </thead>
    <tbody>
      <?php while($p = pg_fetch_assoc($personas)): ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['nombre']) ?></td>
          <td><?= htmlspecialchars($p['documento']) ?></td>
          <td><?= htmlspecialchars($p['correo']) ?></td>
          <td><?= htmlspecialchars($p['telefono']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  
  <h4 class="mt-5">📦 Asignaciones</h4>
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


<div class="modal fade" id="modalPersona" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">🧑 Registrar Persona</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formPersona">
          <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre completo" required>
          <input type="text" name="documento" class="form-control mb-2" placeholder="Documento" required>
          <input type="email" name="correo" class="form-control mb-2" placeholder="Correo">
          <input type="text" name="telefono" class="form-control mb-2" placeholder="Teléfono">
          <button type="submit" class="btn btn-primary w-100">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalAsignacion" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">📌 Asignar Producto a Persona</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formAsignacion">
          <select name="persona_id" class="form-select mb-3" required>
            <option value="">Seleccione una persona</option>
            <?php pg_result_seek($personas, 0); while($p = pg_fetch_assoc($personas)): ?>
              <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
          <select name="producto_id" class="form-select mb-3" required>
            <option value="">Seleccione un producto</option>
            <?php while($pr = pg_fetch_assoc($productos)): ?>
              <option value="<?= $pr['id'] ?>"><?= htmlspecialchars($pr['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
          <button type="submit" class="btn btn-success w-100">Asignar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("formPersona").addEventListener("submit", async (e) => {
  e.preventDefault();
  let data = new FormData(e.target);
  let res = await fetch("api_persona.php", { method: "POST", body: data });
  let r = await res.json();
  if (r.success) { alert("✅ Persona registrada"); location.reload(); }
  else { alert("❌ " + r.error); }
});

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
