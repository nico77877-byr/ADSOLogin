<?php

require_once __DIR__. '/../Config/conexion.php';

class Asociado {
    private  $documento;
    private  $nombres;
    private  $apellidos;
    private  $fechaNacimiento;
    private  $genero;
    private  $direccionResidencia;
    private  $ciudad; 
    private  $telefono; 
    private  $email;
    private  $clave;
     // Nueva propiedad para la contraseña
    
    private ?PDO $conexion;

    public function __construct($datos) {
        // Mapeamos exactamente los nombres que envía tu formulario HTML
        $this->documento = $datos['documento'] ?? '';
        $this->nombres = $datos['nombres'] ?? '';        // Viene del HTML como 'nombre'
        $this->apellidos = $datos['apellidos'] ?? '';    // Viene del HTML como 'apellido'
        $this->fechaNacimiento =($datos['fechaNacimiento'] ?? 'now');       
        $this->genero = $datos['genero'] ?? '';
        $this->direccionResidencia = $datos['direccionResidencia'] ?? ''; // Viene del HTML como 'direccion'
        $this->ciudad = $datos['ciudad'] ?? '';
        $this->telefono = (string) ($datos['telefono'] ?? '');
        $this->email = $datos['email'] ?? '';
        $this->clave = password_hash($datos['clave'] ?? '', PASSWORD_DEFAULT);


        $conexionBD = new Conexion();
        $this->conexion = $conexionBD->conectar();
    }

    public function guardarAsociado() {
        // Los nombres de las columnas coinciden exactamente con tu tabla de la imagen
        $sql = "INSERT INTO asociados (documento, nombres, apellidos, fechaNacimiento, genero, direccionResidencia, ciudad, telefono, email, clave) 
                VALUES (:documento, :nombres, :apellidos, :fechaNacimiento, :genero, :direccionResidencia, :ciudad, :telefono, :email, :clave)";

        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
            ':documento' => $this->documento,
            ':nombres' => $this->nombres,
            ':apellidos' => $this->apellidos,
            ':fechaNacimiento' => $this->fechaNacimiento,
            ':genero' => $this->genero,
            ':direccionResidencia' => $this->direccionResidencia,
            ':ciudad' => $this->ciudad,
            ':telefono' => $this->telefono,
            ':email' => $this->email,
            ':clave' => $this->clave
        ]);
    }
}