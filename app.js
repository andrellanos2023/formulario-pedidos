document.addEventListener("DOMContentLoaded", function () {
  const seccionOcultarTextoFinal = document.getElementById("textofinal");
  const seccionContainer = document.getElementById("container");

  if (seccionOcultarTextoFinal) {
    seccionOcultarTextoFinal.style.display = "none";
  }

  // Datos simulados de departamentos y ciudades
  const departamentosYciudades = {
    "Amazonas": ["Leticia", "Puerto Nariño", "El Encanto", "La Chorrera", "La Pedrera", "La Victoria", "Mirití-Paraná", "Puerto Alegría", "Puerto Arica", "Puerto Santander", "Tarapacá"],
    "Antioquia": ["Medellín", "Abejorral", "Alejandría", "Amagá", "Amalfi", "Andes", "Angelópolis", "Angostura", "Anorí", "Anzá", "Apartadó", "Arboletes", "Argelia", "Armenia", "Barbosa", "Bello", "Belmira", "Betania", "Betulia", "Briceño", "Buriticá", "Cáceres", "Caicedo", "Caldas", "Campamento", "Caracolí", "Caramanta", "Carepa", "Carmen de Viboral", "Carolina", "Caucasia", "Chigorodó", "Cisneros", "Ciudad Bolívar", "Cocorná", "Concepción", "Concordia", "Copacabana", "Dabeiba", "Donmatías", "Ebéjico", "El Bagre", "El Carmen de Viboral", "El Santuario", "Entrerríos", "Envigado", "Fredonia", "Frontino", "Giraldo", "Girardota", "Gómez Plata", "Granada", "Guadalupe", "Guarne", "Guatapé", "Heliconia", "Hispania", "Itagüí", "Ituango", "Jardín", "Jericó", "La Ceja", "La Estrella", "La Pintada", "La Unión", "Liborina", "Maceo", "Marinilla", "Montebello", "Murindó", "Mutatá", "Nariño", "Nechí", "Necoclí", "Olaya", "Peñol", "Peque", "Pueblorrico", "Puerto Berrío", "Puerto Nare", "Puerto Triunfo", "Remedios", "Retiro", "Rionegro", "Sabanalarga", "Sabaneta", "Salgar", "San Andrés de Cuerquía", "San Carlos", "San Francisco", "San Jerónimo", "San José de la Montaña", "San Juan de Urabá", "San Luis", "San Pedro de los Milagros", "San Pedro de Urabá", "San Rafael", "San Roque", "San Vicente", "Santa Bárbara", "Santa Fe de Antioquia", "Santa Rosa de Osos", "Santo Domingo", "Segovia", "Sonsón", "Sopetrán", "Támesis", "Tarazá", "Tarso", "Titiribí", "Toledo", "Turbo", "Uramita", "Urrao", "Valdivia", "Valparaíso", "Vegachí", "Venecia", "Vigía del Fuerte", "Yalí", "Yarumal", "Yolombó", "Yondó", "Zaragoza"],
    // ... (todos los demás departamentos y ciudades)
  };

  // Función para cargar departamentos al iniciar
  function cargarDepartamentos() {
    const selectDepartamento = document.getElementById('departamento');
    const departamentos = Object.keys(departamentosYciudades).sort();
    
    selectDepartamento.innerHTML = '<option value="">Seleccione un departamento</option>';
    
    departamentos.forEach(depto => {
      const option = document.createElement('option');
      option.value = depto;
      option.textContent = depto;
      selectDepartamento.appendChild(option);
    });
  }

  // Función para cargar ciudades según departamento seleccionado
  function cargarCiudades() {
    const selectDepartamento = document.getElementById('departamento');
    const selectCiudad = document.getElementById('ciudad');
    const departamentoSeleccionado = selectDepartamento.value;
    
    selectCiudad.innerHTML = '<option value="">Seleccione una ciudad</option>';
    
    if (departamentoSeleccionado) {
      const ciudades = departamentosYciudades[departamentoSeleccionado].sort();
      
      ciudades.forEach(ciudad => {
        const option = document.createElement('option');
        option.value = ciudad;
        option.textContent = ciudad;
        selectCiudad.appendChild(option);
      });
    }
  }

  // Llamar a cargarDepartamentos al cargar la página
  cargarDepartamentos();

  // Función para cambiar imagen principal
  function changeMainImage(src) {
    document.getElementById("mainImage").src = src;
  }

  async function enviarPedido(event) {
    event.preventDefault();

    const form = event.target;

    // Obtener los datos
    const nombre = form.querySelector('[name="nombre"]').value.trim();
    const cedula = form.querySelector('[name="cedula"]').value.trim();
    const telefono = form.querySelector('[name="telefono"]').value.trim();
    const whatsapp = form.querySelector('[name="whatsapp"]').value.trim();
    const direccion = form.querySelector('[name="direccion"]').value.trim();
    const barrio = form.querySelector('[name="barrio"]').value.trim();
    const ciudad = form.querySelector('[name="ciudad"]').value.trim();
    const departamento = form.querySelector('[name="departamento"]').value.trim();

    // Validaciones
    if (!nombre || !cedula || !telefono || !whatsapp || !direccion || !barrio || !ciudad || !departamento) {
      alert("Por favor completa todos los campos.");
      return;
    }

    // Redirigir a WhatsApp
    const mensaje = `Hola, este es mi pedido:\n🧴 *KIT DETOX RENOVADOR*\n🧑 Nombre: ${nombre}\n🆔 Cédula: ${cedula}\n📞 Teléfono: ${telefono}\n📱 WhatsApp: ${whatsapp}\n🎨 Producto: KIT DETOX\n📏 Talla: ÚNICA\n📍 Dirección: ${direccion}\n🏘️ Barrio: ${barrio}\n🏙️ Ciudad: ${ciudad}\n🌎 Departamento: ${departamento}`;
    const url = `https://wa.me/573132731250?text=${encodeURIComponent(mensaje)}`;
    window.open(url, '_blank');

    // Evento de conversión TikTok
    if (typeof ttq !== "undefined") {
      ttq.track("CompletePayment");
    }

    // Mostrar loading en el botón
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ENVIANDO...';
    submitBtn.disabled = true;

    try {
      const formData = new FormData(form);
      
      const response = await fetch("grabar.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        // DEBUG: Verificar elementos
        console.log("✅ Pedido guardado, mostrando confirmación...");
        
        const seccionContainer = document.getElementById("container");
        const seccionOcultarTextoFinal = document.getElementById("textofinal");
        
        console.log("Container encontrado:", !!seccionContainer);
        console.log("TextoFinal encontrado:", !!seccionOcultarTextoFinal);
        
        if (seccionContainer && seccionOcultarTextoFinal) {
          // Ocultar container y mostrar confirmación
          seccionContainer.style.display = "none";
          seccionOcultarTextoFinal.style.display = "flex";
          console.log("✅ Confirmación mostrada correctamente");
        } else {
          console.log("❌ No se encontraron los elementos para mostrar confirmación");
          alert("✅ Pedido enviado correctamente. Te contactaremos por WhatsApp.");
        }
        
        form.reset();
      } else {
        alert("Error: " + (data.error || 'No se pudo guardar el pedido'));
      }
    } catch (error) {
      console.error("Error completo:", error);
      alert("Error de conexión. Por favor intenta nuevamente.");
    } finally {
      // Restaurar el botón
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
    }
  }

  // Función para recargar la página al hacer click en Aceptar
  function hideConfirmation() {
    console.log("Ocultando confirmación...");
    
    // Ocultar mensaje de confirmación
    const textofinal = document.getElementById("textofinal");
    if (textofinal) {
      textofinal.style.display = "none";
    }
    
    // Mostrar formulario nuevamente
    const container = document.getElementById("container");
    if (container) {
      container.style.display = "flex";
    }
    
    // Recargar la página
    window.location.reload();
  }

  // Exponer funciones necesarias al ámbito global
  window.changeMainImage = changeMainImage;
  window.enviarPedido = enviarPedido;
  window.cargarCiudades = cargarCiudades;
  window.hideConfirmation = hideConfirmation;
});