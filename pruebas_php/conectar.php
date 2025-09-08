<?php
$host = "localhost";
$port = "5432";  
$dbname = "tienda_computadoras"; 
$user = "postgres"; 
$password = "xtoby122x"; 

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Conexión fallida: " . pg_last_error());
}
?>
