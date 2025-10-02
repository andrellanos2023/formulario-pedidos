<?php
header('Content-Type: application/json; charset=utf-8');

// 🔹 Configuración PostgreSQL
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP";

try {
    // 🔹 Conexión PostgreSQL con SSL
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔹 Captura datos del formulario
    $nombre = strtoupper(trim($_POST['nombre'] ?? ''));
    $cedula = trim($_POST['cedula'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $barrio = trim($_POST['barrio'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $departamento = trim($_POST['departamento'] ?? '');

    // 🔹 Validación
    if (empty($nombre) || empty($cedula) || empty($telefono) || empty($whatsapp) || empty($direccion) || empty($barrio) || empty($ciudad) || empty($departamento)) {
        echo json_encode([
            'success' => false,
            'error' => 'Por favor completa todos los campos obligatorios.'
        ]);
        exit;
    }

    // 🔹 Convertir a números (BIGINT)
    $cedula = (int)$cedula;
    $telefono = (int)$telefono;
    $whatsapp = (int)$whatsapp;

    // 🔹 Insertar pedido (SOLO los campos que existen en tu tabla)
    $sql = "INSERT INTO pedidos (nombre, cedula, telefono, whatsapp, direccion, barrio, ciudad, departamento) 
            VALUES (:nombre, :cedula, :telefono, :whatsapp, :direccion, :barrio, :ciudad, :departamento)";
    
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
        ':nombre' => $nombre,
        ':cedula' => $cedula,
        ':telefono' => $telefono,
        ':whatsapp' => $whatsapp,
        ':direccion' => $direccion,
        ':barrio' => $barrio,
        ':ciudad' => $ciudad,
        ':departamento' => $departamento
    ]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Pedido guardado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'No se pudo guardar el pedido.'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
?>