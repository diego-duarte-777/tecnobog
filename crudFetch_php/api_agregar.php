<?php
header("Content-Type: application/json");
include("conectar.php");

// Solo permitir POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Método no permitido", "metodo" => $_SERVER["REQUEST_METHOD"]]);
    exit;
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$precio = $_POST['precio'] ?? 0;
$cantidad = $_POST['cantidad'] ?? 0;
$imagen = null;

// Manejo de la imagen (si se sube)
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $directorio = "uploads/";
    
    // Crear carpeta uploads si no existe
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $nombreArchivo = uniqid() . "_" . basename($_FILES['imagen']['name']);
    $rutaDestino = $directorio . $nombreArchivo;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $imagen = $rutaDestino; // Guardamos la ruta en BD
    }
}

// Insertar en la base de datos
$sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad, imagen) 
        VALUES ($1, $2, $3, $4, $5)";
$params = [$nombre, $descripcion, $precio, $cantidad, $imagen];

$result = pg_query_params($conn, $sql, $params);

if ($result) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Error al agregar producto: " . pg_last_error($conn)]);
}
?>
