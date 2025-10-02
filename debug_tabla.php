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
    
    echo "<h2>🔍 DIAGNÓSTICO BASE DE DATOS</h2>";
    
    // 1. Verificar conexión
    echo "✅ Conexión PostgreSQL exitosa<br><br>";
    
    // 2. Verificar si la tabla existe
    $stmt = $conn->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'pedidos')");
    $tablaExiste = $stmt->fetchColumn();
    
    if ($tablaExiste) {
        echo "✅ La tabla 'pedidos' EXISTE<br>";
        
        // 3. Contar registros
        $stmt = $conn->query("SELECT COUNT(*) as total FROM pedidos");
        $total = $stmt->fetchColumn();
        echo "📊 Total de pedidos: <strong>" . $total . "</strong><br>";
        
        // 4. Mostrar estructura de la tabla
        echo "<br>🏗️ Estructura de la tabla:<br>";
        $stmt = $conn->query("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'pedidos'");
        $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columnas as $columna) {
            echo "- " . $columna['column_name'] . " (" . $columna['data_type'] . ")<br>";
        }
        
        // 5. Mostrar últimos pedidos si existen
        if ($total > 0) {
            echo "<br>📦 Últimos 5 pedidos:<br>";
            $stmt = $conn->query("SELECT id, nombre, color, talla, fecha FROM pedidos ORDER BY id DESC LIMIT 5");
            $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($pedidos as $pedido) {
                echo "ID: " . $pedido['id'] . " | " . $pedido['nombre'] . " | " . $pedido['color'] . " | " . $pedido['talla'] . " | " . $pedido['fecha'] . "<br>";
            }
        } else {
            echo "<br>❌ La tabla está VACÍA - No hay pedidos registrados<br>";
        }
        
    } else {
        echo "❌ La tabla 'pedidos' NO EXISTE<br>";
        echo "<br>💡 Solución: Ejecuta crear_tablas.php para crear la tabla<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
}
?>