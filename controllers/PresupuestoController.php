<?php
require_once 'models/Presupuesto.php';
require_once 'models/Contrato.php';

class PresupuestoController {
    
    // Muestra el panel financiero de un contrato
    public function gestionar() {
        if (!isset($_GET['id'])) {
            header("Location: " . BASE_URL . "index.php?controller=contrato&action=index");
            exit();
        }

        $id_contrato = $_GET['id'];
        
        // Necesitamos datos del contrato (Haremos una consulta directa rápida aquí para la vista)
        $db = new Conexion();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT c.*, con.nombre_razon_social FROM contratos c INNER JOIN contratistas con ON c.id_contratista = con.id_contratista WHERE c.id_contrato = ?");
        $stmt->execute([$id_contrato]);
        $contrato = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$contrato) {
            die("Contrato no encontrado.");
        }

        // Obtener datos presupuestales
        $presupuestoModel = new Presupuesto();
        $presupuesto = $presupuestoModel->obtenerPorContrato($id_contrato);

        require_once 'views/layout/header.php';
        require_once 'views/presupuesto/gestionar.php';
        require_once 'views/layout/footer.php';
    }

    // Recibe el pago y lo procesa
    public function registrarPago() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $presupuestoModel = new Presupuesto();
            
            $datos = [
                'id_contrato' => $_POST['id_contrato'],
                'rubros_presupuestales' => isset($_POST['rubros_presupuestales']) ? trim($_POST['rubros_presupuestales']) : 'N/A',
                'valor_asignado' => $_POST['valor_asignado'], // Valor total del contrato
                'numero_pagos_proyectados' => isset($_POST['numero_pagos_proyectados']) ? $_POST['numero_pagos_proyectados'] : 0,
                'monto_pago' => $_POST['monto_pago'] // Lo que se está pagando ahora
            ];

            $resultado = $presupuestoModel->registrarPago($datos, $_SESSION['usuario_id']);

            if ($resultado === true) {
                header("Location: " . BASE_URL . "index.php?controller=presupuesto&action=gestionar&id=" . $datos['id_contrato'] . "&msg=success");
            } else {
                // Enviar el mensaje de error (ej. "El pago excede el saldo") a la vista
                echo "<script>
                        alert('Error: " . $resultado . "');
                        window.history.back();
                      </script>";
            }
        }
    }
}
?>