<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}

$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear tabla pedidos
    $sql = "CREATE TABLE IF NOT EXISTS pedidos (
        id SERIAL PRIMARY KEY,
        color VARCHAR(50),
        talla VARCHAR(50),
        nombre VARCHAR(100),
        cedula VARCHAR(20),
        telefono VARCHAR(20),
        whatsapp VARCHAR(20),
        direccion TEXT,
        barrio VARCHAR(100),
        ciudad VARCHAR(100),
        departamento VARCHAR(100),
        estado VARCHAR(50) DEFAULT 'En confirmación',
        ip VARCHAR(45),
        user_agent TEXT,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "✅ Tabla 'pedidos' creada/verificada exitosamente<br>";
    
    // Verificar que se creó
    $stmt = $conn->query("SELECT COUNT(*) FROM pedidos");
    $count = $stmt->fetchColumn();
    echo "📊 La tabla ahora tiene: " . $count . " registros<br>";
    
    echo "<br>🎯 Ahora puedes hacer un pedido de prueba desde el formulario principal";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>