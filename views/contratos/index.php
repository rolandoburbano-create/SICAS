<div class="bg-base-100 p-6 rounded-box shadow-sm border border-base-200">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Gestión de Contratos</h1>
            <p class="text-sm opacity-70">Listado general de contratación (Ley 80/93).</p>
        </div>
        <a href="<?php echo BASE_URL; ?>index.php?controller=contrato&action=create" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Nuevo Contrato
        </a>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success mb-6 shadow-sm">
            <i class="fa-solid fa-circle-check"></i>
            <span>Operación realizada con éxito.</span>
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
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contratos)): ?>
                    <tr>
                        <td colspan="6" class="p-10 text-center opacity-50">
                            <i class="fa-regular fa-folder-open text-4xl mb-2"></i>
                            <p>No hay contratos registrados.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contratos as $c): ?>
                        <tr>
                            <td class="font-medium"><?php echo htmlspecialchars($c['numero_contrato']); ?></td>
                            <td><?php echo htmlspecialchars($c['nombre_razon_social']); ?></td>
                            <td><?php echo htmlspecialchars($c['tipo_contrato']); ?></td>
                            <td class="font-semibold">$<?php echo number_format($c['valor_total'], 2, ',', '.'); ?></td>
                            <td>
                                <?php 
                                    // Badges semánticos de DaisyUI
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
                                <a href="index.php?controller=contrato&action=show&id=<?= $c['id_contrato'] ?>" class="btn btn-sm btn-ghost text-info tooltip" data-tip="Ver Detalles">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <?php if(AuthHelper::esAdmin() || AuthHelper::esFinanciero() || AuthHelper::esSupervisor()): ?>
                                    <a href="index.php?controller=contrato&action=edit&id=<?= $c['id_contrato'] ?>" class="btn btn-sm btn-ghost text-warning tooltip" data-tip="Editar Contrato">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>