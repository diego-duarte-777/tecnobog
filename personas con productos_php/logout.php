<?php
session_start();
session_destroy();
header("Location: ../autenticacion_php/login.php");
exit;
?>
