const form = document.getElementById("FormRegistro");

form.addEventListener("submit", async function (event) {
    // Evitar que se recargue la página al enviar el formulario
    event.preventDefault();

    // Bloquear el botón de guardar
    const btnGuardar = form.querySelector("button[type='submit']");

    // Leer campos del formulario y los convierte en un objeto
    const asociado = Object.fromEntries(new FormData(form).entries());

    // 1. Corregido el acceso a 'clave-confirmar' con corchetes
    if (asociado.clave !== asociado['clave-confirmar']) {
        const mensajeP = document.getElementById("mensaje_registro");
        mensajeP.textContent = "Las contraseñas no coinciden.";
        mensajeP.dataset.tipo = "error";
        return; // Detener la ejecución si las contraseñas no coinciden
    }

    delete asociado["clave-confirmar"]; // Eliminar la propiedad del objeto antes de enviarlo

    btnGuardar.disabled = true; // Desactivar el botón mientras se procesa

    try {
        const response = await fetch(form.action, { 
            method: 'POST', 
            headers: { 'content-type': 'application/json' }, 
            body: JSON.stringify(asociado) 
        });

        const resultado = await response.json(); // Convertir respuesta a objeto JS

        const mensajeP = document.getElementById("mensaje_registro");
        mensajeP.textContent = resultado.mensaje; // Mostrar mensaje del servidor
        
        // Asignar el tipo de mensaje según si fue exitoso o no
        if (resultado.success) {
            mensajeP.dataset.tipo = "exito";
            form.reset(); // Limpiar el formulario

            // 2. Redirigir al login después de 2 segundos si el registro fue exitoso
            setTimeout(() => {
                window.location.href = "login.html"; // Ajusta la ruta si tu login está en otra carpeta
            }, 2000);
        } else {
            mensajeP.dataset.tipo = "error";
        }

    } catch (error) {
        console.error("Error al guardar el asociado:", error);
        const mensajeP = document.getElementById("mensaje_registro");
        mensajeP.textContent = "Ocurrió un error de red al intentar guardar.";
        mensajeP.dataset.tipo = "error";
    } finally {
        btnGuardar.disabled = false; // Reactivar el botón pase lo que pase
    }
});