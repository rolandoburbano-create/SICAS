<?php
require_once 'config/Conexion.php';

class Contratista {
    private $conn;
    private $table_name = "contratistas";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    // Listar todos los contratistas para el formulario (Select)
    public function listarTodos() {
        $query = "SELECT id_contratista, documento, nombre_razon_social FROM " . $this->table_name . " ORDER BY nombre_razon_social ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

// Registrar un nuevo contratista de forma segura con todos los campos
    public function registrar($datos, $id_usuario) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " 
                    (tipo_persona, tipo_documento, documento, nombre_razon_social, genero, direccion, telefono, correo, entidad_bancaria, tipo_cuenta, numero_cuenta) 
                    VALUES 
                    (:tipo_persona, :tipo_doc, :doc, :nombre, :genero, :direccion, :telefono, :correo, :entidad_bancaria, :tipo_cuenta, :numero_cuenta)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tipo_persona' => $datos['tipo_persona'],
                ':tipo_doc' => $datos['tipo_documento'],
                ':doc' => $datos['documento'],
                ':nombre' => $datos['nombre_razon_social'],
                ':genero' => $datos['genero'],
                ':direccion' => $datos['direccion'],
                ':telefono' => $datos['telefono'],
                ':correo' => $datos['correo'],
                ':entidad_bancaria' => $datos['entidad_bancaria'],
                ':tipo_cuenta' => $datos['tipo_cuenta'],
                ':numero_cuenta' => $datos['numero_cuenta']
            ]);

            $id_contratista_nuevo = $this->conn->lastInsertId();

            // Registrar en Auditoría
            $queryAuditoria = "INSERT INTO auditoria (id_usuario, accion, tabla_afectada, registro_id, detalles_nuevos, direccion_ip) 
                               VALUES (:user, 'INSERT', 'contratistas', :reg_id, :detalles, :ip)";
            $stmtAuditoria = $this->conn->prepare($queryAuditoria);
            $stmtAuditoria->execute([
                ':user' => $id_usuario,
                ':reg_id' => $id_contratista_nuevo,
                ':detalles' => json_encode($datos),
                ':ip' => $_SERVER['REMOTE_ADDR']
            ]);

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            if ($e->getCode() == 23000) {
                return "duplicado"; 
            }
            return false;
        }
    }    
}
?>