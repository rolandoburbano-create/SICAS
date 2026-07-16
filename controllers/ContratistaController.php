<?php
require_once 'models/Contratista.php';
require_once 'helpers/AuthHelper.php';

class ContratistaController {
    
    public function index() {
        AuthHelper::permitir([1]);
        $contratistaModel = new Contratista();
        $termino = isset($_GET['q']) ? trim($_GET['q']) : '';
        $contratistas = $termino ? $contratistaModel->buscar($termino) : $contratistaModel->listarTodosCompleto();

        require_once 'views/layout/header.php';
        require_once 'views/contratistas/index.php';
        require_once 'views/layout/footer.php';
    }

    public function create() {
        AuthHelper::permitir([1]); 
        
        require_once 'views/layout/header.php';
        require_once 'views/contratistas/create.php';
        require_once 'views/layout/footer.php';
    }

    public function store() {
        AuthHelper::permitir([1]); 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contratistaModel = new Contratista();
            
            $datos = [
                'tipo_persona' => trim($_POST['tipo_persona']),
                'tipo_documento' => trim($_POST['tipo_documento']),
                'documento' => trim($_POST['documento']),
                'digito_verificacion' => trim($_POST['digito_verificacion'] ?? ''),
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
                header("Location: " . BASE_URL . "index.php?controller=contratista&action=index");
                exit();
            } else if ($resultado === "duplicado") {
                echo "<script>
                        alert('Error: Ya existe un contratista con ese numero de documento.');
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

    public function edit() {
        AuthHelper::permitir([1]);
        if (!isset($_GET['id'])) {
            header("Location: " . BASE_URL . "index.php?controller=contratista&action=index");
            exit();
        }
        $contratistaModel = new Contratista();
        $contratista = $contratistaModel->obtenerPorId($_GET['id']);
        if (!$contratista) {
            die("Contratista no encontrado.");
        }

        require_once 'views/layout/header.php';
        require_once 'views/contratistas/edit.php';
        require_once 'views/layout/footer.php';
    }

    public function update() {
        AuthHelper::permitir([1]);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contratistaModel = new Contratista();
            $datos = [
                'id_contratista' => $_POST['id_contratista'],
                'tipo_persona' => trim($_POST['tipo_persona']),
                'tipo_documento' => trim($_POST['tipo_documento']),
                'documento' => trim($_POST['documento']),
                'digito_verificacion' => trim($_POST['digito_verificacion'] ?? ''),
                'nombre_razon_social' => trim($_POST['nombre_razon_social']),
                'genero' => trim($_POST['genero']),
                'direccion' => trim($_POST['direccion']),
                'telefono' => trim($_POST['telefono']),
                'correo' => trim($_POST['correo']),
                'entidad_bancaria' => trim($_POST['entidad_bancaria']),
                'tipo_cuenta' => trim($_POST['tipo_cuenta']),
                'numero_cuenta' => trim($_POST['numero_cuenta'])
            ];
            $contratistaModel->actualizar($datos);
            header("Location: " . BASE_URL . "index.php?controller=contratista&action=index");
        }
    }

    public function buscarJson() {
        $contratistaModel = new Contratista();
        $termino = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($termino === '') {
            $resultados = $contratistaModel->listarTodos();
        } else {
            $resultados = $contratistaModel->buscar($termino);
        }
        $data = array_map(function($c) {
            return [
                'id' => $c['id_contratista'],
                'documento' => $c['documento'],
                'nombre' => $c['nombre_razon_social']
            ];
        }, $resultados);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function delete() {
        AuthHelper::permitir([1]);
        if (isset($_GET['id'])) {
            $contratistaModel = new Contratista();
            $contratistaModel->eliminar($_GET['id']);
            header("Location: " . BASE_URL . "index.php?controller=contratista&action=index");
        }
    }
}
