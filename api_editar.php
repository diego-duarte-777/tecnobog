<?php
include("conectar.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    $rutaImagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Carpeta donde se guardarán las imágenes
        $directorio = "uploads/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Sacamos extensión (jpg, png, webp, etc.)
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);

        // Generamos un nombre único SIN espacios ni caracteres raros
        $nombreArchivo = uniqid() . "." . strtolower($extension);

        // Ruta final en la carpeta
        $rutaImagen = $directorio . $nombreArchivo;

        // Movemos la imagen subida a la carpeta
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            echo json_encode(["success" => false, "error" => "Error al guardar la imagen"]);
            exit;
        }
    }

    // Guardamos en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad, imagen) 
            VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $sql, [$nombre, $descripcion, $precio, $cantidad, $rutaImagen]);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => pg_last_error($conn)]);
    }
} else {
    echo json_encode(["error" => "Método no permitido", "metodo" => $_SERVER["REQUEST_METHOD"]]);
}
