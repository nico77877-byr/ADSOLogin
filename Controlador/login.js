const usuarios = [
    { usuario: 'admin@gmail.com', clave: '123', rol: 'admin' ,nombre: 'AdministradorRR'},
    { usuario: 'asociado@gmail.com', clave: '456', rol: 'asociado' ,nombre: 'Asociado John'}
];

document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const usuarioInput = document.getElementById('email').value;
    const claveInput = document.getElementById('password').value;

    const usuarioEncontrado = usuarios.find(u => u.usuario === usuarioInput && u.clave === claveInput);
    if (usuarioEncontrado) {

            localStorage.setItem('logueado','true');
            localStorage.setItem('rol', usuarioEncontrado.rol);
            localStorage.setItem('nombre', usuarioEncontrado.nombre);
            window.location.href = 'dashboaard.html';
        
    } else {
        alert('Usuario o clave incorrectos');
    }
}); 