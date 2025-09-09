<?php
include("conectar.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $persona_id = $_POST['persona_id'];
    $producto_id = $_POST['producto_id'];

    $sql = "INSERT INTO asignaciones (persona_id, producto_id) VALUES ($1, $2)";
    $result = pg_query_params($conn, $sql, [$persona_id, $producto_id]);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
    }
}
?>
