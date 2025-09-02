<?php
include("conectar.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM productos WHERE id = $id";
    $result = pg_query($conn, $sql);
    if ($result && pg_num_rows($result) > 0) {
        $producto = pg_fetch_assoc($result);
    } else {
        echo "Producto no encontrado.";
        exit;
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $sql = "UPDATE productos SET nombre='$nombre', precio='$precio' WHERE id=$id";
    $result = pg_query($conn, $sql);
    if ($result) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar el producto.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Editar Producto</title>
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
    <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
      <h3 class="text-center mb-4">:lápiz2: Editar Producto</h3>
      <form action="editar.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre del producto</label>
          <input type="text" class="form-control" id="nombre" name="nombre"
                 value="<?php echo $producto['nombre']; ?>" required>
        </div>
        <div class="mb-3">
          <label for="precio" class="form-label">Precio</label>
          <input type="number" class="form-control" id="precio" name="precio" step="0.01"
                 value="<?php echo $producto['precio']; ?>" required>
        </div>
        <button type="submit" class="btn btn-warning w-100">Actualizar</button>
        <a href="index.php" class="btn btn-secondary w-100 mt-2">:flecha_a_la_izquierda: Volver</a>
      </form>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>