<?php
require_once 'models/Presupuesto.php';
require_once 'models/RubroPresupuestal.php';
require_once 'models/Contrato.php';
require_once 'helpers/AuthHelper.php';

class PresupuestoController {

    public function index() {
        AuthHelper::permitir([1, 2, 3, 4, 5]);
        $rubroModel = new RubroPresupuestal();

        if (AuthHelper::esSupervisor()) {
            $id_usuario = $_SESSION['usuario_id'];
            $contratos = $rubroModel->obtenerResumenGlobalPorSupervisor($id_usuario);
            $totales = $rubroModel->obtenerTotalesGlobalesPorSupervisor($id_usuario);
        } else {
            $contratos = $rubroModel->obtenerResumenGlobal();
            $totales = $rubroModel->obtenerTotalesGlobales();
        }

        require_once 'views/layout/header.php';
        require_once 'views/presupuesto/index.php';
        require_once 'views/layout/footer.php';
    }
    
    public function gestionar() {
        AuthHelper::permitir([1, 2, 3, 4, 5]);
        if (!isset($_GET['id'])) {
            header("Location: " . BASE_URL . "index.php?controller=contrato&action=index");
            exit();
        }

        $id_contrato = $_GET['id'];
        
        $db = new Conexion();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT c.*, con.nombre_razon_social FROM contratos c INNER JOIN contratistas con ON c.id_contratista = con.id_contratista WHERE c.id_contrato = ?");
        $stmt->execute([$id_contrato]);
        $contrato = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$contrato) {
            die("Contrato no encontrado.");
        }

        // Supervisor solo ve presupuesto de sus propios contratos
        if (AuthHelper::esSupervisor() && $contrato['id_supervisor'] != $_SESSION['usuario_id']) {
            die("<div style='padding: 20px; color: red; font-family: sans-serif;'>
                    <h3><i class='fa-solid fa-lock'></i> Acceso Denegado</h3>
                    <p>Este contrato no está bajo su supervisión.</p>
                    <a href='index.php' class='btn btn-primary mt-4'>Volver al inicio</a>
                 </div>");
        }

        $presupuestoModel = new Presupuesto();
        $presupuesto = $presupuestoModel->obtenerPorContrato($id_contrato);

        $rubroModel = new RubroPresupuestal();
        $rubros = $rubroModel->obtenerPorContrato($id_contrato);

        require_once 'views/layout/header.php';
        require_once 'views/presupuesto/gestionar.php';
        require_once 'views/layout/footer.php';
    }

    public function guardarRubro() {
        AuthHelper::permitir([1, 2]);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rubroModel = new RubroPresupuestal();
            $datos = [
                'id_contrato'    => $_POST['id_contrato'],
                'rubro'          => trim($_POST['rubro']),
                'vigencia'       => trim($_POST['vigencia'] ?? ''),
                'origen_recurso' => trim($_POST['origen_recurso'] ?? ''),
                'tipo'           => trim($_POST['tipo'] ?? ''),
                'valor'          => str_replace('.', '', $_POST['valor'])
            ];
            $rubroModel->crear($datos);
            header("Location: " . BASE_URL . "index.php?controller=presupuesto&action=gestionar&id=" . $datos['id_contrato']);
        }
    }

    public function actualizarRubro() {
        AuthHelper::permitir([1, 2]);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rubroModel = new RubroPresupuestal();
            $datos = [
                'id_rubro'       => $_POST['id_rubro'],
                'rubro'          => trim($_POST['rubro']),
                'vigencia'       => trim($_POST['vigencia'] ?? ''),
                'origen_recurso' => trim($_POST['origen_recurso'] ?? ''),
                'tipo'           => trim($_POST['tipo'] ?? ''),
                'valor'          => str_replace('.', '', $_POST['valor'])
            ];
            $rubroModel->actualizar($datos);
            header("Location: " . BASE_URL . "index.php?controller=presupuesto&action=gestionar&id=" . $_POST['id_contrato']);
        }
    }

    public function eliminarRubro() {
        AuthHelper::permitir([1, 2]);
        if (!isset($_GET['id']) || !isset($_GET['id_contrato'])) {
            header("Location: " . BASE_URL . "index.php?controller=contrato&action=index");
            exit();
        }
        $rubroModel = new RubroPresupuestal();
        $rubroModel->eliminar($_GET['id']);
        header("Location: " . BASE_URL . "index.php?controller=presupuesto&action=gestionar&id=" . $_GET['id_contrato']);
    }

    public function registrarPago() {
        AuthHelper::permitir([1, 2]);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $presupuestoModel = new Presupuesto();
            
            $datos = [
                'id_contrato' => $_POST['id_contrato'],
                'rubros_presupuestales' => isset($_POST['rubros_presupuestales']) ? trim($_POST['rubros_presupuestales']) : 'N/A',
                'valor_asignado' => $_POST['valor_asignado'],
                'numero_pagos_proyectados' => isset($_POST['numero_pagos_proyectados']) ? $_POST['numero_pagos_proyectados'] : 0,
                'monto_pago' => $_POST['monto_pago']
            ];

            $resultado = $presupuestoModel->registrarPago($datos, $_SESSION['usuario_id']);

            if ($resultado === true) {
                header("Location: " . BASE_URL . "index.php?controller=presupuesto&action=gestionar&id=" . $datos['id_contrato'] . "&msg=success");
            } else {
                echo "<script>
                        alert('Error: " . $resultado . "');
                        window.history.back();
                      </script>";
            }
        }
    }
}
