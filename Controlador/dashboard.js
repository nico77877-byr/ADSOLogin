document.addEventListener('DOMContentLoaded', () =>{
    const logueado = localStorage.getItem('logueado');
    if(logueado !== 'true'){
        window.location.href = 'login.html';
        return;
    }
    const nombre = localStorage.getItem('nombre');
    const rol = localStorage.getItem('rol');
    document.getElementById('nombreUsuario').textContent = nombre;
    if(rol === 'admin'){
        document.getElementById('moduloAsociados').classList.remove('d-none');
        document.getElementById('seccionAsociado').classList.remove('d-none');
    }
});
function cerrarSesion(){
    localStorage.clear();
    window.location.href = '../Vista/login.html'; 
    
}