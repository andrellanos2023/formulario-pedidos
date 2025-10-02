<?php
header('Content-Type: application/json; charset=utf-8');

// 🔹 Configuración PostgreSQL
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP";

// IP y User-Agent del visitante
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'No identificado';

try {
    // 🔹 Conexión a PostgreSQL CON SSL
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔹 Captura y sanea datos del formulario
    $color = $_POST['color'] ?? 'KIT DETOX';
    $talla = $_POST['talla'] ?? 'ÚNICA';
    $nombre = strtoupper(trim($_POST['nombre'] ?? ''));
    $cedula = trim($_POST['cedula'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $barrio = trim($_POST['barrio'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $departamento = trim($_POST['departamento'] ?? '');

    // 🔹 Validación de campos obligatorios
    if (empty($nombre) || empty($cedula) || empty($telefono) || empty($direccion) || empty($barrio) || empty($ciudad) || empty($departamento)) {
        echo json_encode([
            'success' => false,
            'error' => 'Por favor completa todos los campos obligatorios.'
        ]);
        exit;
    }

    // 🔹 Inserta el pedido
    $stmt = $conn->prepare("
        INSERT INTO pedidos (
            color, talla, nombre, cedula, telefono, whatsapp,
            direccion, barrio, ciudad, departamento, ip, user_agent
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $result = $stmt->execute([
        $color,
        $talla,
        $nombre,
        $cedula,
        $telefono,
        $whatsapp,
        $direccion,
        $barrio,
        $ciudad,
        $departamento,
        $ip,
        $user_agent
    ]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Pedido guardado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Error al guardar el pedido en la base de datos.'
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Error PostgreSQL en grabar.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error de conexión con la base de datos. Intente más tarde.'
    ]);
}
?>