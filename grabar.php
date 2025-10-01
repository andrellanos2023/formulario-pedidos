<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . "/fpdf/fpdf.php";

// 🔹 Datos de conexión (Render PostgreSQL - usar la EXTERNAL URL)
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP"; // <-- tu contraseña real

// IP y User-Agent del visitante
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'No identificado';

try {
    // 🔹 Conexión a PostgreSQL
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 🔒 Bloqueo por múltiples intentos fallidos recientes
    $stmtBloqueo = $conn->prepare("
        SELECT COUNT(*) 
        FROM registro_abusos 
        WHERE ip = ? AND fecha > (NOW() - INTERVAL '1 minute')
    ");
    $stmtBloqueo->execute([$ip]);
    $intentosRecientes = (int) $stmtBloqueo->fetchColumn();

    if ($intentosRecientes >= 3) {
        // Registrar el bloqueo
        $logStmt = $conn->prepare("INSERT INTO registro_abusos (ip, user_agent, motivo) VALUES (?, ?, ?)");
        $logStmt->execute([$ip, $user_agent, 'Bloqueado por múltiples intentos sospechosos']);

        echo json_encode([
            'success' => false,
            'error' => '🚫 Tu IP ha sido bloqueada temporalmente por múltiples intentos fallidos.'
        ]);
        exit;
    }

    // 🔹 Captura y sanea datos del formulario
    $color = $_POST['color'] ?? '';
    $talla = $_POST['talla'] ?? '';
    $nombre = strtoupper(trim($_POST['nombre'] ?? ''));
    $cedula = trim($_POST['cedula'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $barrio = trim($_POST['barrio'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $departamento = trim($_POST['departamento'] ?? '');

    // 🔹 Validación de campos obligatorios
    if (
        empty($nombre) || empty($cedula) || empty($telefono) ||
        empty($direccion) || empty($barrio) || empty($ciudad) ||
        empty($departamento) || empty($color) || empty($talla)
    ) {
        // Registrar intento fallido
        $logStmt = $conn->prepare("INSERT INTO registro_abusos (ip, user_agent, motivo) VALUES (?, ?, ?)");
        $logStmt->execute([$ip, $user_agent, 'Campos obligatorios vacíos o incompletos']);

        echo json_encode([
            'success' => false,
            'error' => 'Campos obligatorios vacíos o incompletos.'
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
    $stmt->execute([
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

    // 🔹 Generar PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Resumen del Pedido');
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Nombre: $nombre", 0, 1);
    $pdf->Cell(0, 10, "Cedula: $cedula", 0, 1);
    $pdf->Cell(0, 10, "Telefono: $telefono", 0, 1);
    $pdf->Cell(0, 10, "WhatsApp: $whatsapp", 0, 1);
    $pdf->Cell(0, 10, "Direccion: $direccion", 0, 1);
    $pdf->Cell(0, 10, "Barrio: $barrio", 0, 1);
    $pdf->Cell(0, 10, "Ciudad: $ciudad", 0, 1);
    $pdf->Cell(0, 10, "Departamento: $departamento", 0, 1);
    $pdf->Cell(0, 10, "Color: $color", 0, 1);
    $pdf->Cell(0, 10, "Talla: $talla", 0, 1);
    $pdf->Cell(0, 10, "IP: $ip", 0, 1);
    $pdf->MultiCell(0, 10, "User-Agent: $user_agent");

    if (!is_dir('pdf')) {
        mkdir('pdf', 0777, true);
    }
    $pdfFileName = 'pdf/pedido_' . time() . '.pdf';
    $pdf->Output('F', $pdfFileName);

    echo json_encode([
        'success' => true,
        'message' => 'Pedido guardado correctamente.',
        'pdf' => $pdfFileName
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
