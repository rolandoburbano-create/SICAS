<?php
require_once 'models/Pago.php';

class PagoController {
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $pago = new Pago();
            
            $datos = [
                'id_contrato'   => $_POST['id_contrato'],
                'numero_acta'   => $_POST['numero_acta'],
                'fecha_pago'    => $_POST['fecha_pago'],
                'valor_pagado'  => $_POST['valor_pagado'],
                'observaciones' => $_POST['observaciones'] ?? ''
            ];

            if($pago->registrar($datos)){
                // Redirigir de vuelta al expediente actualizado
                header("Location: index.php?controller=contrato&action=show&id=" . $_POST['id_contrato']);
                exit();
            } else {
                die("Error al registrar el pago en la base de datos.");
            }
        }
    }
}
?>