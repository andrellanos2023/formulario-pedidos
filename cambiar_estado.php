<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    exit('no_autorizado');
}

// 🔹 Configuración PostgreSQL
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP";

if (isset($_POST['id'], $_POST['estado'])) {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $estado = trim($_POST['estado']);

    $estadosPermitidos = ['En confirmación', 'Pendiente por enviar', 'Enviado', 'Descartado'];

    if (!$id || $id <= 0) {
        exit('id_invalido');
    }

    if (!in_array($estado, $estadosPermitidos)) {
        exit('estado_invalido');
    }

    try {
        $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("UPDATE pedidos SET estado = :estado WHERE id = :id");
        $stmt->execute([
            ':estado' => $estado,
            ':id' => $id
        ]);

        echo "ok";
    } catch (PDOException $e) {
        error_log("Error PostgreSQL: " . $e->getMessage());
        echo "error_bd";
    }
} else {
    echo "datos_incompletos";
}
?>