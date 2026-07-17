<?php
require_once 'config/Conexion.php';

class Contrato {
    private $conn;
    private $table_name = "contratos";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    // Listar todos los contratos con el nombre de su contratista
    public function listar() {
        $query = "SELECT c.id_contrato, c.numero_contrato, c.tipo_contrato, c.valor_total, c.estado_contrato, con.nombre_razon_social 
                  FROM " . $this->table_name . " c
                  INNER JOIN contratistas con ON c.id_contratista = con.id_contratista
                  ORDER BY c.fecha_elaboracion DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($termino) {
        $query = "SELECT c.id_contrato, c.numero_contrato, c.tipo_contrato, c.valor_total, c.estado_contrato, c.fecha_terminacion, c.objeto_contrato, con.nombre_razon_social 
                  FROM " . $this->table_name . " c
                  INNER JOIN contratistas con ON c.id_contratista = con.id_contratista
                  WHERE c.numero_contrato LIKE ? 
                     OR con.nombre_razon_social LIKE ? 
                     OR c.objeto_contrato LIKE ? 
                  ORDER BY c.fecha_elaboracion DESC";
                 
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->execute([$termino, $termino, $termino]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorSupervisor($id_usuario) {
        $query = "SELECT c.id_contrato, c.numero_contrato, c.tipo_contrato, c.valor_total,
                         c.estado_contrato, c.fecha_terminacion, c.objeto_contrato,
                         con.nombre_razon_social
                  FROM " . $this->table_name . " c
                  INNER JOIN contratistas con ON c.id_contratista = con.id_contratista
                  WHERE c.id_supervisor = ?
                  ORDER BY c.fecha_elaboracion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorSupervisor($termino, $id_usuario) {
        $query = "SELECT c.id_contrato, c.numero_contrato, c.tipo_contrato, c.valor_total,
                         c.estado_contrato, c.fecha_terminacion, c.objeto_contrato,
                         con.nombre_razon_social
                  FROM " . $this->table_name . " c
                  INNER JOIN contratistas con ON c.id_contratista = con.id_contratista
                  WHERE c.id_supervisor = ?
                    AND (c.numero_contrato LIKE ?
                         OR con.nombre_razon_social LIKE ?
                         OR c.objeto_contrato LIKE ?)
                  ORDER BY c.fecha_elaboracion DESC";
        $stmt = $this->conn->prepare($query);
        $termino = "%{$termino}%";
        $stmt->execute([$id_usuario, $termino, $termino, $termino]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insertar este código DENTRO de la clase Contrato, debajo de la función listar()

    public function registrar($datos, $id_usuario) {
        try {
            // Iniciar transacción: O se guarda todo, o no se guarda nada
            $this->conn->beginTransaction();

            // 1. Insertar el Contrato
            $query = "INSERT INTO " . $this->table_name . " 
                    (numero_contrato, id_contratista, tipo_contrato, modalidad_seleccion, fuente_recursos, valor_total, plazo_ejecucion, numero_cdp, numero_rp, objeto_contrato, fecha_elaboracion, id_supervisor, secretaria, fecha_inicio) 
                    VALUES 
                    (:numero, :contratista, :tipo, :modalidad, :fuente, :valor, :plazo, :cdp, :rp, :objeto, :fecha_elab, :supervisor, :secretaria, :fecha_ini)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':numero' => $datos['numero_contrato'],
                ':contratista' => $datos['id_contratista'],
                ':tipo' => $datos['tipo_contrato'],
                ':modalidad' => $datos['modalidad_seleccion'],
                ':fuente' => $datos['fuente_recursos'],
                ':valor' => $datos['valor_total'],
                ':plazo' => $datos['plazo_ejecucion'],
                ':cdp' => $datos['numero_cdp'],
                ':rp' => $datos['numero_rp'],
                ':objeto' => $datos['objeto_contrato'],
                ':fecha_elab' => $datos['fecha_elaboracion'],
                ':supervisor' => $id_usuario, // El supervisor por defecto es quien lo crea (o se puede cambiar luego)
                ':secretaria' => $datos['secretaria'],
                ':fecha_ini' => $datos['fecha_inicio']
            ]);

            $id_contrato_nuevo = $this->conn->lastInsertId();

            // 2. Registrar en Auditoría (Cumplimiento Ley 1474)
            $queryAuditoria = "INSERT INTO auditoria (id_usuario, accion, tabla_afectada, registro_id, detalles_nuevos, direccion_ip) 
                               VALUES (:user, 'INSERT', 'contratos', :reg_id, :detalles, :ip)";
            $stmtAuditoria = $this->conn->prepare($queryAuditoria);
            $stmtAuditoria->execute([
                ':user' => $id_usuario,
                ':reg_id' => $id_contrato_nuevo,
                ':detalles' => json_encode($datos),
                ':ip' => $_SERVER['REMOTE_ADDR']
            ]);

            // Confirmar transacción
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            // En producción, guardar $e->getMessage() en un log de errores
            return false;
        }
    }

    public function guardar($datos) {
        $query = "INSERT INTO " . $this->table_name . " 
                (numero_contrato, bpin, linea_estrategica, id_contratista, id_supervisor, tipo_contrato, clase_contrato, objeto_contrato, 
                valor_total, forma_pago, fecha_elaboracion, fecha_firma, fecha_inicio, plazo_ejecucion, 
                tiene_prorroga, numero_prorroga, tiempo_prorroga, 
                tiene_suspension, numero_suspension, duracion_suspension,
                tiene_reinicio, fecha_reinicio, 
                tiene_cesion, fecha_cesion, id_nuevo_contratista,
                fecha_terminacion, estado) 
                VALUES 
                (:num, :bpin, :linea, :id_con, :id_sup, :tipo, :clase, :objeto, :valor, :pago, :f_elab, :f_firma, :f_ini, :plazo, 
                :t_pro, :n_pro, :ti_pro, :t_sus, :n_sus, :d_sus, :t_rei, :f_rei, :t_ces, :f_ces, :id_ncon, :f_term, :estado)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':num' => $datos['numero_contrato'],
            ':bpin' => $datos['bpin'],
            ':linea' => $datos['linea_estrategica'],
            ':id_con' => $datos['id_contratista'],
            ':id_sup' => $datos['id_supervisor'],
            ':tipo' => $datos['tipo_contrato'],
            ':clase' => $datos['clase_contrato'],
            ':objeto' => $datos['objeto_contrato'],
            ':valor' => $datos['valor_total'],
            ':pago' => $datos['forma_pago'],
            ':f_elab' => $datos['fecha_elaboracion'],
            ':f_firma' => $datos['fecha_firma'],
            ':f_ini' => $datos['fecha_inicio'],
            ':plazo' => $datos['plazo_ejecucion'],
            ':t_pro' => isset($datos['tiene_prorroga']) ? 1 : 0,
            ':n_pro' => $datos['numero_prorroga'] ?? 0,
            ':ti_pro' => $datos['tiempo_prorroga'] ?? null,
            ':t_sus' => isset($datos['tiene_suspension']) ? 1 : 0,
            ':n_sus' => $datos['numero_suspension'] ?? 0,
            ':d_sus' => $datos['duracion_suspension'] ?? null,
            ':t_rei' => isset($datos['tiene_reinicio']) ? 1 : 0,
            ':f_rei' => $datos['fecha_reinicio'] ?? null,
            ':t_ces' => isset($datos['tiene_cesion']) ? 1 : 0,
            ':f_ces' => $datos['fecha_cesion'] ?? null,
            ':id_ncon' => !empty($datos['id_nuevo_contratista']) ? $datos['id_nuevo_contratista'] : null,
            ':f_term' => $datos['fecha_terminacion'],
            ':estado' => $datos['estado']
        ]);
    }

    // Obtener un contrato por su ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_contrato = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar un contrato existente
    public function actualizar($datos) {
        $query = "UPDATE " . $this->table_name . " 
                  SET numero_contrato = :num, 
                      objeto_contrato = :objeto, 
                      valor_total = :valor, 
                      forma_pago = :pago,
                      id_contratista = :id_con,
                      id_supervisor = :id_sup,
                      bpin = :bpin,
                      linea_estrategica = :linea,
                      secretaria = :secretaria,
                      fuente_recursos = :fuente,
                      modalidad_seleccion = :modalidad,
                      tipo_contrato = :tipo,
                      link_secop = :secop,
                      estado = :estado,
                      fecha_firma = :f_firma,
                      fecha_inicio = :f_inicio,
                      fecha_terminacion = :f_term,
                      fecha_terminacion_real = :f_term_real,
                      fecha_liquidacion = :f_liq,
                      plazo_ejecucion = :plazo,
                      plazo_ejecucion_real = :plazo_real,
                      cdp = :cdp,
                      fecha_cdp = :f_cdp,
                      valor_cdp = :v_cdp,
                      rp = :rp,
                      valor_rp = :v_rp,
                      rubro_presupuestal = :rubro,
                      tiene_prorroga = :t_pro,
                      numero_prorroga = :n_pro,
                      tiempo_prorroga = :ti_pro,
                      tiene_suspension = :t_sus,
                      numero_suspension = :n_sus,
                      duracion_suspension = :d_sus,
                      tiene_reinicio = :t_rei,
                      numero_reinicio = :n_rei,
                      fecha_reinicio = :f_rei,
                      tiene_cesion = :t_ces,
                      fecha_cesion = :f_ces,
                      id_nuevo_contratista = :id_nuevo
                  WHERE id_contrato = :id";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            ':num' => $datos['numero_contrato'],
            ':objeto' => $datos['objeto_contrato'],
            ':valor' => $datos['valor_total'],
            ':pago' => $datos['forma_pago'],
            ':id_con' => $datos['id_contratista'],
            ':id_sup' => $datos['id_supervisor'],
            ':bpin' => $datos['bpin'] ?? null,
            ':linea' => $datos['linea_estrategica'] ?? null,
            ':secretaria' => $datos['secretaria'] ?? null,
            ':fuente' => $datos['fuente_recursos'] ?? null,
            ':modalidad' => $datos['modalidad_seleccion'] ?? null,
            ':tipo' => $datos['tipo_contrato'] ?? null,
            ':secop' => $datos['link_secop'] ?? null,
            ':estado' => $datos['estado'] ?? 'Activo',
            ':f_firma' => $datos['fecha_firma'] ?? null,
            ':f_inicio' => $datos['fecha_inicio'] ?? null,
            ':f_term' => $datos['fecha_terminacion'] ?? null,
            ':f_term_real' => $datos['fecha_terminacion_real'] ?? null,
            ':f_liq' => $datos['fecha_liquidacion'] ?? null,
            ':plazo' => $datos['plazo_ejecucion'] ?? null,
            ':plazo_real' => $datos['plazo_ejecucion_real'] ?? null,
            ':cdp' => $datos['cdp'],
            ':f_cdp' => $datos['fecha_cdp'] ?? null,
            ':v_cdp' => $datos['valor_cdp'] ?? null,
            ':rp' => $datos['rp'],
            ':v_rp' => $datos['valor_rp'] ?? null,
            ':rubro' => $datos['rubro_presupuestal'],
            ':t_pro' => $datos['tiene_prorroga'],
            ':n_pro' => $datos['numero_prorroga'],
            ':ti_pro' => $datos['tiempo_prorroga'],
            ':t_sus' => $datos['tiene_suspension'],
            ':n_sus' => $datos['numero_suspension'],
            ':d_sus' => $datos['duracion_suspension'],
            ':t_rei' => $datos['tiene_reinicio'],
            ':n_rei' => $datos['numero_reinicio'],
            ':f_rei' => $datos['fecha_reinicio'],
            ':t_ces' => $datos['tiene_cesion'],
            ':f_ces' => $datos['fecha_cesion'],
            ':id_nuevo' => $datos['id_nuevo_contratista'],
            ':id' => $datos['id_contrato']
        ]);
    }

    public function crear($datos) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (numero_contrato, objeto_contrato, valor_total, forma_pago, 
                       id_contratista, id_supervisor, fecha_firma, fecha_inicio, 
                       fecha_terminacion, fecha_terminacion_real, plazo_ejecucion, plazo_ejecucion_real, 
                       cdp, fecha_cdp, valor_cdp, rp, valor_rp, rubro_presupuestal, link_secop, bpin, linea_estrategica, 
                       tipo_contrato, modalidad_seleccion, fuente_recursos, secretaria, estado) 
                      VALUES 
                      (:num, :obj, :val, :pago, 
                       :id_con, :id_sup, :f_firma, :f_inicio, 
                       :f_term, :f_term_real, :plazo, :plazo_real, 
                       :cdp, :f_cdp, :v_cdp, :rp, :v_rp, :rubro, :secop, :bpin, :linea, 
                       :tipo, :modalidad, :fuente, :secretaria, :est)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':num'          => $datos['numero_contrato'],
                ':obj'          => $datos['objeto_contrato'],
                ':val'          => $datos['valor_total'],
                ':pago'         => $datos['forma_pago'],
                ':id_con'       => $datos['id_contratista'],
                ':id_sup'       => $datos['id_supervisor'],
                ':f_firma'      => $datos['fecha_firma'],
                ':f_inicio'     => $datos['fecha_inicio'],
                ':f_term'       => $datos['fecha_terminacion'],
                ':f_term_real'  => $datos['fecha_terminacion_real'],
                ':plazo'        => $datos['plazo_ejecucion'],
                ':plazo_real'   => $datos['plazo_ejecucion_real'],
                ':cdp'          => $datos['cdp'],
                ':f_cdp'        => $datos['fecha_cdp'] ?? null,
                ':v_cdp'        => $datos['valor_cdp'] ?? null,
                ':rp'           => $datos['rp'],
                ':v_rp'         => $datos['valor_rp'] ?? null,
                ':rubro'        => $datos['rubro_presupuestal'],
                ':secop'        => $datos['link_secop'],
                ':bpin'         => $datos['bpin'],
                ':linea'        => $datos['linea_estrategica'],
                ':tipo'         => $datos['tipo_contrato'],
                ':modalidad'    => $datos['modalidad_seleccion'],
                ':fuente'       => $datos['fuente_recursos'],
                ':secretaria'   => $datos['secretaria'],
                ':est'          => $datos['estado']
            ]);
            return (int)$this->conn->lastInsertId();

        } catch (PDOException $e) {
            die("<div style='background: black; color: red; padding: 20px; font-family: monospace;'>
                    <h3>🚨 ERROR DE BASE DE DATOS AL GUARDAR 🚨</h3>
                    <p>" . $e->getMessage() . "</p>
                 </div>");
        }
    }

    // Obtener toda la información detallada de un contrato, incluyendo contratista y supervisor
    public function obtenerDetallePorId($id) {
        // CORRECCIÓN: Usamos nombre_razon_social y documento, que son los nombres reales en tu BD
        $query = "SELECT c.*, 
                         con.nombre_razon_social AS contratista_nombre, 
                         con.documento AS contratista_documento,
                         CONCAT(u.nombres, ' ', u.apellidos) AS supervisor_nombre
                  FROM " . $this->table_name . " c
                  LEFT JOIN contratistas con ON c.id_contratista = con.id_contratista
                  LEFT JOIN usuarios u ON c.id_supervisor = u.id_usuario
                  WHERE c.id_contrato = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>