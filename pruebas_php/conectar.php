<?php
$databaseUrl = getenv("DATABASE_URL");

if ($databaseUrl) {
    // Aseguramos que comience con postgres://
    $databaseUrl = str_replace("postgresql://", "postgres://", $databaseUrl);

    $db = parse_url($databaseUrl);

    $host = $db["host"];
    $port = $db["port"] ?? "5432";
    $user = $db["user"];
    $password = $db["pass"];
    $dbname = ltrim($db["path"], "/");

    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    if (!$conn) {
        die("❌ Error conectando a Render DB: " . pg_last_error());
    }
} else {
    die("❌ No se encontró DATABASE_URL en Render. ¿La configuraste en Settings → Environment Variables?");
}
?>
