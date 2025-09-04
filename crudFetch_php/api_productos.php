<?php
header("Content-Type: application/json; charset=UTF-8");
include("conectar.php");

$sql = "SELECT id, nombre, precio, imagen, descripcion, cantidad FROM productos ORDER BY id DESC";
$result = pg_query($conn, $sql);

$productos = [];
if ($result) {
    while ($row = pg_fetch_assoc($result)) {
        $productos[] = [
            "id" => (int)$row["id"],
            "nombre" => $row["nombre"],
            "descripcion" => $row["descripcion"],
            "precio" => (float)$row["precio"],
            "cantidad" => (int)$row["cantidad"],
            "imagen" => $row["imagen"]
        ];
    }
    echo json_encode($productos, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Error al obtener productos"]);
}
?>
