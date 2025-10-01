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
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
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
    die("Error de conexión, intente más tarde.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pedido</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7fa;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 25px;
            font-size: 22px;
            text-align: center;
            color: #000;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        button {
            padding: 12px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #333;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #3276B1;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Editar Pedido #<?= htmlspecialchars($pedido['id']) ?></h1>

        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="color" value="<?= htmlspecialchars($pedido['color']) ?>" placeholder="Color" required>
            <input type="text" name="talla" value="<?= htmlspecialchars($pedido['talla']) ?>" placeholder="Talla" required>
            <input type="text" name="nombre" value="<?= htmlspecialchars($pedido['nombre']) ?>" placeholder="Nombre" required>
            <input type="text" name="cedula" value="<?= htmlspecialchars($pedido['cedula']) ?>" placeholder="Cédula">
            <input type="text" name="telefono" value="<?= htmlspecialchars($pedido['telefono']) ?>" placeholder="Teléfono">
            <input type="text" name="whatsapp" value="<?= htmlspecialchars($pedido['whatsapp']) ?>" placeholder="WhatsApp">
            <input type="text" name="direccion" value="<?= htmlspecialchars($pedido['direccion']) ?>" placeholder="Dirección">
            <input type="text" name="barrio" value="<?= htmlspecialchars($pedido['barrio']) ?>" placeholder="Barrio">
            <input type="text" name="ciudad" value="<?= htmlspecialchars($pedido['ciudad']) ?>" placeholder="Ciudad">
            <input type="text" name="departamento" value="<?= htmlspecialchars($pedido['departamento']) ?>" placeholder="Departamento">

            <button type="submit">Guardar Cambios</button>
        </form>

        <a href="ver_pedidos.php" class="back-link">← Volver al listado</a>
    </div>
</body>
</html>