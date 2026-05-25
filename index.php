<?php
// ENCENDER REPORTES DE ERRORES (Borrar cuando el sistema pase a producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Requerir configuración inicial
require_once 'config/config.php';
require_once 'helpers/AuthHelper.php';

// Definir el controlador y acción por defecto
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Si el usuario ya inició sesión y entra a la raíz, redirigirlo al panel de contratos
if (isset($_SESSION['usuario_id']) && $controller == 'auth' && $action == 'showLogin') {
    $controller = 'contrato';
    $action = 'index';
}

// Enrutador básico MVC
switch ($controller) {
    case 'home':
        require_once 'controllers/HomeController.php';
        $homeController = new HomeController();
        $homeController->index();
        break;

    case 'auth':
        require_once 'controllers/AuthController.php';
        $authController = new AuthController();
        if (method_exists($authController, $action)) {
            $authController->$action();
        }
        break;

    case 'contrato':
        // Protección de rutas: Si no hay sesión, devolver al Login
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=showLogin");
            exit();
        }
        require_once 'controllers/ContratoController.php';
        $contratoController = new ContratoController();
        if (method_exists($contratoController, $action)) {
            $contratoController->$action();
        }
        break;
    
    case 'contratista':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=showLogin");
            exit();
        }
        require_once 'controllers/ContratistaController.php';
        $contratistaController = new ContratistaController();
        if (method_exists($contratistaController, $action)) {
            $contratistaController->$action();
        }
        break;

    case 'presupuesto':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=showLogin");
            exit();
        }
        require_once 'controllers/PresupuestoController.php';
        $presupuestoController = new PresupuestoController();
        if (method_exists($presupuestoController, $action)) {
            $presupuestoController->$action();
        }
        break;

    case 'pago':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=showLogin");
            exit();
        }
        require_once 'controllers/PagoController.php';
        $pagoController = new PagoController();
        if (method_exists($pagoController, $action)) {
            $pagoController->$action();
        }
        break;
    
    case 'plataforma':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: " . BASE_URL . "index.php?controller=auth&action=showLogin");
            exit();
        }
        require_once 'controllers/PlataformaController.php';
        $plataformaController = new PlataformaController();
        if (method_exists($plataformaController, $action)) {
            $plataformaController->$action();
        }
        break;

    case 'usuario':
        if (!isset($_SESSION['usuario_id'])) { header("Location: index.php"); exit(); }
        require_once 'controllers/UsuarioController.php';
        $usuarioController = new UsuarioController();
        if (method_exists($usuarioController, $action)) { $usuarioController->$action(); }
        break;

    default:
        echo "<h1 style='text-align:center; color:red; margin-top:50px;'>Error 404: Controlador no encontrado.</h1>";
        break;
}
?>