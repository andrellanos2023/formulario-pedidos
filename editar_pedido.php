<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}

// 🔹 Configuración PostgreSQL
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if (!$id || $id <= 0) {
            die("ID no válido.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $color = trim($_POST['color'] ?? '');
            $talla = trim($_POST['talla'] ?? '');
            $nombre = strtoupper(trim($_POST['nombre'] ?? ''));
            $cedula = trim($_POST['cedula'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $whatsapp = trim($_POST['whatsapp'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $barrio = trim($_POST['barrio'] ?? '');
            $ciudad = trim($_POST['ciudad'] ?? '');
            $departamento = trim($_POST['departamento'] ?? '');

            if ($color && $talla && $nombre) {
                $stmt = $conn->prepare("UPDATE pedidos SET color=?, talla=?, nombre=?, cedula=?, telefono=?, whatsapp=?, direccion=?, barrio=?, ciudad=?, departamento=? WHERE id=?");
                $stmt->execute([$color, $talla, $nombre, $cedula, $telefono, $whatsapp, $direccion, $barrio, $ciudad, $departamento, $id]);

                header("Location: ver_pedidos.php");
                exit;
            } else {
                $error = "Los campos Color, Talla y Nombre son obligatorios.";
            }
        }

        $stmt = $conn->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            die("Pedido no encontrado.");
        }
    } else {
        die("ID no especificado.");
    }
} catch (PDOException $e) {
    error_log("Error PostgreSQL: " . $e->getMessage());
    die("Error de conexión, intente más tarde.");
}
?>