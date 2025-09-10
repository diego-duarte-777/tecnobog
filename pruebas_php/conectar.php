<?php
$databaseUrl = getenv("DATABASE_URL");

if ($databaseUrl) {
    // Parsear la URL de Render
    $db = parse_url($databaseUrl);

    $host = $db["host"];
    $port = $db["port"] ?? 5432;
    $user = $db["user"];
    $password = $db["pass"];
    $dbname = ltrim($db["path"], "/");

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
} else {
    // Config local
    $host = "localhost";
    $port = "5432";
    $user = "postgres";
    $password = "1234";
    $dbname = "tienda_computadoras";

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
}

if (!$conn) {
    die("❌ Error de conexión: " . pg_last_error());
}
?>
