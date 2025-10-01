<?php
session_start();
if (!isset($_SESSION['autenticado'])) {
    header('Location: login.php');
    exit;
}

// 🔹 Datos de conexión (Render PostgreSQL)
$host = "dpg-d3e795jipnbc73bi9fng-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "formulario_db_qpn5";
$user = "usuarioform";
$password = "zhLh8QQfitSubKHj1DlNf3vljNn0g1dP"; // tu contraseña real de Render

$eliminado = false;
$error = '';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if (!$id || $id <= 0) {
        $error = "ID no válido.";
    } else {
        try {
            // 🔹 Conexión a PostgreSQL
            $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("DELETE FROM pedidos WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                $eliminado = true;
            } else {
                $error = "Pedido no encontrado o ya eliminado.";
            }
        } catch (PDOException $e) {
            $error = "Error de conexión: " . $e->getMessage();
        }
    }
} else {
    $error = "ID no válido.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Pedido</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }

        .message-box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        h2 {
            margin-bottom: 15px;
            color: #000;
        }

        p {
            margin-bottom: 20px;
            color: #555;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background: #3276B1;
            color: white;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #225a85;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="message-box">
        <?php if ($eliminado): ?>
            <h2>Pedido eliminado</h2>
            <p>El pedido fue eliminado exitosamente.</p>
        <?php else: ?>
            <h2>Error</h2>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <a href="ver_pedidos.php" class="btn">Volver al listado</a>
    </div>

</body>
</html>
