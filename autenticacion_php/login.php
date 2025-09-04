<?php
session_start();
include("conectar.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM usuarios WHERE username = $1";
    $result = pg_query_params($conn, $sql, [$username]);
    if ($row = pg_fetch_assoc($result)) {
     
        if (crypt($password, $row['password']) === $row['password']) {
            $_SESSION['usuario'] = $row['username'];
            $_SESSION['rol'] = $row['rol'] ?? 'usuario'; 
            header("Location: index.php");
            exit;
        } else {
            $error = "❌ Contraseña incorrecta";
        }
    } else {
        $error = "❌ Usuario no encontrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - Tecnobog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
  <div class="card shadow p-4" style="width: 350px;">
    <h3 class="text-center mb-4">🔑Iniciar Sesión</h3>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Usuario</label>
        <input type="text" class="form-control" name="username" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" class="form-control" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    </form>
  </div>
</body>
</html>