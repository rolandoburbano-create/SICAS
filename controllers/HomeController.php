<?php
require_once 'config/Conexion.php';

class HomeController {
    
    public function index() {
        if(isset($_GET['test'])) {
            var_dump($_SESSION);
            exit();
        }
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=showLogin");
            exit();
        }

        $db = new Conexion();
        $conn = $db->getConnection();

        // 1. Estadísticas Globales
        $stats = [];
        
        // Total Contratos
        $res = $conn->query("SELECT COUNT(*) as total FROM contratos");
        $stats['total_contratos'] = $res->fetch(PDO::FETCH_ASSOC)['total'];

        // Contratos Activos
        $res = $conn->query("SELECT COUNT(*) as total FROM contratos WHERE estado = 'Activo'");
        $stats['activos'] = $res->fetch(PDO::FETCH_ASSOC)['total'];

        // Inversión Total (Suma de valor_total)
        $res = $conn->query("SELECT SUM(valor_total) as inversion FROM contratos");
        $stats['inversion_total'] = $res->fetch(PDO::FETCH_ASSOC)['inversion'] ?? 0;

        // Total Contratistas
        $res = $conn->query("SELECT COUNT(*) as total FROM contratistas");
        $stats['contratistas'] = $res->fetch(PDO::FETCH_ASSOC)['total'];

        // 2. Alertas: Contratos por vencer (Próximos 30 días)
        $queryAlertas = "SELECT numero_contrato, objeto_contrato, fecha_terminacion, 
                         DATEDIFF(fecha_terminacion, CURDATE()) as dias_restantes 
                         FROM contratos 
                         WHERE estado = 'Activo' 
                         AND DATEDIFF(fecha_terminacion, CURDATE()) <= 30 
                         ORDER BY dias_restantes ASC LIMIT 5";
        $stmt = $conn->prepare($queryAlertas);
        $stmt->execute();
        $alertas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Cargar Vistas
        require_once 'views/layout/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layout/footer.php';
    }
}