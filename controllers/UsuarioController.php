<?php
require_once 'models/Usuario.php';
require_once 'helpers/AuthHelper.php';

class UsuarioController {
    
    public function index() {
        AuthHelper::permitir([1]);

        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->listarTodos();

        require_once 'views/layout/header.php';
        require_once 'views/usuarios/index.php';
        require_once 'views/layout/footer.php';
    }

    public function store() {
        AuthHelper::permitir([1]);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();
            
            $datos = [
                'documento' => $_POST['documento'],
                'nombres'   => $_POST['nombres'],
                'apellidos' => $_POST['apellidos'],
                'correo'    => $_POST['correo'],
                'password'  => $_POST['password'],
                'id_rol'    => $_POST['id_rol']
            ];

            if($usuario->registrar($datos)){
                header("Location: index.php?controller=usuario&action=index");
                exit();
            } else {
                die("<div style='color:red; padding:20px; font-family:sans-serif;'>
                        <b>Error de base de datos:</b> Es posible que el correo ingresado ya esté registrado para otro funcionario.
                     </div>");
            }
        }
    }

    public function update() {
        AuthHelper::permitir([1]);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();
            
            $datos = [
                'id_usuario' => $_POST['id_usuario'],
                'nombres'    => $_POST['nombres'],
                'apellidos'  => $_POST['apellidos'],
                'correo'     => $_POST['correo'],
                'id_rol'     => $_POST['id_rol'],
                'estado'     => $_POST['estado']
            ];

            if($usuario->actualizar($datos)){
                header("Location: index.php?controller=usuario&action=index");
                exit();
            } else {
                die("<div style='color:red; padding:20px; font-family:sans-serif;'>
                        <b>Error:</b> No se pudo actualizar el usuario.
                     </div>");
            }
        }
    }

    public function delete() {
        AuthHelper::permitir([1]);

        if (isset($_GET['id'])) {
            $usuario = new Usuario();
            $usuario->eliminar($_GET['id']);
            header("Location: index.php?controller=usuario&action=index");
        }
    }

    public function updatePassword() {
        AuthHelper::permitir([1]);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();
            
            $id_usuario = $_POST['id_usuario'];
            $nueva_password = $_POST['nueva_password'];

            if($usuario->cambiarPassword($id_usuario, $nueva_password)){
                header("Location: index.php?controller=usuario&action=index");
                exit();
            } else {
                die("<div style='color:red; padding:20px; font-family:sans-serif;'>
                        <b>Error:</b> No se pudo actualizar la contraseña.
                     </div>");
            }
        }
    }
}
