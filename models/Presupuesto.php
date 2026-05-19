<?php
require_once 'config/Conexion.php';

class Presupuesto {
    private $conn;
    private $table_name = "presupuesto";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    // Obtener la información presupuestal de un contrato específico
    public function obtenerPorContrato($id_contrato) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_contrato = :id_contrato LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_contrato", $id_contrato);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Inicializar o actualizar el presupuesto y registrar un pago
    public function registrarPago($datos, $id_usuario) {
        try {
            $this->conn->beginTransaction();

            // Verificar si el presupuesto ya existe para este contrato
            $presupuesto_actual = $this->obtenerPorContrato($datos['id_contrato']);

            if (!$presupuesto_actual) {
                // Si no existe, creamos el registro inicial (Primer pago)
                $saldo_pendiente = $datos['valor_asignado'] - $datos['monto_pago'];
                
                $query = "INSERT INTO " . $this->table_name . " 
                        (id_contrato, rubros_presupuestales, valor_asignado, numero_pagos_proyectados, numero_pagos_realizados, saldo_pendiente) 
                        VALUES 
                        (:id_contrato, :rubros, :valor_asignado, :pagos_proyectados, 1, :saldo_pendiente)";
                
                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    ':id_contrato' => $datos['id_contrato'],
                    ':rubros' => $datos['rubros_presupuestales'],
                    ':valor_asignado' => $datos['valor_asignado'],
                    ':pagos_proyectados' => $datos['numero_pagos_proyectados'],
                    ':saldo_pendiente' => $saldo_pendiente
                ]);
                $accion = 'INSERT';
            } else {
                // Si ya existe, actualizamos sumando un pago y restando al saldo
                $nuevo_saldo = $presupuesto_actual['saldo_pendiente'] - $datos['monto_pago'];
                $nuevos_pagos = $presupuesto_actual['numero_pagos_realizados'] + 1;

                // Validación de seguridad: No pagar más de lo que hay
                if ($nuevo_saldo < 0) {
                    throw new Exception("El pago excede el saldo pendiente del contrato.");
                }

                $query = "UPDATE " . $this->table_name . " 
                          SET saldo_pendiente = :saldo, numero_pagos_realizados = :pagos, ultima_actualizacion = CURRENT_TIMESTAMP 
                          WHERE id_contrato = :id_contrato";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    ':saldo' => $nuevo_saldo,
                    ':pagos' => $nuevos_pagos,
                    ':id_contrato' => $datos['id_contrato']
                ]);
                $accion = 'UPDATE';
            }

            // Registrar en Auditoría (Transparencia Ley 1474)
            $queryAuditoria = "INSERT INTO auditoria (id_usuario, accion, tabla_afectada, registro_id, detalles_nuevos, direccion_ip) 
                               VALUES (:user, :accion, 'presupuesto', :reg_id, :detalles, :ip)";
            $stmtAuditoria = $this->conn->prepare($queryAuditoria);
            $stmtAuditoria->execute([
                ':user' => $id_usuario,
                ':accion' => $accion,
                ':reg_id' => $datos['id_contrato'],
                ':detalles' => json_encode(['monto_pagado' => $datos['monto_pago']]),
                ':ip' => $_SERVER['REMOTE_ADDR']
            ]);

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            return $e->getMessage();
        }
    }
}
?>