<?php
// Primera base (donde está el pedido original)
$host1 = "localhost";
$user1 = "root";
$pass1 = "";
$db1 = "formulario_db_qpn5";

// Segunda base (donde quieres enviar el pedido)
$host2 = "localhost";
$user2 = "root";
$pass2 = "";
$db2 = "otra_base";

if (isset($_POST['id'])) {
    try {
        // Conexión a primera base
        $conn1 = new PDO("mysql:host=$host1;dbname=$db1;charset=utf8", $user1, $pass1);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Conexión a segunda base
        $conn2 = new PDO("mysql:host=$host2;dbname=$db2;charset=utf8", $user2, $pass2);
        $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener pedido de la primera base
        $stmt = $conn1->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pedido) {
            // Insertar en la segunda base
            $sql = "INSERT INTO pedidos (
                        color, talla, nombre, cedula, telefono, whatsapp,
                        direccion, barrio, ciudad, departamento, ip, user_agent
                    ) VALUES (
                        :color, :talla, :nombre, :cedula, :telefono, :whatsapp,
                        :direccion, :barrio, :ciudad, :departamento, :ip, :user_agent
                    )";

            $stmt2 = $conn2->prepare($sql);
            $stmt2->execute([
                ':color'        => $pedido['color'],
                ':talla'        => $pedido['talla'],
                ':nombre'       => $pedido['nombre'],
                ':cedula'       => $pedido['cedula'],
                ':telefono'     => $pedido['telefono'],
                ':whatsapp'     => $pedido['whatsapp'],
                ':direccion'    => $pedido['direccion'],
                ':barrio'       => $pedido['barrio'],
                ':ciudad'       => $pedido['ciudad'],
                ':departamento' => $pedido['departamento'],
                ':ip'           => $pedido['ip'],
                ':user_agent'   => $pedido['user_agent']
            ]);
            echo "ok";
        } else {
            echo "pedido_no_encontrado";
        }
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();
    }
} else {
    echo "sin_datos";
}
