<?php
include("conectar.php");
include("header.php");

$personas = pg_query($conn, "SELECT * FROM personas ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Personas - Tecnobog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2 class="mb-4 text-center">👤 Personas</h2>
  <form id="formPersona" class="mb-4">
    <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre completo" required>
    <input type="text" name="documento" class="form-control mb-2" placeholder="Documento" required>
    <input type="email" name="correo" class="form-control mb-2" placeholder="Correo">
    <input type="text" name="telefono" class="form-control mb-2" placeholder="Teléfono">
    <button type="submit" class="btn btn-primary w-100">Registrar Persona</button>
  </form>

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
</div>

<script>
document.getElementById("formPersona").addEventListener("submit", async (e) => {
  e.preventDefault();
  let data = new FormData(e.target);
  let res = await fetch("api_persona.php", { method: "POST", body: data });
  let r = await res.json();
  if (r.success) { alert("✅ Persona registrada"); location.reload(); }
  else { alert("❌ " + r.error); }
});
</script>
</body>
</html>
