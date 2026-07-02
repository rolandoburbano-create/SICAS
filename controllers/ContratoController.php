<?php
// Importar los modelos que necesita este controlador
require_once 'models/Contrato.php';
require_once 'models/Contratista.php'; // <--- ¡Esta es la línea que falta!

class ContratoController {
    
    public function index() {
        $contratoModel = new Contrato();
        $termino = isset($_GET['q']) ? trim($_GET['q']) : '';

        $alertas_vencimiento = [];

        if (AuthHelper::esSupervisor()) {
            $id_usuario = $_SESSION['usuario_id'];
            $contratos = $termino
                ? $contratoModel->buscarPorSupervisor($termino, $id_usuario)
                : $contratoModel->listarPorSupervisor($id_usuario);

            foreach ($contratos as &$c) {
                if (!empty($c['fecha_terminacion']) && $c['fecha_terminacion'] != '0000-00-00') {
                    $hoy = new DateTime();
                    $f_term = new DateTime($c['fecha_terminacion']);
                    $diff = $hoy->diff($f_term);
                    $dias = $hoy <= $f_term ? (int)$diff->days : -(int)$diff->days;
                    $c['dias_restantes'] = $dias;
                    if ($dias <= 8) {
                        $alertas_vencimiento[] = $c;
                    }
                } else {
                    $c['dias_restantes'] = null;
                }
            }
            unset($c);
        } else {
            $contratos = $termino ? $contratoModel->buscar($termino) : $contratoModel->listar();
        }

        require_once 'views/layout/header.php';
        require_once 'views/contratos/index.php';
        require_once 'views/layout/footer.php';
    }
    // Insertar DENTRO de la clase ContratoController

    // Cargar la vista con el formulario
    public function create() {
        AuthHelper::permitir([1, 5]);
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
        AuthHelper::permitir([1, 5]);

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
                'fecha_inicio'           => $_POST['fecha_inicio'] ?? date('Y-m-d'),
                'fecha_terminacion'      => $_POST['fecha_terminacion'] ?? null,
                
                // --- NUEVOS CAMPOS DE EJECUCIÓN REAL ---
                'fecha_terminacion_real' => $_POST['fecha_terminacion_real'] ?? null,
                'plazo_ejecucion_real'   => $_POST['plazo_ejecucion_real'] ?? '',
                // ----------------------------------------
                
                'plazo_ejecucion'        => $_POST['plazo_ejecucion'] ?? 'Por definir',
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
        AuthHelper::permitir([1, 2, 3, 4, 5]);

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

        // Supervisor solo ve sus propios contratos
        if (AuthHelper::esSupervisor() && $contrato['id_supervisor'] != $_SESSION['usuario_id']) {
            die("<div style='padding: 20px; color: red; font-family: sans-serif;'>
                    <h3><i class='fa-solid fa-lock'></i> Acceso Denegado</h3>
                    <p>Este contrato no está bajo su supervisión.</p>
                    <a href='index.php' class='btn btn-primary mt-4'>Volver al inicio</a>
                 </div>");
        }

        // --- NUEVO: CARGAR DATOS FINANCIEROS ---
        require_once 'models/Pago.php';
        $pagoModel = new Pago();
        $historial_pagos = $pagoModel->obtenerPorContrato($id);
        $total_pagado = $pagoModel->obtenerTotalPagado($id);
        
        // Calcular Saldo y Porcentaje de Ejecución
        $saldo_pendiente = $contrato['valor_total'] - $total_pagado;
        $porcentaje = 0;
        if ($contrato['valor_total'] > 0) {
            $porcentaje = ($total_pagado / $contrato['valor_total']) * 100;
        }
        // ----------------------------------------

        require_once 'views/layout/header.php';
        require_once 'views/contratos/show.php';
        require_once 'views/layout/footer.php';
    }

    public function edit() {
        // 🔒 PERMISOS: Admin(1), Financiero(2), Supervisor(4)
        AuthHelper::permitir([1]);

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
        AuthHelper::permitir([1]);

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
                'tiene_prorroga' => isset($_POST['tiene_prorroga']) ? 1 : $contratoOriginal['tiene_prorroga'],
                'numero_prorroga' => $_POST['numero_prorroga'] ?? $contratoOriginal['numero_prorroga'],
                'tiempo_prorroga' => $_POST['tiempo_prorroga'] ?? $contratoOriginal['tiempo_prorroga'],
                
                'tiene_suspension' => isset($_POST['tiene_suspension']) ? 1 : $contratoOriginal['tiene_suspension'],
                'numero_suspension' => $_POST['numero_suspension'] ?? $contratoOriginal['numero_suspension'],
                'duracion_suspension' => $_POST['duracion_suspension'] ?? $contratoOriginal['duracion_suspension'],

                'tiene_reinicio' => isset($_POST['tiene_reinicio']) ? 1 : $contratoOriginal['tiene_reinicio'],
                'numero_reinicio' => $_POST['numero_reinicio'] ?? $contratoOriginal['numero_reinicio'],
                'fecha_reinicio' => $_POST['fecha_reinicio'] ?? $contratoOriginal['fecha_reinicio'],

                'tiene_cesion' => isset($_POST['tiene_cesion']) ? 1 : $contratoOriginal['tiene_cesion'],
                'fecha_cesion' => $_POST['fecha_cesion'] ?? $contratoOriginal['fecha_cesion'],
                'id_nuevo_contratista' => $_POST['id_nuevo_contratista'] ?? $contratoOriginal['id_nuevo_contratista']
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