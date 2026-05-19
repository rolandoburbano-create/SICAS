<div class="space-y-8 pb-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-base-content">Dashboard de Gestión</h1>
            <p class="text-sm opacity-60">Resumen operativo - Alcaldía de Silvia, Cauca</p>
        </div>
        <div class="text-right">
            <span class="badge badge-primary font-bold p-4 shadow-sm">
                <i class="fa-solid fa-calendar-day mr-2"></i> <?= date('Y/m/d') ?>
            </span>
        </div>
    </div>

    <div class="stats stats-vertical lg:stats-horizontal shadow-xl w-full border border-base-300">
        
        <div class="stat bg-base-100">
            <div class="stat-figure text-primary">
                <i class="fa-solid fa-file-contract text-3xl"></i>
            </div>
            <div class="stat-title font-bold">Total Contratos</div>
            <div class="stat-value text-primary"><?= $stats['total_contratos'] ?></div>
            <div class="stat-desc">Registrados en sistema</div>
        </div>
        
        <div class="stat bg-base-100">
            <div class="stat-figure text-success">
                <i class="fa-solid fa-play text-3xl"></i>
            </div>
            <div class="stat-title font-bold">En Ejecución</div>
            <div class="stat-value text-success"><?= $stats['activos'] ?></div>
            <div class="stat-desc">Contratos vigentes hoy</div>
        </div>

        <div class="stat bg-base-100">
            <div class="stat-figure text-primary">
                <i class="fa-solid fa-sack-dollar text-3xl"></i>
            </div>
            <div class="stat-title font-bold">Inversión Total</div>
            <div class="stat-value text-sm md:text-2xl">$<?= number_format($stats['inversion_total'], 0, ',', '.') ?></div>
            <div class="stat-desc text-primary font-semibold">Compromiso presupuestal</div>
        </div>

        <div class="stat bg-base-100">
            <div class="stat-figure text-info">
                <i class="fa-solid fa-users-gear text-3xl"></i>
            </div>
            <div class="stat-title font-bold">Contratistas</div>
            <div class="stat-value text-info"><?= $stats['contratistas'] ?></div>
            <div class="stat-desc">Fichas técnicas creadas</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="card lg:col-span-2 bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <h2 class="card-title text-primary">
                    <i class="fa-solid fa-bell-concierge"></i> Alertas de Vencimiento
                </h2>
                <p class="text-xs opacity-60 mb-4">Contratos activos que finalizan en menos de 30 días.</p>
                
                <div class="overflow-x-auto">
                    <table class="table table-sm w-full">
                        <thead class="bg-base-200">
                            <tr>
                                <th>No. Contrato</th>
                                <th>Fecha Fin</th>
                                <th>Días Restantes</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($alertas)): ?>
                                <tr><td colspan="4" class="text-center opacity-40 py-6 italic">No hay alertas críticas pendientes</td></tr>
                            <?php else: ?>
                                <?php foreach($alertas as $a): ?>
                                    <tr class="hover">
                                        <td class="font-bold"><?= $a['numero_contrato'] ?></td>
                                        <td><?= $a['fecha_terminacion'] ?></td>
                                        <td>
                                            <div class="badge badge-warning font-bold"><?= $a['dias_restantes'] ?> días</div>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-xs btn-ghost text-primary"><i class="fa-solid fa-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card bg-primary text-primary-content shadow-xl">
            <div class="card-body">
                <h2 class="card-title font-black uppercase tracking-widest text-sm italic">Acciones Rápidas</h2>
                <div class="space-y-3 mt-4">
                    <a href="index.php?controller=contrato&action=create" class="btn btn-block bg-white text-primary border-none shadow-md">
                        <i class="fa-solid fa-plus-circle"></i> Nueva Radicación
                    </a>
                    <a href="index.php?controller=contratista&action=create" class="btn btn-block bg-white/20 text-white border-none hover:bg-white/30">
                        <i class="fa-solid fa-user-plus"></i> Crear Contratista
                    </a>
                    <a href="index.php?controller=usuario&action=create" class="btn btn-block bg-white/20 text-white border-none hover:bg-white/30">
                        <i class="fa-solid fa-user-shield"></i> Registrar Supervisor
                    </a>
                </div>
                <div class="mt-6 text-[10px] text-center opacity-70">
                    SISTEMA DE GESTIÓN MUNICIPAL - SILVIA
                </div>
            </div>
        </div>
    </div>
</div>