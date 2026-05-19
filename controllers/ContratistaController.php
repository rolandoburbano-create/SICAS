<?php
require_once 'models/Contratista.php';

class ContratistaController {
    public function create() {
        // 🔒 EL CANDADO: Solo el Administrador (Rol 1) puede entrar aquí
        AuthHelper::permitir([1]); 
        
        require_once 'views/layout/header.php';
        require_once 'views/contratistas/create.php';
        require_once 'views/layout/footer.php';
    }

 // Recibir los datos del formulario POST y guardarlos
    public function store() {
        AuthHelper::permitir([1]); 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contratistaModel = new Contratista();
            
            // Sanitizar todas las entradas
            $datos = [
                'tipo_persona' => trim($_POST['tipo_persona']),
                'tipo_documento' => trim($_POST['tipo_documento']),
                'documento' => trim($_POST['documento']),
                'nombre_razon_social' => trim($_POST['nombre_razon_social']),
                'genero' => trim($_POST['genero']),
                'direccion' => trim($_POST['direccion']),
                'telefono' => trim($_POST['telefono']),
                'correo' => trim($_POST['correo']),
                'entidad_bancaria' => trim($_POST['entidad_bancaria']),
                'tipo_cuenta' => trim($_POST['tipo_cuenta']),
                'numero_cuenta' => trim($_POST['numero_cuenta'])
            ];

            $resultado = $contratistaModel->registrar($datos, $_SESSION['usuario_id']);

            if ($resultado === true) {
                echo "<script>
                        alert('Contratista registrado exitosamente.');
                        window.location.href='" . BASE_URL . "index.php?controller=contrato&action=create';
                      </script>";
            } else if ($resultado === "duplicado") {
                echo "<script>
                        alert('Error: Ya existe un contratista con ese número de documento.');
                        window.history.back();
                      </script>";
            } else {
                echo "<script>
                        alert('Error en el sistema al registrar.');
                        window.history.back();
                      </script>";
            }
        }
    }
}
?>