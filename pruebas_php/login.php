<?php
session_start();
include("conectar.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE username = $1";
    $result = pg_query_params($conn, $sql, [$username]);

    if ($row = pg_fetch_assoc($result)) {
        $check = pg_fetch_result(
            pg_query_params($conn, "SELECT crypt($1, $2)", [$password, $row['password']]),
            0,
            0
        );

        if ($check === $row['password']) {
            $_SESSION['usuario'] = $row['username'];
            $_SESSION['rol'] = $row['rol'];
            header("Location: index.php");
            exit;
        } else {
            $error = "❌ Contraseña incorrecta";
        }
    } else {
        $error = "❌ Usuario no encontrado";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    $username = $_POST['new_username'];
    $password = $_POST['new_password'];

    // Hashear contraseña
    $hash = pg_fetch_result(
        pg_query_params($conn, "SELECT crypt($1, gen_salt('bf'))", [$password]),
        0,
        0
    );

    $sql = "INSERT INTO usuarios (username, password, rol) VALUES ($1, $2, 'usuario')";
    $result = pg_query_params($conn, $sql, [$username, $hash]);

    if ($result) {
        $success = "✅ Usuario creado, ya puedes iniciar sesión";
    } else {
        $error = "❌ Error: " . pg_last_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login - Tecnobog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
  <div class="card shadow p-4" style="width: 400px;">
    <ul class="nav nav-tabs" id="authTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button">🔑 Iniciar Sesión</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button">🆕 Crear Cuenta</button>
      </li>
    </ul>
    <div class="tab-content mt-3">
      <!-- Login -->
      <div class="tab-pane fade show active" id="login" role="tabpanel">
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php elseif (!empty($success)): ?>
          <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <form method="POST">
          <input type="hidden" name="login" value="1">
          <div class="mb-3">
            <label class="form-label">Usuario</label>
            <input type="text" class="form-control" name="username" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
      </div>
      <!-- Registro -->
      <div class="tab-pane fade" id="register" role="tabpanel">
        <form method="POST">
          <input type="hidden" name="register" value="1">
          <div class="mb-3">
            <label class="form-label">Nuevo Usuario</label>
            <input type="text" class="form-control" name="new_username" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" name="new_password" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Crear Usuario</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
