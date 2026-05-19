<?php
require_once 'config/Conexion.php';

class ControlPlataforma {
    private $conn;
    private $table_name = "control_plataformas";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    // Obtener el estado actual del contrato en las plataformas
    public function obtenerPorContrato($id_contrato) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_contrato = :id_contrato LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_contrato", $id_contrato);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Guardar o actualizar la información
    public function guardar($datos, $id_usuario) {
        try {
            $this->conn->beginTransaction();

            $actual = $this->obtenerPorContrato($datos['id_contrato']);

            if (!$actual) {
                // Si no existe, creamos el primer registro
                $query = "INSERT INTO " . $this->table_name . " 
                        (id_contrato, estado_secop, url_secop, estado_sia_observa, observaciones) 
                        VALUES 
                        (:id_contrato, :estado_secop, :url_secop, :estado_sia, :observaciones)";
                $accion = 'INSERT';
            } else {
                // Si ya existe, lo actualizamos
                $query = "UPDATE " . $this->table_name . " 
                          SET estado_secop = :estado_secop, url_secop = :url_secop, 
                              estado_sia_observa = :estado_sia, observaciones = :observaciones,
                              ultima_actualizacion = CURRENT_TIMESTAMP
                          WHERE id_contrato = :id_contrato";
                $accion = 'UPDATE';
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':id_contrato' => $datos['id_contrato'],
                ':estado_secop' => $datos['estado_secop'],
                ':url_secop' => $datos['url_secop'],
                ':estado_sia' => $datos['estado_sia_observa'],
                ':observaciones' => $datos['observaciones']
            ]);

            // Registrar en Auditoría
            $queryAuditoria = "INSERT INTO auditoria (id_usuario, accion, tabla_afectada, registro_id, detalles_nuevos, direccion_ip) 
                               VALUES (:user, :accion, 'control_plataformas', :reg_id, :detalles, :ip)";
            $stmtAuditoria = $this->conn->prepare($queryAuditoria);
            $stmtAuditoria->execute([
                ':user' => $id_usuario,
                ':accion' => $accion,
                ':reg_id' => $datos['id_contrato'],
                ':detalles' => json_encode($datos),
                ':ip' => $_SERVER['REMOTE_ADDR']
            ]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>