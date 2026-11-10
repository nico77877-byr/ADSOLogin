<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/asociados.php';

try {
    // Validar que el método sea POST; si no lo es, lanzar excepción
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Método no permitido.');
    }

    // Leer el contenido JSON de la solicitud enviada por fetch
    $json = file_get_contents('php://input');

    // Decodificar el JSON en un array asociativo
    $datos = json_decode($json, true);
    echo json_encode($datos); // Para depuración, puedes eliminarlo después

    // Validar que los datos se hayan pasado a un array
    if (!is_array($datos) || json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        throw new Exception('Datos inválidos.');
    }

    $campos = ['documento', 'nombres', 'apellidos', 'fechaNacimiento', 'genero', 'direccionResidencia', 'ciudad', 'telefono', 'email', 'clave'];
    
    // Validar que todos los campos obligatorios estén presentes y no estén vacíos
    foreach ($campos as $campo) {
        if (!isset($datos[$campo]) || empty($datos[$campo])) {
            http_response_code(400);
            throw new Exception("El campo: '$campo' es obligatorio.");
        }
    }

    // Validar el formato del campo email
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        throw new Exception("El campo: 'email' no es válido.");
    }

    $asociado = new Asociado($datos);

    $resultado = $asociado->guardarAsociado();

    if ($resultado) {
        http_response_code(201);
        echo json_encode(['success' => true, 'mensaje' => 'Asociado guardado correctamente.']);
    } else {
        http_response_code(500);
        throw new Exception('Error al guardar el asociado.');
    }

} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // Código de error para violación de clave única
        http_response_code(409);
        echo json_encode(["success" => false, "mensaje" => "El documento ya existe"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "mensaje" => "Error en la base de datos: " . $e->getMessage()]);
    }
} catch (Exception $e) {
    // Si ya se había asignado un código 400 o 405 arriba, lo respetamos, de lo contrario será 500
    if (http_response_code() === 200) {
        http_response_code(500);
    }
    echo json_encode(["success" => false, "mensaje" => $e->getMessage()]);
}