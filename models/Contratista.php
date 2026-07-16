<?php
require_once 'config/Conexion.php';

class Contratista {
    private $conn;
    private $table_name = "contratistas";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    public function listarTodos() {
        $query = "SELECT id_contratista, documento, nombre_razon_social FROM " . $this->table_name . " ORDER BY nombre_razon_social ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodosCompleto() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre_razon_social ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($termino) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE documento LIKE ? 
                     OR nombre_razon_social LIKE ? 
                  ORDER BY nombre_razon_social ASC";
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->execute([$termino, $termino]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_contratista) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_contratista = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_contratista);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($datos, $id_usuario) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " 
                    (tipo_persona, tipo_documento, documento, digito_verificacion, nombre_razon_social, genero, direccion, telefono, correo, entidad_bancaria, tipo_cuenta, numero_cuenta) 
                    VALUES 
                    (:tipo_persona, :tipo_doc, :doc, :dv, :nombre, :genero, :direccion, :telefono, :correo, :entidad_bancaria, :tipo_cuenta, :numero_cuenta)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':tipo_persona' => $datos['tipo_persona'],
                ':tipo_doc' => $datos['tipo_documento'],
                ':doc' => $datos['documento'],
                ':dv' => $datos['digito_verificacion'] ?? null,
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

    public function actualizar($datos) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                      SET tipo_persona = :tipo_persona, tipo_documento = :tipo_documento, documento = :documento, 
                          digito_verificacion = :dv,
                          nombre_razon_social = :nombre_razon_social, genero = :genero, direccion = :direccion, 
                          telefono = :telefono, correo = :correo, entidad_bancaria = :entidad_bancaria, 
                          tipo_cuenta = :tipo_cuenta, numero_cuenta = :numero_cuenta 
                      WHERE id_contratista = :id_contratista";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':id_contratista' => $datos['id_contratista'],
                ':tipo_persona' => $datos['tipo_persona'],
                ':tipo_documento' => $datos['tipo_documento'],
                ':documento' => $datos['documento'],
                ':dv' => $datos['digito_verificacion'] ?? null,
                ':nombre_razon_social' => $datos['nombre_razon_social'],
                ':genero' => $datos['genero'],
                ':direccion' => $datos['direccion'],
                ':telefono' => $datos['telefono'],
                ':correo' => $datos['correo'],
                ':entidad_bancaria' => $datos['entidad_bancaria'],
                ':tipo_cuenta' => $datos['tipo_cuenta'],
                ':numero_cuenta' => $datos['numero_cuenta']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id_contratista) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_contratista = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id_contratista);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
