<?php
include 'conectar.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Tienda de Computadoras</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="btn btn-success" href="agregar.php">➕ Agregar Producto</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <div class="container">
    <div class="card shadow-lg rounded-3">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Lista de Productos</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT * FROM productos ORDER BY id ASC";
              $result = pg_query($conn, $sql);

              while ($row = pg_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nombre']}</td>
                        <td>{$row['descripcion']}</td>
                        <td>$ {$row['precio']}</td>
                        <td>{$row['cantidad']}</td>
                        <td>
                          <a href='editar.php?id={$row['id']}' class='btn btn-warning btn-sm'>✏️ Editar</a>
                          <a href='eliminar.php?id={$row['id']}' class='btn btn-danger btn-sm' 
                             onclick=\"return confirm('¿Seguro que deseas eliminar este producto?');\">🗑️ Eliminar</a>
                        </td>
                      </tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
