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
    
    // Valores fijos - NO validar como obligatorios
    const talla = "ÚNICA";
    const color = "KIT DETOX";

    // Validaciones (SOLO campos que el usuario debe llenar)
    if (!nombre || !cedula || !telefono || !whatsapp || !direccion || !barrio || !ciudad || !departamento) {
        alert("Por favor completa todos los campos.");
        return;
    }

    // Redirigir a WhatsApp
    const mensaje = `Hola, este es mi pedido:\n🧴 *KIT DETOX RENOVADOR*\n🧑 Nombre: ${nombre}\n🆔 Cédula: ${cedula}\n📞 Teléfono: ${telefono}\n📱 WhatsApp: ${whatsapp}\n🎨 Producto: ${color}\n📏 Talla: ${talla}\n📍 Dirección: ${direccion}\n🏘️ Barrio: ${barrio}\n🏙️ Ciudad: ${ciudad}\n🌎 Departamento: ${departamento}`;
    const url = `https://wa.me/573132731250?text=${encodeURIComponent(mensaje)}`;
    window.open(url, '_blank');

    // Enviar a la base de datos sin recargar
    try {
        const formData = new FormData(form);
        
        // Asegurar que se envíen los valores fijos
        formData.set('color', color);
        formData.set('talla', talla);
        
        const response = await fetch("grabar.php", {
            method: "POST",
            body: formData,
        });

        const data = await response.json();

        if (data.success) {
            // Ocultar formulario y mostrar confirmación
            const seccionContainer = document.getElementById("container");
            const seccionOcultarTextoFinal = document.getElementById("textofinal");
            
            if (seccionContainer && seccionOcultarTextoFinal) {
                seccionContainer.style.display = "none";
                seccionOcultarTextoFinal.style.display = "flex";
            }
            
            form.reset();
        } else {
            alert("Hubo un problema al guardar el pedido: " + (data.error || 'Error desconocido'));
        }
    } catch (error) {
        console.error(error);
        alert("Error en el envío.");
    }
}