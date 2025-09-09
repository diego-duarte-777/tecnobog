<?php
include("conectar.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $documento = $_POST['documento'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $sql = "INSERT INTO personas (nombre, documento, correo, telefono)
            VALUES ($1, $2, $3, $4)";
    $result = pg_query_params($conn, $sql, [$nombre, $documento, $correo, $telefono]);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
    }
}
?>
