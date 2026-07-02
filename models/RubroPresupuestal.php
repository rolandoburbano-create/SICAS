<?php
require_once 'config/Conexion.php';

class RubroPresupuestal {
    private $conn;
    private $table_name = "rubros_presupuestales";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    public function obtenerPorContrato($id_contrato) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_contrato = :id_contrato ORDER BY id_rubro ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_contrato", $id_contrato);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id_rubro) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_rubro = :id_rubro";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_rubro", $id_rubro);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($datos) {
        $query = "INSERT INTO " . $this->table_name . " (id_contrato, rubro, vigencia, origen_recurso, tipo, valor) 
                  VALUES (:id_contrato, :rubro, :vigencia, :origen_recurso, :tipo, :valor)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_contrato'   => $datos['id_contrato'],
            ':rubro'         => $datos['rubro'],
            ':vigencia'      => $datos['vigencia'] ?? null,
            ':origen_recurso'=> $datos['origen_recurso'] ?? null,
            ':tipo'          => $datos['tipo'] ?? null,
            ':valor'         => $datos['valor']
        ]);
    }

    public function actualizar($datos) {
        $query = "UPDATE " . $this->table_name . " 
                  SET rubro = :rubro, vigencia = :vigencia, origen_recurso = :origen_recurso, 
                      tipo = :tipo, valor = :valor 
                  WHERE id_rubro = :id_rubro";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_rubro'       => $datos['id_rubro'],
            ':rubro'          => $datos['rubro'],
            ':vigencia'       => $datos['vigencia'] ?? null,
            ':origen_recurso' => $datos['origen_recurso'] ?? null,
            ':tipo'           => $datos['tipo'] ?? null,
            ':valor'          => $datos['valor']
        ]);
    }

    public function eliminar($id_rubro) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_rubro = :id_rubro";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_rubro", $id_rubro);
        return $stmt->execute();
    }

    public function obtenerResumenGlobal() {
        $query = "SELECT c.id_contrato, c.numero_contrato, c.valor_total, con.nombre_razon_social,
                         COUNT(r.id_rubro) as total_rubros,
                         COALESCE(SUM(r.valor), 0) as total_presupuestado
                  FROM contratos c
                  INNER JOIN contratistas con ON c.id_contratista = con.id_contratista
                  LEFT JOIN rubros_presupuestales r ON c.id_contrato = r.id_contrato
                  GROUP BY c.id_contrato
                  ORDER BY c.fecha_elaboracion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerResumenGlobalPorSupervisor($id_usuario) {
        $query = "SELECT c.id_contrato, c.numero_contrato, c.valor_total, con.nombre_razon_social,
                         COUNT(r.id_rubro) as total_rubros,
                         COALESCE(SUM(r.valor), 0) as total_presupuestado
                  FROM contratos c
                  INNER JOIN contratistas con ON c.id_contratista = con.id_contratista
                  LEFT JOIN rubros_presupuestales r ON c.id_contrato = r.id_contrato
                  WHERE c.id_supervisor = ?
                  GROUP BY c.id_contrato
                  ORDER BY c.fecha_elaboracion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTotalesGlobales() {
        $query = "SELECT COUNT(DISTINCT c.id_contrato) as total_contratos,
                         COUNT(r.id_rubro) as total_rubros,
                         COALESCE(SUM(r.valor), 0) as gran_total
                  FROM contratos c
                  LEFT JOIN rubros_presupuestales r ON c.id_contrato = r.id_contrato";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTotalesGlobalesPorSupervisor($id_usuario) {
        $query = "SELECT COUNT(DISTINCT c.id_contrato) as total_contratos,
                         COUNT(r.id_rubro) as total_rubros,
                         COALESCE(SUM(r.valor), 0) as gran_total
                  FROM contratos c
                  LEFT JOIN rubros_presupuestales r ON c.id_contrato = r.id_contrato
                  WHERE c.id_supervisor = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
