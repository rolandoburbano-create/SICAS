<div class="bg-base-100 p-6 rounded-box shadow-sm border border-base-200">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
        <div>
            <h1 class="text-2xl font-bold">
                <?php if(isset($_GET['q'])): ?>
                    <i class="fa-solid fa-search text-primary mr-2"></i> Resultados de búsqueda
                <?php elseif(isset($_GET['from']) && $_GET['from'] == 'pagos'): ?>
                    <i class="fa-solid fa-money-bill-wave text-success mr-2"></i> Seleccionar Contrato para Pago
                <?php else: ?>
                    Gestión de Contratos
                <?php endif; ?>
            </h1>
            <p class="text-sm opacity-70">
                <?php if(isset($_GET['q'])): ?>
                    Contratos que coinciden con "<?= htmlspecialchars($_GET['q']) ?>"
                <?php elseif(isset($_GET['from']) && $_GET['from'] == 'pagos'): ?>
                    Elija un contrato para registrar un pago
                <?php else: ?>
                    Listado general de contratación (Ley 80/93).
                <?php endif; ?>
            </p>
        </div>
        <div class="flex gap-2">
            <form method="GET" action="index.php" class="join">
                <input type="hidden" name="controller" value="contrato">
                <input type="hidden" name="action" value="index">
                <input type="text" name="q" placeholder="Buscar contrato..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="input input-bordered input-sm join-item w-48">
                <button type="submit" class="btn btn-sm btn-primary join-item">
                    <i class="fa-solid fa-search"></i>
                </button>
                <?php if(isset($_GET['q']) && $_GET['q'] !== ''): ?>
                    <a href="index.php?controller=contrato&action=index<?= isset($_GET['from']) ? '&from=pagos' : '' ?>" class="btn btn-sm btn-ghost join-item">
                        <i class="fa-solid fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>
            <?php if(AuthHelper::esAdmin() || AuthHelper::esRadicacion()): ?>
                <a href="<?php echo BASE_URL; ?>index.php?controller=contrato&action=create" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Nuevo Contrato
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success mb-6 shadow-sm">
            <i class="fa-solid fa-circle-check"></i>
            <span>Operación realizada con éxito.</span>
        </div>
    <?php endif; ?>

    <?php if(!empty($alertas_vencimiento)): ?>
        <div class="alert alert-error bg-red-50 border-2 border-red-400 text-red-900 shadow-lg mb-6 p-6">
            <div class="flex items-start gap-4">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-red-600 mt-1"></i>
                <div class="flex-1">
                    <h3 class="font-black text-xl uppercase tracking-wide text-red-700">
                        ⚠ Contratos por Vencer
                    </h3>
                    <p class="text-sm text-red-600 font-semibold mt-1">
                        Los siguientes contratos están próximos a su fecha de terminación (8 días o menos):
                    </p>
                    <ul class="mt-3 space-y-2">
                        <?php foreach($alertas_vencimiento as $alerta): ?>
                        <li class="flex items-center justify-between bg-white/70 p-3 rounded-box border border-red-300 shadow-sm">
                            <div>
                                <a href="index.php?controller=contrato&action=show&id=<?= $alerta['id_contrato'] ?>" class="font-bold text-red-800 hover:underline text-lg">
                                    <?= htmlspecialchars($alerta['numero_contrato']) ?>
                                </a>
                                <span class="text-sm text-red-700 ml-2">— <?= htmlspecialchars($alerta['nombre_razon_social']) ?></span>
                                <p class="text-xs text-red-600 mt-0.5"><?= htmlspecialchars($alerta['objeto_contrato']) ?></p>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <span class="text-xs font-bold text-red-700 uppercase">Vence:</span>
                                <span class="block font-black text-xl <?= $alerta['dias_restantes'] <= 0 ? 'text-red-600' : ($alerta['dias_restantes'] <= 3 ? 'text-orange-600' : 'text-yellow-700') ?>">
                                    <?= date('d/m/Y', strtotime($alerta['fecha_terminacion'])) ?>
                                </span>
                                <span class="badge badge-error badge-sm text-white font-bold mt-1">
                                    <?= $alerta['dias_restantes'] <= 0 ? 'VENCIDO' : ($alerta['dias_restantes'] . ' día(s)') ?>
                                </span>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto rounded-box border border-base-200">
        <table class="table table-zebra w-full">
            <thead class="bg-base-200 text-base-content text-sm">
                <tr>
                    <th>No. Contrato</th>
                    <th>Contratista</th>
                    <th>Tipo</th>
                    <th>Valor Total</th>
                    <?php if(AuthHelper::esSupervisor()): ?>
                    <th>Vencimiento</th>
                    <?php endif; ?>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contratos)): ?>
                    <tr>
                        <td colspan="<?= AuthHelper::esSupervisor() ? 7 : 6 ?>" class="p-10 text-center opacity-50">
                            <i class="fa-regular fa-folder-open text-4xl mb-2"></i>
                            <p>
                                <?php if(isset($_GET['q'])): ?>
                                    No se encontraron contratos con "<?= htmlspecialchars($_GET['q']) ?>".
                                <?php else: ?>
                                    No hay contratos registrados.
                                <?php endif; ?>
                            </p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contratos as $c): ?>
                        <tr>
                            <td class="font-medium"><?php echo htmlspecialchars($c['numero_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($c['nombre_razon_social']); ?></td>
                            <td><?php echo htmlspecialchars($c['tipo_contrato']); ?></td>
                            <td class="font-semibold">$<?php echo number_format($c['valor_total'], 2, ',', '.'); ?></td>
                            <?php if(AuthHelper::esSupervisor()): ?>
                            <td>
                                <?php if(!empty($c['fecha_terminacion']) && $c['fecha_terminacion'] != '0000-00-00'): ?>
                                    <span class="whitespace-nowrap <?= isset($c['dias_restantes']) && $c['dias_restantes'] <= 8 ? 'text-red-600 font-bold' : '' ?>">
                                        <?= date('d/m/Y', strtotime($c['fecha_terminacion'])) ?>
                                        <?php if(isset($c['dias_restantes']) && $c['dias_restantes'] <= 8): ?>
                                            <span class="badge badge-error badge-xs text-white ml-1"><?= $c['dias_restantes'] <= 0 ? 'VENCIDO' : $c['dias_restantes'] . 'd' ?></span>
                                        <?php endif; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="opacity-40">—</span>
                                <?php endif; ?>
                            </td>
                            <?php endif; ?>
                            <td>
                                <?php 
                                    $badge = 'badge-ghost';
                                    if($c['estado_contrato'] == 'En Ejecución') $badge = 'badge-info';
                                    if($c['estado_contrato'] == 'Terminado') $badge = 'badge-success';
                                    if($c['estado_contrato'] == 'Liquidado') $badge = 'badge-primary';
                                    if($c['estado_contrato'] == 'Suspendido') $badge = 'badge-warning';
                                ?>
                                <div class="badge <?php echo $badge; ?> gap-1">
                                    <?php echo $c['estado_contrato']; ?>
                                </div>
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <?php if(isset($_GET['from']) && $_GET['from'] == 'pagos'): ?>
                                    <a href="index.php?controller=contrato&action=show&id=<?= $c['id_contrato'] ?>" class="btn btn-sm btn-primary text-white shadow-xs gap-1">
                                        <i class="fa-solid fa-plus"></i> Registrar Pago
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?controller=contrato&action=show&id=<?= $c['id_contrato'] ?>" class="btn btn-sm btn-ghost text-info tooltip" data-tip="Ver Detalles">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <?php if(AuthHelper::esAdmin()): ?>
                                        <a href="index.php?controller=contrato&action=edit&id=<?= $c['id_contrato'] ?>" class="btn btn-sm btn-ghost text-warning tooltip" data-tip="Editar Contrato">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>