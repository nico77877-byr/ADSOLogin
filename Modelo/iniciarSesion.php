<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Método no permitido.');
    }
    $datos = json_decode(file_get_contents('php://input'), true);
    if (!is_array($datos) || json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        throw new Exception('Datos inválidos.');
    }
    $email = $datos['email'] ?? '';
    $clave = $datos['clave'] ?? '';
    if(!is_string($email) || !is_string($clave) || empty($email) || empty($clave)) {
        http_response_code(400);
        throw new Exception('Email o clave no válidos.');
    }
    require_once __DIR__ . '/../config/conexion.php';
    $conexion = new Conexion();->conectar();
    $consulta = $conexion->prepare("SELECT * FROM asociados WHERE email = :email");
    $consulta->execute([':email' => $email]);
    $asociado = $consulta->fetch();
    if(!$asociado || !password_verify($clave, $asociado['clave'])) {
        http_response_code(401);
        throw new Exception('Email o clave incorrectos.');
    }
    $_SESSION['rol'] = $asociado['rol'];
    $_SESSION['documento'] = $asociado['documento']; // Guardar el documento del asociado en la sesión
    echo json_encode([
        'success' => true,
         'rol' => $asociado['rol'],
         'nombre' => $asociado['nombres'] . ' ' . $asociado['apellidos'],
    ]);
catch(PDOException $e){
    $errorMessage = "Error: " . $e->getMessage();
    echo json_encode([
        "success" => false, 
        "message" => $errorMessage
    ]);
}   
catch(Exception $e){
    $errorMessage = "Error: " . $e->getMessage();
    echo json_encode([
        "success" => false, 
        "message" => $errorMessage
    ]);
}