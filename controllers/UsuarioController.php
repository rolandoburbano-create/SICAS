<?php
require_once 'models/Usuario.php';

class UsuarioController {
    
    public function create() {
        // 🔒 EL CANDADO: Solo el Administrador (Rol 1) puede crear usuarios
        AuthHelper::permitir([1]); 
        
        require_once 'views/layout/header.php';
        require_once 'views/usuarios/create.php';
        require_once 'views/layout/footer.php';
    }

    public function store() {
        AuthHelper::permitir([1]);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuarioModel = new Usuario();
            
            $datos = [
                'id_rol' => $_POST['id_rol'], // ID del rol Supervisor (normalmente 4)
                'tipo_persona' => $_POST['tipo_persona'],
                'tipo_documento' => $_POST['tipo_documento'],
                'documento' => $_POST['documento'],
                'nombres' => $_POST['nombres'],
                'apellidos' => $_POST['apellidos'],
                'tipo_vinculacion' => $_POST['tipo_vinculacion'],
                'secretaria' => $_POST['secretaria'],
                'correo' => $_POST['correo']
            ];

            if ($usuarioModel->registrar($datos)) {
                echo "<script>alert('Supervisor registrado exitosamente. Su contraseña es su número de documento.'); window.location.href='index.php?controller=contrato&action=index';</script>";
            } else {
                echo "<script>alert('Error al registrar. Verifique si el documento o correo ya existen.'); window.history.back();</script>";
            }
        }
    }
}