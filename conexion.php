<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "formulario_db_qpn5";

$stmt = new mysqli($servername, $username, $password, $dbname);

if ($stmt->connect_error) {
    die("Conexión fallida: " . $stmt->connect_error);
}

// Vamos a ejecutar una consulta simple:
$res = $stmt->query("SELECT 1");

if ($res) {
    echo "Conexión estable y consulta exitosa.";
} else {
    echo "Error en la consulta.";
}

$stmt->close();

?>
