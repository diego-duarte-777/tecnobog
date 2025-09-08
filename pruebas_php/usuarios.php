<?php
include("conectar.php");
include("header.php");

// Solo admin puede entrar
if ($_SESSION['rol'] !== 'admin') {
    echo "<div class='container py-5'><div class='alert alert-danger'>🚫 No tienes permiso para ver esta página.</div></div>";
    exit;
}

$usuarios = pg_query($conn, "SELECT id, username, rol FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios - Tecnobog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2 class="mb-4 text-center">👥 Gestión de Usuarios</h2>

  <!-- Formulario nuevo usuario -->
  <form id="formUsuario" class="mb-4">
    <input type="text" name="username" class="form-control mb-2" placeholder="Nombre de usuario" required>
    <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña" required>
    <select name="rol" class="form-select mb-2" required>
      <option value="usuario">Usuario</option>
      <option value="admin">Administrador</option>
    </select>
    <button type="submit" class="btn btn-primary w-100">Crear Usuario</button>
  </form>

  <!-- Listado de usuarios -->
  <table class="table table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Rol</th>
      </tr>
    </thead>
    <tbody>
      <?php while($u = pg_fetch_assoc($usuarios)): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['rol']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
document.getElementById("formUsuario").addEventListener("submit", async (e) => {
  e.preventDefault();
  let data = new FormData(e.target);
  let res = await fetch("api_usuario.php", { method: "POST", body: data });
  let r = await res.json();
  if (r.success) { alert("✅ Usuario creado"); location.reload(); }
  else { alert("❌ " + r.error); }
});
</script>
</body>
</html>
