<?php
// test_conexion.php
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP";

try {
    echo "Intentando conectar a: $host<br>";
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión EXITOSA a PostgreSQL<br>";
    
    // Verificar si la tabla existe
    $stmt = $conn->query("SELECT COUNT(*) FROM pedidos");
    $count = $stmt->fetchColumn();
    echo "✅ Tabla 'pedidos' encontrada con $count registros<br>";
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
}
?>