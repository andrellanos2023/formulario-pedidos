<?php
session_start();
if (isset($_SESSION['usuario'])) {
  header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="⭐ Calzado Cucuta Store | Compra Lo Ya ⭐ Zapatos de moda para hombre y mujer. ✅ Envíos rápidos, ✅ calidad garantizada. ¡Ofertas exclusivas! 👟✨">
  <link rel="icon" href="./redondo logo-Photoroom.png" type="image/x-icon">
  <title>KIT DETOX - Pago Contra Entrega</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">

</head>

<body>

  <header> PAGAS AL RECIBIR 🔥</header>

  <div class="container" id="container">

    <!-- Imágenes -->
    <div class="images">
      <img id="mainImage" src="./assets/Diseño sin título.gif" class="main-image" alt="BOTA UNDER">

      <div class="thumbs">
        <img src="./assets/detox.jpeg" alt="Miniatura 1" onclick="changeMainImage(this.src)">
        <img src="./assets/family.jpeg" alt="Miniatura 2" onclick="changeMainImage(this.src)">
        <img src="./assets/bene.jpeg" alt="Miniatura 3" onclick="changeMainImage(this.src)">
        <img src="./assets/testi.jpeg" alt="Miniatura 4" onclick="changeMainImage(this.src)">
        <img src="./assets/testi 2.jpeg" alt="Miniatura 5" onclick="changeMainImage(this.src)">
      </div>
    </div>

    <!-- Detalles del producto y formulario -->
    <div class="details">
      <h1>KIT DETOX RENOVADOR</h1>
      <p class="price">$85.100 <span class="shipping-badge"> + ENVÍO </span></p>
      <p class="price">50% de Descuento en el envio 
      <span class="shipping-badge"> PAGANDO DE CONTADO</span></p>
      <div class="card shadow p-4 border-0 bg-light">
        <form onsubmit="enviarPedido(event)" action="./grabar.php" method="POST">

          <div class="label"></div>
          <div class="options">
            
          </div>
          <input type="hidden" name="color" id="color">

          <div class="price">Limpia tu colon, elimina toxinas, mejora el tránsito intestinal, potencializa la pérdida de peso y combate el estreñimiento.
          <div class="price"> ⭐⭐⭐⭐⭐</div>
          <div class="options">
            
          <input type="hidden" name="talla" id="talla">

          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="form-control" name="nombre" placeholder="Tu Nombre Completo" required>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-credit-card-fill"></i></span>
            <input type="number" class="form-control" name="cedula" placeholder="Cédula" required>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
            <input type="number" class="form-control" name="telefono" placeholder="Teléfono" required>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
            <input type="number" class="form-control" name="whatsapp" placeholder="WhatsApp" required>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
            <input type="text" class="form-control" name="direccion" placeholder="Dirección de entrega" required>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-house-door-fill"></i></span>
            <input type="text" class="form-control" name="barrio" placeholder="Barrio o Urbanización" required>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-map-fill"></i></span>
            <select class="form-control" id="departamento" name="departamento" required onchange="cargarCiudades()">
              <option value="">Seleccione un departamento</option>
            </select>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text"><i class="bi bi-building"></i></span>
            <select class="form-control" id="ciudad" name="ciudad" required>
              <option value="">Seleccione una ciudad</option>
            </select>
          </div>

          <button type="submit" class="whatsapp-button">
            <i class="fas fa-shopping-bag me-2"></i> ENVIAR DATOS
          </button>

          <p class="note">
            <i class="fas fa-truck me-2"></i> ENVÍO GRATIS A NIVEL NACIONAL
          </p>
        </form>
      </div>


      <div style="margin-top:20px">
        <h3><i class="fas fa-fire me-2"></i> ¡Evita la indigestion y desintoxica el colon!</h3>
        <ul class="benefits">
          <li><i class="fas fa-check-circle me-2"></i> Previene y alivia los trasnstornos digestivos</li>
          <li><i class="fas fa-check-circle me-2"></i> Estimula la actividad del colon gracias a su efecto laxante</li>
          <li><i class="fas fa-check-circle me-2"></i> Eficaz en caso de extreñimiento o digestion deficiente</li>
          <li><i class="fas fa-check-circle me-2"></i> Pago contraentrega disponible</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Texto final fuera del formulario -->
  <div id="textofinal">
    <div class="confirmation-content">
      <i class="fas fa-check-circle success-icon"></i>
      <h2>¡Tu pedido ha sido registrado con ÉXITO!</h2>
      <p>Te escribiremos vía WhatsApp para confirmar tu pedido.
        El tiempo estimado de entrega es de 3 a 5 dias Habiles.
      </p>
      <button onclick="hideConfirmation()">Aceptar</button>
    </div>
  </div>

  <!-- Botón de WhatsApp flotante -->
  <a class="ao" href="https://wa.me/573127495741?text=Hola,%20vengo%20de%20la%20pagina%20web,%20deseo%20el%20KIT%20DETOX" target="_blank">
    <img class="wh pulse-animation" src="./assets/whatsapp.vector.webp" alt="whatsapp" />
  </a>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="app.js"></script>


</body>

</html>