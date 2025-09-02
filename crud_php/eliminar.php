<?php
include("conectar.php");

// Verificar si viene el ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar producto por ID
    $sql = "SELECT * FROM productos WHERE id = $id";
    $result = pg_query($conn, $sql);

    if ($result && pg_num_rows($result) > 0) {
        $producto = pg_fetch_assoc($result);
    } else {
        echo "Producto no encontrado.";
        exit;
    }
}

// Eliminar si se confirma
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $sql = "DELETE FROM productos WHERE id = $id";
    $result = pg_query($conn, $sql);

    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al eliminar el producto.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Eliminar Producto</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">Tienda de Productos</a>
    </div>
  </nav>

  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-4 text-center" style="max-width: 500px; width: 100%;">
      <h3 class="mb-4 text-danger">⚠️ Confirmar Eliminación</h3>
      <p>¿Estás seguro de que deseas eliminar el producto:</p>
      <h5 class="fw-bold">"<?php echo $producto['nombre']; ?>"</h5>
      <p class="text-muted">Precio: $<?php echo $producto['precio']; ?></p>
      
      <form method="POST" action="eliminar.php">
        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
        <button type="submit" class="btn btn-danger w-100 mb-2">🗑️ Eliminar</button>
        <a href="index.php" class="btn btn-secondary w-100">⬅️ Cancelar</a>
      </form>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
