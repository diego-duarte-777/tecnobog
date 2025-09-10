<?php
// Datos de conexión a PostgreSQL en Render
$host = "dpg-d30nui8gjchc73f36ul0-a.virginia-postgres.render.com";
$port = "5432";
$dbname = "prueva";
$user = "prueva_user";
$password = "CLQQwOGZ4HpzqnKgHO8il7YSQyCezf01";

// Cadena de conexión
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password sslmode=require";

// Intentar la conexión
$conn = pg_connect($conn_string);

// Verificar si la conexión fue exitosa
if (!$conn) {
    die("❌ Error al conectar con la base de datos: " . pg_last_error());
} else {
    // Si quieres verificar conexión, descomenta la siguiente línea
    // echo "✅ Conexión exitosa a la base de datos en Render";
}
?>
