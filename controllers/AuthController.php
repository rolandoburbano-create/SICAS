<?php
require_once 'models/Usuario.php';

class AuthController {
    
    // Muestra la vista de login
    public function showLogin() {
        require_once 'views/auth/login.php';
    }

    // Procesa el formulario de login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = $_POST['correo'];
            $password = $_POST['password'];

            $db = new Conexion();
            $conn = $db->getConnection();
            
            // Asegúrate de que sea un SELECT * para traer todo
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = :correo");
            $stmt->execute([':correo' => $correo]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password'])) {
                // Iniciar la sesión si no está iniciada
                if (session_status() == PHP_SESSION_NONE) { session_start(); }

                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombres'];
                
                // ---> ¡NUEVO! GUARDAMOS EL ROL EN LA SESIÓN <---
                $_SESSION['usuario_rol'] = $usuario['id_rol']; 

                header("Location: " . BASE_URL . "index.php?controller=home&action=index");
                exit();
            } else {
                echo "<script>alert('Credenciales incorrectas'); window.history.back();</script>";
            }
        }
    }
    // Cierra la sesión de forma segura
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "index.php");
        exit();
    }
}
?>