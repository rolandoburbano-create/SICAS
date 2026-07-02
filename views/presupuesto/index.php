<div class="max-w-6xl mx-auto space-y-6 pb-12">
    <div class="flex justify-between items-center bg-white p-6 rounded-box shadow-sm border border-base-300">
        <div>
            <h1 class="text-2xl font-bold text-primary">Gestión Presupuestal General</h1>
            <p class="text-xs opacity-70 uppercase tracking-wider mt-1">Rubros presupuestales de todos los contratos</p>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card bg-primary text-primary-content shadow-sm">
            <div class="card-body text-center">
                <p class="text-xs font-bold uppercase tracking-widest opacity-80">Total Contratos</p>
                <p class="text-3xl font-black"><?= $totales['total_contratos'] ?></p>
            </div>
        </div>
        <div class="card bg-info text-info-content shadow-sm">
            <div class="card-body text-center">
                <p class="text-xs font-bold uppercase tracking-widest opacity-80">Total Rubros</p>
                <p class="text-3xl font-black"><?= $totales['total_rubros'] ?></p>
            </div>
        </div>
        <div class="card bg-success text-success-content shadow-sm">
            <div class="card-body text-center">
                <p class="text-xs font-bold uppercase tracking-widest opacity-80">Gran Total Presupuestado</p>
                <p class="text-3xl font-black">$<?= number_format($totales['gran_total'], 0, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <!-- Tabla de contratos con rubros -->
    <div class="card bg-base-100 shadow-sm border border-base-300">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-sm w-full border border-base-200">
                    <thead>
                        <tr class="bg-base-200 text-neutral">
                            <th class="font-bold text-xs">Contrato</th>
                            <th class="font-bold text-xs">Contratista</th>
                            <th class="font-bold text-xs text-center">No. Rubros</th>
                            <th class="font-bold text-xs text-right">Valor Contrato</th>
                            <th class="font-bold text-xs text-right">Total Presupuestado</th>
                            <th class="font-bold text-xs text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($contratos)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-sm opacity-60 py-8">No hay contratos registrados.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($contratos as $c): ?>
                        <tr class="hover:bg-base-200/50 transition-colors">
                            <td class="font-bold text-neutral"><?= htmlspecialchars($c['numero_contrato']) ?></td>
                            <td><?= htmlspecialchars($c['nombre_razon_social']) ?></td>
                            <td class="text-center">
                                <span class="badge badge-<?= $c['total_rubros'] > 0 ? 'success' : 'ghost' ?> badge-sm">
                                    <?= $c['total_rubros'] ?>
                                </span>
                            </td>
                            <td class="text-right font-mono text-sm">$<?= number_format($c['valor_total'], 0, ',', '.') ?></td>
                            <td class="text-right font-mono text-sm font-bold text-success">$<?= number_format($c['total_presupuestado'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <a href="index.php?controller=presupuesto&action=gestionar&id=<?= $c['id_contrato'] ?>" class="btn btn-ghost btn-xs text-primary" title="Administrar Rubros">
                                    <i class="fa-solid fa-money-bill-trend-up"></i> Rubros
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
