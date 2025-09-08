<?php
include("conectar.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $persona_id = $_POST['persona_id'];
    $producto_id = $_POST['producto_id'];

    // Verificar stock antes de asignar
    $checkStock = pg_query_params($conn, "SELECT cantidad FROM productos WHERE id = $1", [$producto_id]);
    $row = pg_fetch_assoc($checkStock);

    if ($row && $row['cantidad'] > 0) {
        // 1. Insertar la asignación
        $sql = "INSERT INTO asignaciones (persona_id, producto_id, fecha_asignacion)
                VALUES ($1, $2, NOW())";
        $result = pg_query_params($conn, $sql, [$persona_id, $producto_id]);

        if ($result) {
            // 2. Disminuir stock
            $update = "UPDATE productos SET cantidad = cantidad - 1 WHERE id = $1";
            pg_query_params($conn, $update, [$producto_id]);

            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Producto sin stock disponible"]);
    }
}
?>
