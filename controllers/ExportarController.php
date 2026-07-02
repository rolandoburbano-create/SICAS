<?php
require_once 'config/Conexion.php';
require_once 'helpers/AuthHelper.php';

class ExportarController {

    private $fieldLabels = [
        'contratos' => [
            'numero_contrato'    => 'No. Contrato',
            'objeto_contrato'    => 'Objeto del Contrato',
            'valor_total'        => 'Valor Total',
            'forma_pago'         => 'Forma de Pago',
            'nombre_contratista' => 'Contratista',
            'nombre_supervisor'  => 'Supervisor',
            'tipo_contrato'      => 'Tipo de Contrato',
            'modalidad_seleccion'=> 'Modalidad de Selección',
            'fuente_recursos'    => 'Fuente de Recursos',
            'secretaria'         => 'Secretaría',
            'linea_estrategica'  => 'Línea Estratégica',
            'bpin'               => 'Código BPIN',
            'fecha_firma'        => 'Fecha de Firma',
            'fecha_inicio'       => 'Fecha de Inicio',
            'fecha_terminacion'  => 'Fecha de Terminación Pactada',
            'fecha_terminacion_real' => 'Fecha de Terminación Real',
            'plazo_ejecucion'    => 'Plazo de Ejecución',
            'plazo_ejecucion_real' => 'Plazo Real Ejecutado',
            'cdp'                => 'No. CDP',
            'rp'                 => 'No. RP',
            'rubro_presupuestal' => 'Rubro Presupuestal',
            'link_secop'         => 'Enlace SECOP',
            'estado_contrato'    => 'Estado del Contrato',
            'estado'             => 'Estado General',
            'fecha_elaboracion'  => 'Fecha de Elaboración',
            'fecha_liquidacion'  => 'Fecha de Liquidación',
            'tiene_prorroga'     => 'Tiene Prórroga',
            'numero_prorroga'    => 'No. Prórroga',
            'tiempo_prorroga'    => 'Tiempo Prórroga',
            'tiene_suspension'   => 'Tiene Suspensión',
            'numero_suspension'  => 'No. Suspensión',
            'duracion_suspension'=> 'Duración Suspensión (días)',
            'tiene_reinicio'     => 'Tiene Reinicio',
            'numero_reinicio'    => 'No. Reinicio',
            'fecha_reinicio'     => 'Fecha de Reinicio',
            'tiene_cesion'       => 'Tiene Cesión',
            'fecha_cesion'       => 'Fecha de Cesión',
            'id_nuevo_contratista' => 'Nuevo Contratista (Cesión)',
        ],
        'contratistas' => [
            'tipo_persona'       => 'Tipo de Persona',
            'tipo_documento'     => 'Tipo de Documento',
            'documento'          => 'No. Documento',
            'nombre_razon_social'=> 'Nombre / Razón Social',
            'genero'             => 'Género',
            'direccion'          => 'Dirección',
            'telefono'           => 'Teléfono',
            'correo'             => 'Correo Electrónico',
            'entidad_bancaria'   => 'Entidad Bancaria',
            'tipo_cuenta'        => 'Tipo de Cuenta',
            'numero_cuenta'      => 'No. de Cuenta',
        ]
    ];

    private $defaultFields = [
        'contratos'    => ['numero_contrato', 'objeto_contrato', 'valor_total', 'forma_pago', 'nombre_contratista', 'nombre_supervisor', 'tipo_contrato', 'modalidad_seleccion', 'secretaria', 'fecha_firma', 'fecha_inicio', 'fecha_terminacion', 'plazo_ejecucion', 'estado_contrato'],
        'contratistas' => ['tipo_persona', 'tipo_documento', 'documento', 'nombre_razon_social', 'direccion', 'telefono', 'correo', 'entidad_bancaria', 'tipo_cuenta', 'numero_cuenta'],
    ];

    public function index() {
        AuthHelper::permitir([1]);

        require_once 'views/layout/header.php';
        require_once 'views/exportar/index.php';
        require_once 'views/layout/footer.php';
    }

    public function exportar() {
        AuthHelper::permitir([1]);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=exportar&action=index");
            exit();
        }

        $entidad   = $_POST['entidad'] ?? 'contratos';
        $campos    = $_POST['campos'] ?? [];
        $formato   = $_POST['formato'] ?? 'csv';

        if (empty($campos)) {
            echo "<script>alert('Debe seleccionar al menos un campo.'); window.history.back();</script>";
            exit();
        }

        $db = new Conexion();
        $conn = $db->getConnection();
        $datos = $this->obtenerDatos($conn, $entidad, $campos);
        $labels = $this->fieldLabels[$entidad];

        // Mapear labels en orden de selección
        $headers = [];
        foreach ($campos as $c) {
            $headers[] = $labels[$c] ?? $c;
        }

        switch ($formato) {
            case 'csv':
                $this->exportarCSV($headers, $datos);
                break;
            case 'xls':
                $this->exportarXLS($headers, $datos, $entidad);
                break;
            case 'pdf':
                $this->exportarPDF($headers, $datos, $entidad);
                break;
            default:
                $this->exportarCSV($headers, $datos);
        }
    }

    private function obtenerDatos($conn, $entidad, $campos) {
        if ($entidad === 'contratos') {
            $mapaCampos = [
                'nombre_contratista' => "con.nombre_razon_social AS nombre_contratista",
                'nombre_supervisor'  => "CONCAT(u.nombres, ' ', u.apellidos) AS nombre_supervisor",
            ];
            $selects = [];
            foreach ($campos as $c) {
                if (isset($mapaCampos[$c])) {
                    $selects[] = $mapaCampos[$c];
                } else {
                    $selects[] = "c.{$c}";
                }
            }
            $sql = "SELECT " . implode(', ', $selects) . "
                    FROM contratos c
                    LEFT JOIN contratistas con ON c.id_contratista = con.id_contratista
                    LEFT JOIN usuarios u ON c.id_supervisor = u.id_usuario
                    ORDER BY c.fecha_elaboracion DESC";
        } else {
            $selects = array_map(function($c) { return "{$c}"; }, $campos);
            $sql = "SELECT " . implode(', ', $selects) . "
                    FROM contratistas
                    ORDER BY nombre_razon_social ASC";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function exportarCSV($headers, $datos) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="exportacion_' . date('Ymd_His') . '.csv"');
        header('Pragma: no-cache');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
        fputcsv($output, $headers);

        foreach ($datos as $fila) {
            $row = [];
            foreach ($fila as $valor) {
                $row[] = $valor ?? '';
            }
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    }

    private function exportarXLS($headers, $datos, $entidad) {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="exportacion_' . date('Ymd_His') . '.xls"');
        header('Pragma: no-cache');

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="UTF-8"><style>td, th { border: 1px solid #ccc; padding: 4px 8px; } th { background: #1B5E20; color: white; font-weight: bold; }</style></head>';
        echo '<body><table>';
        echo '<tr><th>' . implode('</th><th>', array_map('htmlspecialchars', $headers)) . '</th></tr>';
        foreach ($datos as $fila) {
            echo '<tr>';
            foreach ($fila as $valor) {
                echo '<td>' . htmlspecialchars($valor ?? '') . '</td>';
            }
            echo '</tr>';
        }
        echo '</table></body></html>';
        exit();
    }

    private function exportarPDF($headers, $datos, $entidad) {
        $titulo = $entidad === 'contratos' ? 'Contratos' : 'Contratistas';
        $fecha = date('d/m/Y H:i:s');
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Exportación - <?= $titulo ?></title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 10pt; margin: 20px; }
                h1 { color: #1B5E20; border-bottom: 2px solid #1B5E20; padding-bottom: 6px; }
                .fecha { color: #666; font-size: 9pt; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background: #1B5E20; color: white; padding: 6px 8px; text-align: left; font-size: 8pt; }
                td { padding: 4px 8px; border-bottom: 1px solid #ddd; font-size: 8pt; }
                tr:nth-child(even) { background: #f5f5f5; }
                .footer { margin-top: 20px; font-size: 8pt; color: #999; text-align: center; border-top: 1px solid #ddd; padding-top: 8px; }
                @media print { body { margin: 10px; } }
            </style>
        </head>
        <body>
            <h1>Exportación de <?= $titulo ?></h1>
            <p class="fecha">Generado el <?= $fecha ?> - Sistema SICAS - Alcaldía de Silvia</p>
            <table>
                <thead>
                    <tr><th><?= implode('</th><th>', array_map('htmlspecialchars', $headers)) ?></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($datos as $fila): ?>
                    <tr>
                        <?php foreach ($fila as $valor): ?>
                        <td><?= htmlspecialchars($valor ?? '') ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="footer">Sistema de Información Contractual - Alcaldía del Municipio de Silvia, Cauca.</p>
            <script>window.print();</script>
        </body>
        </html>
        <?php
        exit();
    }
}
