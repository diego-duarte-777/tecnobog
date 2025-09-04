<?php
include("conectar.php");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(["success" => false, "error" => "ID no proporcionado"]);
        exit;
    }

    $sql = "DELETE FROM productos WHERE id=$1";
    $result = pg_query_params($conn, $sql, [$id]);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Método no permitido"]);
}
