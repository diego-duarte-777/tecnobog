<?php
session_start();
include("conectar.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$resUser = pg_query_params($conn, "SELECT id FROM usuarios WHERE username = $1", [$usuario]);
$userRow = pg_fetch_assoc($resUser);
$usuario_id = $userRow['id'];

$sql = "SELECT c.id, p.nombre, p.precio, c.fecha 
        FROM compras c
        JOIN productos p ON p.id = c.producto_id
        WHERE c.usuario_id = $1
        ORDER BY c.fecha DESC";
$compras = pg_query_params($conn, $sql, [$usuario_id]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Compras - Tecnobog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h2 class="mb-4">🛒 Mis Compras</h2>
  <table class="table table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Precio</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      <?php while($c = pg_fetch_assoc($compras)): ?>
        <tr>
          <td><?= $c['id'] ?></td>
          <td><?= htmlspecialchars($c['nombre']) ?></td>
          <td>$<?= number_format($c['precio'], 2) ?></td>
          <td><?= $c['fecha'] ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
