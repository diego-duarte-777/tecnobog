<?php
include("conectar.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $imagen = null;

    if (!empty($_FILES['imagen']['name'])) {
        $dir = "uploads/";
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $fileName = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $target = $dir . $fileName;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $target)) {
            $imagen = $target;
        }
    }

    $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad, imagen)
            VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $sql, [$nombre, $descripcion, $precio, $cantidad, $imagen]);

    if ($result) echo json_encode(["success" => true]);
    else echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
}
?>
