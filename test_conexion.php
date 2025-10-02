<?php
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP";

try {
    echo "Probando conexión PostgreSQL...<br>";
    
    // Intentar con SSL
    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $user, $password);
        echo "✅ Conexión EXITOSA con SSL<br>";
    } catch (PDOException $e) {
        echo "❌ Conexión con SSL falló: " . $e->getMessage() . "<br>";
        
        // Intentar sin SSL
        try {
            $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
            echo "✅ Conexión EXITOSA sin SSL<br>";
        } catch (PDOException $e2) {
            echo "❌ Conexión sin SSL también falló: " . $e2->getMessage() . "<br>";
            exit;
        }
    }
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar tabla
    $stmt = $conn->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'pedidos')");
    $tablaExiste = $stmt->fetchColumn();
    
    if ($tablaExiste) {
        echo "✅ Tabla 'pedidos' existe<br>";
    } else {
        echo "❌ Tabla 'pedidos' NO existe<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error general: " . $e->getMessage() . "<br>";
}
?>