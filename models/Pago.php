<?php
require_once 'config/Conexion.php';

class Pago {
    private $conn;
    private $table_name = "pagos";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    // 1. Registrar un nuevo pago
    public function registrar($datos) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (id_contrato, numero_acta, fecha_pago, valor_pagado, observaciones) 
                      VALUES (:id_contrato, :acta, :fecha, :valor, :obs)";
            
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':id_contrato' => $datos['id_contrato'],
                ':acta'        => $datos['numero_acta'],
                ':fecha'       => $datos['fecha_pago'],
                ':valor'       => $datos['valor_pagado'],
                ':obs'         => $datos['observaciones']
            ]);
        } catch(PDOException $e) {
            return false;
        }
    }

    // 2. Obtener el historial de pagos de un contrato específico
    public function obtenerPorContrato($id_contrato) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE id_contrato = :id 
                  ORDER BY fecha_pago ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id_contrato]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. El motor matemático: Sumar todo lo pagado a un contrato
    public function obtenerTotalPagado($id_contrato) {
        $query = "SELECT SUM(valor_pagado) as total_pagado 
                  FROM " . $this->table_name . " 
                  WHERE id_contrato = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id_contrato]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $resultado['total_pagado'] ? $resultado['total_pagado'] : 0;
    }
}
?>