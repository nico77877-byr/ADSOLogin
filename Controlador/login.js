const formulario = document.getElementById("formlogin");
formulario.addEventListener("submit", async function (e) {
e.preventDefault();

    const boton = formulario.querySelector('button[type="submit"]');
    const mensaje = formulario.getElementById("mensajeLogin");

    boton.disabled = true; 

    try{
        const respuesta = await fetch(formulario.action, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(Object.fromEntries(new FormData(formulario).entries()),
            ),
        });

        const resultado = await respuesta.json();

        if (!resultado.success){
            mensaje.textContent = 
            "No fue posible iniciar sesión. Por favor, verifica tus credenciales.";
            boton.disabled = false;
            return;
        }

        localStorage.setItem("logueado", "true");
        localStorage.setItem("rol", resultado.rol);
        localStorage.setItem("nombre", resultado.nombre);

        window.location.href = 'dashboard.html';
      }catch(error){
        console.error("Error al iniciar sesión:", error);
        mensaje.textContent = 
            "Ocurrió un error al iniciar sesión. Por favor, inténtalo de nuevo más tarde.";
      } finally {
        boton.disabled = false; 
      }
    });