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

$pedidos = [];

try {
    // Intentar conexión con diferentes opciones de SSL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    
    // Opción 1: Intentar con SSL
    try {
        $conn = new PDO("$dsn;sslmode=require", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Opción 2: Intentar sin SSL
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Verificar si la tabla existe
    $stmt = $conn->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = 'pedidos')");
    $tablaExiste = $stmt->fetchColumn();
    
    if (!$tablaExiste) {
        die("Error: La tabla 'pedidos' no existe en la base de datos.");
    }

    // Consulta para obtener todos los pedidos
    $stmt = $conn->query("SELECT * FROM pedidos ORDER BY id DESC");
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Mostrar error detallado solo en desarrollo
    $error = "Error de conexión: " . $e->getMessage();
    error_log($error);
    die("Error de conexión a la base de datos. Intente más tarde.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Pedidos</title>
    <!-- CAMPANA DE NOTIFICACIÓN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <audio id="notificacion-audio" src="./notificacion.mp3" preload="auto"></audio>
    <style>
        /* (Mantener todos los estilos CSS igual) */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7fa;
            color: #333;
            margin: 0;
            padding: 15px;
        }

        h1 {
            text-align: center;
            color: #000;
            margin-bottom: 15px;
            font-size: 22px;
        }

        .filtros-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        #buscador,
        #filtroEstado {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            min-width: 180px;
        }

        .table-container {
            overflow-x: auto;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            max-width: 100%;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th,
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #eee;
            text-align: center;
            font-size: 14px;
            word-break: break-word;
        }

        th {
            background: #000;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .estado-select {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 13px;
            cursor: pointer;
            min-width: 140px;
            text-align: center;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .estado-select.en-confirmación {
            background: #fff3cd;
            color: #856404;
            border-color: #ffeeba;
        }

        .estado-select.pendiente-por-enviar {
            background: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }

        .estado-select.enviado {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .estado-select.descartado {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .action-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 2px;
            font-size: 12px;
            text-decoration: none;
            color: #fff;
            display: inline-block;
        }

        .edit-btn {
            background: #3276B1;
        }

        .edit-btn:hover {
            background: #225a85;
        }

        .delete-btn {
            background: #d9534f;
        }

        .delete-btn:hover {
            background: #b71c1c;
        }

        .whatsapp-btn {
            background: #25D366;
        }

        .whatsapp-btn:hover {
            background: #1ebe5d;
        }

        .copy-btn {
            background: #6c757d;
        }

        .copy-btn:hover {
            background: #5a6268;
        }

        .send-btn {
            background: #ffa500;
        }

        .send-btn:hover {
            background: #cc8400;
        }

        .no-data {
            text-align: center;
            margin-top: 40px;
            color: #555;
        }

        @media (max-width: 1024px) {
            h1 {
                font-size: 20px;
            }

            table {
                min-width: 850px;
            }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 18px;
            }

            table {
                min-width: 750px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 16px;
            }

            table {
                min-width: 680px;
            }
        }

        /* Animación de campana */
        @keyframes campana-parpadeo {
            0%, 100% { color: #ffc107; transform: scale(1); }
            10% { color: red; transform: scale(1.4); }
            20% { color: #ff5722; transform: scale(1); }
            30% { color: red; transform: scale(1.3); }
            40% { color: #ff5722; transform: scale(1); }
            50% { color: red; transform: scale(1.3); }
            60% { color: #ff5722; transform: scale(1); }
            70% { color: red; transform: scale(1.3); }
            80% { color: #ff5722; transform: scale(1); }
            90% { color: red; transform: scale(1.3); }
        }

        .bell-alert {
            animation: campana-parpadeo 3s ease-in-out 1;
        }

        .bell-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .bell-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const audio = document.getElementById('notificacion-audio');
            const campana = document.getElementById('campana-icono');
            const contador = document.getElementById('contador-pedidos');
            let tituloOriginal = document.title;
            let parpadeoTitulo = null;

            let ultimoID = <?= count($pedidos) ? $pedidos[0]['id'] : 0 ?>;
            let nuevosPedidos = 0;

            setInterval(() => {
                fetch('verificar_nuevos.php?ultimo=' + ultimoID)
                    .then(res => res.json())
                    .then(data => {
                        if (data.nuevo) {
                            audio.pause();
                            audio.currentTime = 0;
                            audio.play();

                            campana.classList.add('bell-alert');
                            setTimeout(() => campana.classList.remove('bell-alert'), 3000);

                            nuevosPedidos++;
                            contador.textContent = nuevosPedidos;
                            contador.style.display = 'inline';
                            
                            if (!parpadeoTitulo) {
                                let visible = true;
                                parpadeoTitulo = setInterval(() => {
                                    document.title = visible ? '🛎️ ¡Nuevo pedido recibido!' : tituloOriginal;
                                    visible = !visible;
                                }, 1000);
                            }
                            ultimoID = data.ultimo;
                        }
                    });
            }, 10000);

            window.resetearContador = () => {
                nuevosPedidos = 0;
                contador.style.display = 'none';
                document.title = tituloOriginal;

                if (parpadeoTitulo) {
                    clearInterval(parpadeoTitulo);
                    parpadeoTitulo = null;
                }
            };
        });
    </script>
</head>

<body>

    <h1>Listado de Pedidos
        <span class="bell-container" onclick="resetearContador()">
            <i id="campana-icono" class="bi bi-bell-fill" style="color:#ffc107; font-size:22px;"></i>
            <span id="contador-pedidos" class="bell-count"></span>
        </span>
    </h1>
    
    <a href="logout.php" style="display:inline-block; padding:8px 12px; background:#d9534f; color:#fff; border-radius:4px; text-decoration:none; float:right; margin-bottom: 15px;">Cerrar Sesión</a>

    <div class="filtros-container">
        <input type="text" id="buscador" placeholder="Buscar por nombre o teléfono...">
        <select id="filtroEstado">
            <option value="">Todos los estados</option>
            <option value="En confirmación">En confirmación</option>
            <option value="Pendiente por enviar">Pendiente por enviar</option>
            <option value="Enviado">Enviado</option>
            <option value="Descartado">Descartado</option>
        </select>
    </div>

    <div class="table-container">
        <?php if (isset($pedidos) && count($pedidos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Color</th>
                        <th>Talla</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Teléfono</th>
                        <th>WhatsApp</th>
                        <th>Dirección</th>
                        <th>Barrio</th>
                        <th>Ciudad</th>
                        <th>Departamento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                        <tr>
                            <td><?= $pedido['id'] ?></td>
                            <td><?= $pedido['fecha'] ?? '' ?></td>
                            <td><?= htmlspecialchars($pedido['color']) ?></td>
                            <td><?= htmlspecialchars($pedido['talla']) ?></td>
                            <td><?= htmlspecialchars($pedido['nombre']) ?></td>
                            <td><?= htmlspecialchars($pedido['cedula']) ?></td>
                            <td><?= htmlspecialchars($pedido['telefono']) ?></td>
                            <td><?= htmlspecialchars($pedido['whatsapp']) ?></td>
                            <td><?= htmlspecialchars($pedido['direccion']) ?></td>
                            <td><?= htmlspecialchars($pedido['barrio']) ?></td>
                            <td><?= htmlspecialchars($pedido['ciudad']) ?></td>
                            <td><?= htmlspecialchars($pedido['departamento']) ?></td>
                            <td>
                                <select class="estado-select <?= strtolower(str_replace(' ', '-', $pedido['estado'])) ?>" data-id="<?= $pedido['id'] ?>">
                                    <option value="En confirmación" <?= $pedido['estado'] === 'En confirmación' ? 'selected' : '' ?>>En confirmación</option>
                                    <option value="Pendiente por enviar" <?= $pedido['estado'] === 'Pendiente por enviar' ? 'selected' : '' ?>>Pendiente por enviar</option>
                                    <option value="Enviado" <?= $pedido['estado'] === 'Enviado' ? 'selected' : '' ?>>Enviado</option>
                                    <option value="Descartado" <?= $pedido['estado'] === 'Descartado' ? 'selected' : '' ?>>Descartado</option>
                                </select>
                            </td>
                            <td>
                                <a href="editar_pedido.php?id=<?= $pedido['id'] ?>" class="action-btn edit-btn">Editar</a>
                                <button class="action-btn delete-btn" onclick="confirmarEliminacion(<?= $pedido['id'] ?>)">Eliminar</button>

                                <?php if ($pedido['whatsapp']): ?>
                                    <a href="https://wa.me/57<?= preg_replace('/\D/', '', $pedido['whatsapp']) ?>?text=<?= urlencode("👋 Hola {$pedido['nombre']}, gracias por tu pedido. Confirmemos tus datos:\n\n✅ Producto: Color {$pedido['color']}, Talla {$pedido['talla']}\n🏙️ Ciudad: {$pedido['ciudad']}\n📍 Dirección: {$pedido['direccion']}\n📞 Teléfono: {$pedido['telefono']}\n\n¿Son correctos los datos? Por favor, confírmame y 📦 despachamos tu pedido hoy mismo.") ?>" target="_blank" class="action-btn whatsapp-btn">WhatsApp</a>
                                <?php endif; ?>

                                <button class="action-btn copy-btn" onclick="copiarFila(this)">Copiar</button>
                                <button class="action-btn copy-btn" onclick="copiarFormatoSheets(this)">Copiar Sheets</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No hay pedidos registrados.</p>
        <?php endif; ?>
    </div>

    <script>
        function confirmarEliminacion(id) {
            if (confirm("¿Seguro que deseas eliminar este pedido?")) {
                window.location.href = 'eliminar_pedido.php?id=' + id;
            }
        }

        function copiarFila(boton) {
            const fila = boton.closest('tr');
            const celdas = fila.querySelectorAll('td');
            const etiquetas = ["ID", "Fecha", "Color", "Talla", "Nombre", "Cédula", "Teléfono", "WhatsApp", "Dirección", "Barrio", "Ciudad", "Departamento"];
            let texto = "";

            celdas.forEach((td, index) => {
                if (index < etiquetas.length) {
                    texto += `*${etiquetas[index]}:* ${td.innerText.trim()}\n`;
                }
            });

            const recaudo = "*RECAUDO: $95.000*";
            texto += `\n${recaudo}`;

            navigator.clipboard.writeText(texto.trim())
                .then(() => alert("Datos copiados en formato WhatsApp ✅"))
                .catch(() => alert("Error al copiar"));
        }

        document.getElementById('buscador').addEventListener('input', filtrarTabla);
        document.getElementById('filtroEstado').addEventListener('change', filtrarTabla);

        function filtrarTabla() {
            const texto = document.getElementById('buscador').value.toLowerCase();
            const estadoFiltro = document.getElementById('filtroEstado').value;
            document.querySelectorAll('tbody tr').forEach(fila => {
                const nombre = fila.children[4].innerText.toLowerCase();
                const telefono = fila.children[6].innerText.toLowerCase();
                const estado = fila.querySelector('.estado-select').value;
                fila.style.display = (nombre.includes(texto) || telefono.includes(texto)) &&
                    (estadoFiltro === "" || estado === estadoFiltro) ? "" : "none";
            });
        }

        document.querySelectorAll('.estado-select').forEach(select => {
            select.addEventListener('change', () => {
                const id = select.getAttribute('data-id');
                const estado = select.value;
                select.className = 'estado-select ' + estado.toLowerCase().replace(/ /g, '-');
                fetch('cambiar_estado.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id=' + encodeURIComponent(id) + '&estado=' + encodeURIComponent(estado)
                    })
                    .then(res => res.text())
                    .then(data => alert(data.trim() === 'ok' ? 'Estado actualizado ✅' : 'Error al actualizar'))
                    .catch(() => alert('Error de conexión'));
            });
        });

        function copiarFormatoSheets(btn) {
            const fila = btn.closest('tr');
            const celdas = fila.querySelectorAll('td');
            let texto = '';
            celdas.forEach((celda, index) => {
                if (index !== 0 && index !== 12 && index < celdas.length - 1) {
                    texto += celda.innerText.trim() + '\t';
                }
            });
            navigator.clipboard.writeText(texto.trim())
                .then(() => alert('Datos copiados en formato Google Sheets ✅'))
                .catch(() => alert('Error al copiar'));
        }
    </script>

</body>
</html>