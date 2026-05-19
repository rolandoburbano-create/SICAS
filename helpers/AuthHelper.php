<?php
class AuthHelper {
    
    // Validar si el rol del usuario está en la lista de permitidos
    public static function permitir($rolesPermitidos) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Si no hay sesión o no tiene rol, lo mandamos al login
        if (!isset($_SESSION['usuario_rol'])) {
            header("Location: index.php?controller=auth&action=showLogin");
            exit();
        }

        $rol_actual = $_SESSION['usuario_rol'];

        // Si su rol no está permitido, le mostramos una alerta y lo devolvemos
        if (!in_array($rol_actual, $rolesPermitidos)) {
            echo "<script>
                    alert('ACCESO DENEGADO: Su rol no tiene permisos para esta acción.');
                    window.location.href = 'index.php?controller=home&action=index';
                  </script>";
            exit();
        }
    }

    // Funciones rápidas para ocultar botones visuales
    public static function esAdmin() { return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 1; }
    public static function esFinanciero() { return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 2; }
    public static function esConsulta() { return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 3; }
    public static function esSupervisor() { return isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] == 4; }
}