<?php
// Primero intentamos obtener la URL de conexión desde Render (variable de entorno)
$databaseUrl = getenv("DATABASE_URL");

if ($databaseUrl) {
    // Parsear la URL de conexión de Render
    $db = parse_url($databaseUrl);

    $host = $db["host"];
    $port = $db["port"] ?? "5432";
    $user = $db["user"];
    $password = $db["pass"];
    $dbname = ltrim($db["path"], "/");

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
} else {
    // Configuración local (para pruebas en tu PC)
    $host = "localhost";
    $port = "5432";
    $dbname = "tienda_computadoras";
    $user = "postgres";
    $password = "xtoby122x";

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
}

// Verificar conexión
if (!$conn) {
    die("❌ Conexión fallida: " . pg_last_error());
}
?>
