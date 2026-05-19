<?php
// Importar los modelos que necesita este controlador
require_once 'models/Contrato.php';
require_once 'models/Contratista.php'; // <--- ¡Esta es la línea que falta!

class ContratoController {
    
// Vista principal: Panel de Contratos (Listado)
    public function index() {
        $contratoModel = new Contrato();
        $contratos = $contratoModel->listar(); // Esto trae los contratos de la BD

        // 1. Cargar el Header y Sidebar
        require_once 'views/layout/header.php';

        // 2. Cargar la vista de la tabla
        require_once 'views/contratos/index.php';

        // 3. Cargar el Footer
        require_once 'views/layout/footer.php';
    }
    // Insertar DENTRO de la clase ContratoController

    // Cargar la vista con el formulario
    public function create() {
        // 1. Obtener los contratistas
        $contratistaModel = new Contratista();
        $contratistas = $contratistaModel->listarTodos();

        // 2. Obtener los supervisores (Usuarios con rol de supervisor)
        // Asumimos que el ID del rol de supervisor es 4
        $db = new Conexion();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT id_usuario, nombres, apellidos FROM usuarios WHERE id_rol = 4 ORDER BY nombres ASC");
        $stmt->execute();
        $supervisores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Cargar las vistas
        require_once 'views/layout/header.php';
        require_once 'views/contratos/create.php';
        require_once 'views/layout/footer.php';
    }

    // Recibir los datos del formulario POST y guardarlos
    public function store() {
        AuthHelper::permitir([1]); // Solo Admin

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contratoModel = new Contrato();
            
            // Recolectar TODOS los datos del formulario
            // Usamos ?? null para que si un campo no es obligatorio y viene vacío, no genere error
             $datos = [
                'numero_contrato'        => $_POST['numero_contrato'],
                'objeto_contrato'        => $_POST['objeto_contrato'],
                'valor_total'            => $_POST['valor_total'],
                'forma_pago'             => $_POST['forma_pago'] ?? '',
                'id_contratista'         => $_POST['id_contratista'],
                'id_supervisor'          => $_POST['id_supervisor'],
                'fecha_firma'            => $_POST['fecha_firma'] ?? null,
                'fecha_inicio'           => $_POST['fecha_inicio'] ?? null,
                'fecha_terminacion'      => $_POST['fecha_terminacion'] ?? null,
                
                // --- NUEVOS CAMPOS DE EJECUCIÓN REAL ---
                'fecha_terminacion_real' => $_POST['fecha_terminacion_real'] ?? null,
                'plazo_ejecucion_real'   => $_POST['plazo_ejecucion_real'] ?? '',
                // ----------------------------------------
                
                'plazo_ejecucion'        => $_POST['plazo_ejecucion'] ?? '',
                'cdp'                    => $_POST['cdp'] ?? '',
                'rp'                     => $_POST['rp'] ?? '',
                'rubro_presupuestal'     => $_POST['rubro_presupuestal'] ?? '',
                'link_secop'             => $_POST['link_secop'] ?? '',
                'bpin'                   => $_POST['bpin'] ?? '',
                'linea_estrategica'      => $_POST['linea_estrategica'] ?? '',
                'tipo_contrato'          => $_POST['tipo_contrato'] ?? '',
                'modalidad_seleccion'    => $_POST['modalidad_seleccion'] ?? '',
                'fuente_recursos'        => $_POST['fuente_recursos'] ?? '',
                'secretaria'             => $_POST['secretaria'] ?? '',
                'estado'                 => 'Activo' 
            ];

            try {
                if ($contratoModel->crear($datos)) {
                    header("Location: index.php?controller=contrato&action=index");
                    exit();
                }
            } catch (Exception $e) {
                echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
            }
        }
    }

    public function show() {
        // Permitimos que todos los roles activos vean los detalles
        AuthHelper::permitir([1, 2, 3, 4]);

        if (!isset($_GET['id'])) {
            header("Location: index.php?controller=contrato&action=index");
            exit();
        }

        $id = $_GET['id'];
        $contratoModel = new Contrato();
        $contrato = $contratoModel->obtenerDetallePorId($id);

        if (!$contrato) {
            die("<div style='padding: 20px; color: red;'>El contrato solicitado no existe.</div>");
        }

        // Cargar las vistas
        require_once 'views/layout/header.php';
        require_once 'views/contratos/show.php';
        require_once 'views/layout/footer.php';
    }

    public function edit() {
        // 🔒 PERMISOS: Admin(1), Financiero(2), Supervisor(4)
        AuthHelper::permitir([1, 2, 4]);

        // Validar que venga un ID en la URL
        if (!isset($_GET['id'])) {
            header("Location: index.php?controller=contrato&action=index");
            exit();
        }

        $id = $_GET['id'];

        // 1. Obtener los datos del contrato actual
        $contratoModel = new Contrato();
        $contrato = $contratoModel->obtenerPorId($id);

        if (!$contrato) {
            die("El contrato no existe.");
        }

        // 2. Obtener listas para los desplegables
        require_once 'models/Contratista.php';
        $contratistaModel = new Contratista();
        $contratistas = $contratistaModel->listarTodos();

        $db = new Conexion();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT id_usuario, nombres, apellidos FROM usuarios WHERE id_rol = 4 ORDER BY nombres ASC");
        $stmt->execute();
        $supervisores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Cargar las vistas
        require_once 'views/layout/header.php';
        require_once 'views/contratos/edit.php';
        require_once 'views/layout/footer.php';
    }

    public function update() {
        // 🔒 PERMISOS: Admin, Financiero, Supervisor
        AuthHelper::permitir([1, 2, 4]);

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_contrato'])) {
            $id = $_POST['id_contrato'];

            $contratoModel = new Contrato();
            
            // 1. Traer el contrato original para no perder datos de los campos "disabled"
            $contratoOriginal = $contratoModel->obtenerPorId($id);

            if (!$contratoOriginal) {
                die("Error: Contrato no encontrado.");
            }

            // 2. Preparar los datos fusionando lo original con lo que viene del formulario.
            // Si el campo viene en el POST (porque el usuario tenía permiso), reemplaza al original.
            $datosActualizados = [
                'id_contrato' => $id,
                'numero_contrato' => $_POST['numero_contrato'] ?? $contratoOriginal['numero_contrato'],
                'objeto_contrato' => $_POST['objeto_contrato'] ?? $contratoOriginal['objeto_contrato'],
                'valor_total' => $_POST['valor_total'] ?? $contratoOriginal['valor_total'],
                'forma_pago' => $_POST['forma_pago'] ?? $contratoOriginal['forma_pago'],
                
                // Novedades (Si el rol es financiero, estos inputs vienen vacíos/nulos, así que conservamos el original)
                'tiene_prorroga' => isset($_POST['tiene_prorroga']) ? 1 : (AuthHelper::esFinanciero() ? $contratoOriginal['tiene_prorroga'] : 0),
                'numero_prorroga' => $_POST['numero_prorroga'] ?? $contratoOriginal['numero_prorroga'],
                'tiempo_prorroga' => $_POST['tiempo_prorroga'] ?? $contratoOriginal['tiempo_prorroga'],
                
                'tiene_suspension' => isset($_POST['tiene_suspension']) ? 1 : (AuthHelper::esFinanciero() ? $contratoOriginal['tiene_suspension'] : 0),
                'numero_suspension' => $_POST['numero_suspension'] ?? $contratoOriginal['numero_suspension'],
                'duracion_suspension' => $_POST['duracion_suspension'] ?? $contratoOriginal['duracion_suspension']
            ];

            // 3. Enviar a guardar en la base de datos
            if ($contratoModel->actualizar($datosActualizados)) {
                // Redirigir al listado con éxito
                header("Location: index.php?controller=contrato&action=index");
                exit();
            } else {
                echo "<script>alert('Error al actualizar el contrato en la base de datos'); window.history.back();</script>";
            }
        }
    }
}
?>