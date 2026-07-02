<?php
require_once 'config/Conexion.php';

class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    public function listarTodos() {
        $query = "SELECT u.id_usuario, u.nombres, u.apellidos, u.correo, u.id_rol, u.estado, u.documento, u.tipo_vinculacion, u.secretaria
                  FROM " . $this->table_name . " u 
                  ORDER BY u.nombres ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_usuario) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($correo, $password) {
        $query = "SELECT u.id_usuario, u.nombres, u.apellidos, u.password, u.estado, u.id_rol, r.nombre_rol 
                  FROM " . $this->table_name . " u
                  INNER JOIN roles r ON u.id_rol = r.id_rol
                  WHERE u.correo = :correo LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":correo", $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['estado'] == 'Activo' && password_verify($password, $row['password'])) {
                return $row;
            }
        }
        return false;
    }
    
    public function registrar($datos) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (id_rol, tipo_persona, tipo_documento, documento, nombres, apellidos, tipo_vinculacion, secretaria, correo, password, estado) 
                      VALUES (:id_rol, 'Natural', 'CC', :documento, :nombres, :apellidos, 'Carrera Administrativa', '', :correo, :password, 'Activo')";
            
            $stmt = $this->conn->prepare($query);
            $password_encriptada = password_hash($datos['password'], PASSWORD_BCRYPT);

            return $stmt->execute([
                ':id_rol'    => $datos['id_rol'],
                ':documento' => $datos['documento'],
                ':nombres'   => $datos['nombres'],
                ':apellidos' => $datos['apellidos'],
                ':correo'    => $datos['correo'],
                ':password'  => $password_encriptada
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }
    
    public function actualizar($datos) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET nombres = :nombres, apellidos = :apellidos, correo = :correo, id_rol = :id_rol, estado = :estado
                      WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':id_usuario' => $datos['id_usuario'],
                ':nombres'    => $datos['nombres'],
                ':apellidos'  => $datos['apellidos'],
                ':correo'     => $datos['correo'],
                ':id_rol'     => $datos['id_rol'],
                ':estado'     => $datos['estado']
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }

    public function eliminar($id_usuario) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_usuario", $id_usuario);
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function cambiarPassword($id_usuario, $nueva_password) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET password = :password 
                      WHERE id_usuario = :id_usuario";
            
            $stmt = $this->conn->prepare($query);
            $password_encriptada = password_hash($nueva_password, PASSWORD_BCRYPT);

            return $stmt->execute([
                ':password'   => $password_encriptada,
                ':id_usuario' => $id_usuario
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }
}
