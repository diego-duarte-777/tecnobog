<?php
session_start();
include("conectar.php");

if (!isset($_SESSION['usuario'])) {
    echo json_encode(["success" => false, "error" => "Debes iniciar sesión"]);
    exit;
}

$usuario = $_SESSION['usuario'];
$producto_id = $_POST['producto_id'];

// Buscar ID del usuario
$resUser = pg_query_params($conn, "SELECT id FROM usuarios WHERE username = $1", [$usuario]);
$userRow = pg_fetch_assoc($resUser);
$usuario_id = $userRow['id'];

// Verificar stock
$check = pg_query_params($conn, "SELECT cantidad FROM productos WHERE id = $1", [$producto_id]);
$row = pg_fetch_assoc($check);

if (!$row || $row['cantidad'] <= 0) {
    echo json_encode(["success" => false, "error" => "Producto sin stock"]);
    exit;
}

// Registrar la compra
$insert = "INSERT INTO compras (usuario_id, producto_id) VALUES ($1, $2)";
$res = pg_query_params($conn, $insert, [$usuario_id, $producto_id]);

if ($res) {
    // Disminuir stock
    pg_query_params($conn, "UPDATE productos SET cantidad = cantidad - 1 WHERE id = $1", [$producto_id]);
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
}
?>
