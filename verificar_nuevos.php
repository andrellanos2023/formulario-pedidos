<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    http_response_code(403);
    exit;
}

// 🔹 Configuración PostgreSQL
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP";

// Obtener el último ID conocido desde el parámetro GET
$ultimo_id_actual = isset($_GET['ultimo']) ? intval($_GET['ultimo']) : 0;

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ✅ Consulta corregida - usar tabla "pedidos"
    $stmt = $conn->query("SELECT MAX(id) as max_id FROM pedidos");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $nuevo_id = isset($row['max_id']) ? intval($row['max_id']) : 0;

    $hay_nuevo = $nuevo_id > $ultimo_id_actual;

    // Respuesta en formato JSON
    echo json_encode([
        'nuevo' => $hay_nuevo,
        'ultimo' => $nuevo_id
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'nuevo' => false,
        'ultimo' => $ultimo_id_actual
    ]);
}
?>