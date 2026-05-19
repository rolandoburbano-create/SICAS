<?php
require_once 'config/Conexion.php';

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    public function login($correo, $password) {
        // Consulta segura con INNER JOIN para traer también el nombre del rol
        $query = "SELECT u.id_usuario, u.nombres, u.apellidos, u.password, u.estado, r.nombre_rol 
                  FROM " . $this->table_name . " u
                  INNER JOIN roles r ON u.id_rol = r.id_rol
                  WHERE u.correo = :correo LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo", $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar si el usuario está Activo y si la contraseña coincide
            if ($row['estado'] == 'Activo' && password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }
    
    // Registrar un nuevo usuario/supervisor
    public function registrar($datos) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (id_rol, tipo_persona, tipo_documento, documento, nombres, apellidos, tipo_vinculacion, secretaria, correo, password) 
                    VALUES 
                    (:id_rol, :tipo_persona, :tipo_doc, :doc, :nombres, :apellidos, :vinculacion, :secretaria, :correo, :pass)";
            
            $stmt = $this->conn->prepare($query);
            
            // Encriptar contraseña por defecto (puede ser el documento)
            $password_hash = password_hash($datos['documento'], PASSWORD_BCRYPT);

            return $stmt->execute([
                ':id_rol' => $datos['id_rol'],
                ':tipo_persona' => $datos['tipo_persona'],
                ':tipo_doc' => $datos['tipo_documento'],
                ':doc' => $datos['documento'],
                ':nombres' => $datos['nombres'],
                ':apellidos' => $datos['apellidos'],
                ':vinculacion' => $datos['tipo_vinculacion'],
                ':secretaria' => $datos['secretaria'],
                ':correo' => $datos['correo'],
                ':pass' => $password_hash
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>