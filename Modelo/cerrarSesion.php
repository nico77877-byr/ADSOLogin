<?php
header('Content-Type: application/json; charset=utf-8');
if(SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    //SALTAR A CATH Y MOSTRAR ERROR
    THROW new Exception('Método no permitido.'); 
}
SESSION_START();
$_SESSION = [];
IF(ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,$params["path"], $params["domain"], $params["secure"], $params["httponly"]);  
}
session_destroy();
echo json_encode([
    "success" => true,
    "mensajeP" => "Sesión cerrada correctamente."
]);