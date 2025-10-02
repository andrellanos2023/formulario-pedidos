<!DOCTYPE html>
<html>
<head>
    <title>Test Pedido</title>
</head>
<body>
    <h2>Test de Pedido</h2>
    <form action="grabar.php" method="POST">
        <input type="hidden" name="color" value="KIT DETOX">
        <input type="hidden" name="talla" value="ÚNICA">
        
        <input type="text" name="nombre" placeholder="Nombre" value="JUAN PEREZ" required><br>
        <input type="text" name="cedula" placeholder="Cédula" value="123456789" required><br>
        <input type="text" name="telefono" placeholder="Teléfono" value="3001234567" required><br>
        <input type="text" name="whatsapp" placeholder="WhatsApp" value="3001234567" required><br>
        <input type="text" name="direccion" placeholder="Dirección" value="Calle 123" required><br>
        <input type="text" name="barrio" placeholder="Barrio" value="Centro" required><br>
        <input type="text" name="ciudad" placeholder="Ciudad" value="Bogotá" required><br>
        <input type="text" name="departamento" placeholder="Departamento" value="Bogotá D.C." required><br>
        
        <button type="submit">Enviar Pedido de Prueba</button>
    </form>
</body>
</html>