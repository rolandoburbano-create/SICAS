<?php
require_once 'models/ControlPlataforma.php';
require_once 'models/Contrato.php'; // Para obtener datos básicos del contrato

class PlataformaController {
    
    // Muestra la vista para gestionar el estado en SECOP y SIA
    public function gestionar() {
        if (!isset($_GET['id'])) {
            header("Location: " . BASE_URL . "index.php?controller=contrato&action=index");
            exit();
        }

        $id_contrato = $_GET['id'];
        
        // Obtener datos básicos del contrato
        $db = new Conexion();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT numero_contrato, objeto_contrato FROM contratos WHERE id_contrato = ?");
        $stmt->execute([$id_contrato]);
        $contrato = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$contrato) {
            die("Contrato no encontrado.");
        }

        // Obtener el estado en las plataformas
        $plataformaModel = new ControlPlataforma();
        $control = $plataformaModel->obtenerPorContrato($id_contrato);

        require_once 'views/layout/header.php';
        require_once 'views/plataformas/gestionar.php';
        require_once 'views/layout/footer.php';
    }

    // Procesa el guardado del formulario
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $plataformaModel = new ControlPlataforma();
            
            $datos = [
                'id_contrato' => $_POST['id_contrato'],
                'estado_secop' => trim($_POST['estado_secop']),
                'url_secop' => trim($_POST['url_secop']),
                'estado_sia_observa' => trim($_POST['estado_sia_observa']),
                'observaciones' => trim($_POST['observaciones'])
            ];

            if ($plataformaModel->guardar($datos, $_SESSION['usuario_id'])) {
                header("Location: " . BASE_URL . "index.php?controller=plataforma&action=gestionar&id=" . $datos['id_contrato'] . "&msg=success");
            } else {
                echo "<script>alert('Error al guardar la información.'); window.history.back();</script>";
            }
        }
    }
}
?>