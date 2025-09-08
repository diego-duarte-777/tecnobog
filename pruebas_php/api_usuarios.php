<?php
include("conectar.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Hashear contraseña
    $hash = pg_fetch_result(
        pg_query_params($conn, "SELECT crypt($1, gen_salt('bf'))", [$password]),
        0,
        0
    );

    $sql = "INSERT INTO usuarios (username, password, rol) VALUES ($1, $2, $3)";
    $result = pg_query_params($conn, $sql, [$username, $hash, $rol]);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
    }
}
?>
