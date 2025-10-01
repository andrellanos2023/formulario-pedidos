<?php
session_start();

$config = require 'config.php'; // Traemos usuario y hash

// Inicializar contador de intentos
if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

// Si supera 5 intentos, bloquear
if ($_SESSION['intentos'] >= 5) {
    die('Demasiados intentos. Intenta nuevamente más tarde.');
}

if (isset($_POST['usuario'], $_POST['contrasena'])) {

    $usuarioIngresado = trim($_POST['usuario']);
    $contrasenaIngresada = trim($_POST['contrasena']);

    if (
        $usuarioIngresado === $config['USUARIO'] &&
        password_verify($contrasenaIngresada, $config['CLAVE_HASH'])
    ) {
        $_SESSION['autenticado'] = true;
        $_SESSION['intentos'] = 0; // Reiniciar intentos al ingresar correctamente
        header('Location: ver_pedidos.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
        $_SESSION['intentos']++;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Iniciar Sesión</title>
<style>
* { box-sizing: border-box; }
body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background: linear-gradient(135deg, #f5f7fa, #e2e6ec);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.login-container {
    background: #fff;
    padding: 30px 20px;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 350px;
    text-align: center;
    opacity: 0;
    transform: translateY(-20px);
    animation: fadeIn 0.8s ease forwards;
}
@keyframes fadeIn {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.logo {
    margin-bottom: 15px;
}
.logo img {
    max-width: 100px;
}
h2 {
    margin-bottom: 20px;
    color: #000;
}
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
    transition: border-color 0.3s;
}
input:focus {
    outline: none;
    border-color: #000;
    box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
}
button {
    width: 100%;
    padding: 12px;
    background: #000;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s;
}
button:hover {
    background: #333;
}
.error {
    color: red;
    margin-bottom: 10px;
}
.show-password {
    display: flex;
    align-items: center;
    font-size: 14px;
    margin-top: -5px;
    margin-bottom: 15px;
    color: #555;
    user-select: none;
}
.show-password input[type="checkbox"] {
    margin-right: 6px;
    transform: scale(1.1);
    cursor: pointer;
    accent-color: #000;
    outline: none;
    box-shadow: none;
}
.show-password input[type="checkbox"]:focus {
    outline: none;
    box-shadow: none;
}
@media (max-width: 480px) {
    .login-container {
        padding: 20px;
    }
    h2 {
        font-size: 20px;
    }
    button {
        font-size: 15px;
    }
}
</style>
</head>
<body>

<div class="login-container">
    <div class="logo">
        <img src="./redondo logo-Photoroom.png" alt="Logo"> 
    </div>
    
    <h2>Acceso</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <input type="text" name="usuario" placeholder="Usuario" required value="<?= isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : '' ?>">
        <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required>
        
        <div class="show-password">
            <input type="checkbox" id="mostrarPass" onclick="togglePassword()">
            <label for="mostrarPass">Mostrar contraseña</label>
        </div>

        <button type="submit">Ingresar</button>
    </form>
</div>

<script>
function togglePassword() {
    const passInput = document.getElementById('contrasena');
    passInput.type = passInput.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>
