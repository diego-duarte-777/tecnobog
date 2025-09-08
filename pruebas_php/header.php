<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="index.php">💻 Tecnobog</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="mis_compras.php">Mis Compras</a></li>
        <?php if ($_SESSION['rol'] === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="personas.php">Personas</a></li>
          <li class="nav-item"><a class="nav-link" href="asignaciones.php">Asignaciones</a></li>
          <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
        <?php endif; ?>
      </ul>
      <span class="navbar-text me-3">👤 <?= $_SESSION['usuario']; ?> (<?= $_SESSION['rol']; ?>)</span>
      <a href="logout.php" class="btn btn-danger btn-sm">🚪 Cerrar sesión</a>
    </div>
  </div>
</nav>
