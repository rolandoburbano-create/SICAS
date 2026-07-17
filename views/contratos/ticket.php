<div class="ticket">
    <div class="ticket-header">
        <h2>Alcaldía de Silvia - Cauca</h2>
        <div class="sub">SICAS · Sistema de Información Contractual<br>Comprobante de Radicación</div>
    </div>

    <div class="ticket-body">
        <div class="ticket-row">
            <span class="label">No. Contrato</span>
            <span class="value"><?= htmlspecialchars($contrato['numero_contrato']) ?></span>
        </div>
        <div class="ticket-row">
            <span class="label">Fecha Radicación</span>
            <span class="value"><?= htmlspecialchars($contrato['fecha_firma'] ?? date('Y-m-d')) ?></span>
        </div>
        <div class="ticket-row">
            <span class="label">BPIN</span>
            <span class="value"><?= htmlspecialchars($contrato['bpin'] ?? 'N/A') ?></span>
        </div>
        <div class="ticket-row">
            <span class="label">No. CDP</span>
            <span class="value"><?= htmlspecialchars($contrato['cdp'] ?? 'N/A') ?></span>
        </div>
        <div class="ticket-row">
            <span class="label">Fecha CDP</span>
            <span class="value"><?= $contrato['fecha_cdp'] && $contrato['fecha_cdp'] !== '0000-00-00' ? htmlspecialchars($contrato['fecha_cdp']) : 'N/A' ?></span>
        </div>
        <div class="ticket-row">
            <span class="label">Valor CDP</span>
            <span class="value">$<?= number_format((float)($contrato['valor_cdp'] ?? 0), 0, ',', '.') ?></span>
        </div>
        <div class="ticket-row highlight">
            <span class="label">Valor Contrato</span>
            <span class="value">$<?= number_format((float)$contrato['valor_total'], 0, ',', '.') ?></span>
        </div>
        <div class="ticket-row diff">
            <span class="label">Diferencia</span>
            <span class="value">$<?= number_format($diferencia, 0, ',', '.') ?></span>
        </div>
        <div class="ticket-row">
            <span class="label">Plazo</span>
            <span class="value"><?= htmlspecialchars($contrato['plazo_ejecucion'] ?? 'N/A') ?></span>
        </div>
        <div class="ticket-row">
            <span class="label">DV Contratista</span>
            <span class="value"><?= $digitoVerificacion ?></span>
        </div>
    </div>

    <div class="ticket-qr">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=<?= urlencode($qrUrl) ?>" alt="QR Contrato">
        <p><?= htmlspecialchars($qrUrl) ?></p>
    </div>

    <div class="ticket-footer">
        Generado el <?= date('d/m/Y H:i:s') ?> · Alcaldía del Municipio de Silvia, Cauca
    </div>
</div>

<div class="actions">
    <button class="btn-print" onclick="window.print()">
        <i class="fa-solid fa-print"></i> Imprimir Ticket
    </button>
    <a href="index.php?controller=contrato&action=index" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Volver al Listado
    </a>
</div>


