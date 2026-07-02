<div class="max-w-6xl mx-auto space-y-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-box shadow-sm border border-base-300 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-primary">Ejecución Presupuestal</h1>
                <span class="badge badge-success text-white font-semibold uppercase px-3 py-2 text-xs"><?= htmlspecialchars($contrato['estado']) ?></span>
            </div>
            <p class="text-xs opacity-70 uppercase tracking-wider mt-1">Contrato No. <?= htmlspecialchars($contrato['numero_contrato']) ?> • <?= htmlspecialchars($contrato['nombre_razon_social']) ?></p>
        </div>
        <a href="index.php?controller=contrato&action=show&id=<?= $contrato['id_contrato'] ?>" class="btn btn-ghost btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Volver al Contrato
        </a>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success bg-green-50 border-green-200 text-green-800">
            <i class="fa-solid fa-circle-check"></i> Pago registrado y saldos actualizados correctamente.
        </div>
    <?php endif; ?>

    <div class="space-y-6">

        <!-- Resumen financiero -->
        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body">
                <h3 class="font-bold text-primary flex items-center gap-2"><i class="fa-solid fa-chart-pie"></i> Estado Financiero</h3>
                <div class="space-y-3 text-sm mt-2">
                    <div>
                        <p class="text-xs opacity-60">Valor Total del Contrato</p>
                        <p class="font-bold text-lg">$<?= number_format($contrato['valor_total'], 2, ',', '.') ?></p>
                    </div>
                    <?php if($presupuesto): ?>
                    <div class="border-t pt-2">
                        <p class="text-xs opacity-60">Pagos Realizados</p>
                        <p class="font-semibold text-info"><?= $presupuesto['numero_pagos_realizados'] ?> de <?= $presupuesto['numero_pagos_proyectados'] ?></p>
                    </div>
                    <div class="bg-success/10 p-3 rounded-box border border-success/20">
                        <p class="text-xs font-bold opacity-60 uppercase">Saldo Pendiente</p>
                        <p class="font-bold text-xl text-success">$<?= number_format($presupuesto['saldo_pendiente'], 2, ',', '.') ?></p>
                    </div>
                    <?php else: ?>
                    <div class="bg-warning/10 p-3 rounded-box border border-warning/20">
                        <p class="text-xs font-bold opacity-60 uppercase">Presupuesto sin iniciar</p>
                        <p class="text-sm text-warning mt-1">El saldo actual es igual al valor total del contrato.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Rubros Presupuestales -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-4">
                        <div class="divider divider-start text-primary font-bold uppercase text-xs m-0 flex-1">Rubros Presupuestales</div>
                    </div>

                    <?php if(empty($rubros)): ?>
                        <div class="alert alert-info bg-blue-50 text-blue-800 text-xs border-blue-200">
                            <i class="fa-solid fa-circle-info"></i> No hay rubros presupuestales registrados para este contrato.
                        </div>
                    <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-sm w-full border border-base-200">
                            <thead>
                                <tr class="bg-base-200 text-neutral">
                                    <th class="font-bold text-xs">Rubro</th>
                                    <th class="font-bold text-xs">Vigencia</th>
                                    <th class="font-bold text-xs">Origen del Recurso</th>
                                    <th class="font-bold text-xs">Tipo</th>
                                    <th class="font-bold text-xs text-right">Valor</th>
                                    <?php if(AuthHelper::esAdmin() || AuthHelper::esFinanciero()): ?>
                                    <th class="font-bold text-xs text-center">Acción</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($rubros as $r): ?>
                                <tr class="hover:bg-base-200/50 transition-colors">
                                    <td class="font-mono text-xs font-bold"><?= htmlspecialchars($r['rubro']) ?></td>
                                    <td><?= htmlspecialchars($r['vigencia']) ?></td>
                                    <td><?= htmlspecialchars($r['origen_recurso']) ?></td>
                                    <td><?= htmlspecialchars($r['tipo']) ?></td>
                                    <td class="text-right font-bold text-success">$<?= number_format($r['valor'], 2, ',', '.') ?></td>
                                    <?php if(AuthHelper::esAdmin() || AuthHelper::esFinanciero()): ?>
                                    <td class="text-center">
                                        <div class="flex gap-1 justify-center">
                                            <button class="btn btn-ghost btn-xs text-warning" onclick="editarRubro(<?= $r['id_rubro'] ?>, '<?= htmlspecialchars(addslashes($r['rubro'])) ?>', '<?= htmlspecialchars(addslashes($r['vigencia'])) ?>', '<?= htmlspecialchars(addslashes($r['origen_recurso'])) ?>', '<?= htmlspecialchars(addslashes($r['tipo'])) ?>', <?= $r['valor'] ?>)" title="Editar">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <a href="index.php?controller=presupuesto&action=eliminarRubro&id=<?= $r['id_rubro'] ?>&id_contrato=<?= $contrato['id_contrato'] ?>" class="btn btn-ghost btn-xs text-error" onclick="return confirm('¿Eliminar este rubro presupuestal?')" title="Eliminar">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-base-200 font-bold">
                                    <td colspan="4" class="text-right">Total Rubros</td>
                                    <td class="text-right text-success">$<?= number_format(array_sum(array_column($rubros, 'valor')), 2, ',', '.') ?></td>
                                    <?php if(AuthHelper::esAdmin() || AuthHelper::esFinanciero()): ?>
                                    <td></td>
                                    <?php endif; ?>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <?php endif; ?>

                    <!-- Botón para agregar rubro (Admin/Financiero) -->
                    <?php if(AuthHelper::esAdmin() || AuthHelper::esFinanciero()): ?>
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary text-white gap-2" onclick="modal_crear_rubro.showModal()">
                            <i class="fa-solid fa-plus"></i> Agregar Rubro Presupuestal
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

<!-- Modal Crear Rubro -->
<dialog id="modal_crear_rubro" class="modal">
    <div class="modal-box bg-white">
        <h3 class="font-bold text-lg text-primary mb-4 border-b pb-2">Nuevo Rubro Presupuestal</h3>
        <form action="index.php?controller=presupuesto&action=guardarRubro" method="POST">
            <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">

            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text font-bold">Rubro Presupuestal *</span></label>
                <input type="text" name="rubro" required class="input input-bordered w-full" placeholder="Ej. 2.3.2.02.02.08">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Vigencia</span></label>
                    <input type="text" name="vigencia" class="input input-bordered w-full" placeholder="Ej. 2026">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Origen del Recurso</span></label>
                    <select name="origen_recurso" class="select select-bordered w-full">
                        <option value="">Seleccione...</option>
                        <option value="Recursos Propios">Recursos Propios</option>
                        <option value="SGP - Salud">SGP - Salud</option>
                        <option value="SGP - Educación">SGP - Educación</option>
                        <option value="SGP - Propósito General">SGP - Propósito General</option>
                        <option value="Sistema General de Regalías">SGR</option>
                        <option value="Cofinanciación">Cofinanciación</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Tipo</span></label>
                    <select name="tipo" class="select select-bordered w-full">
                        <option value="">Seleccione...</option>
                        <option value="Funcionamiento">Funcionamiento</option>
                        <option value="Inversión">Inversión</option>
                        <option value="Deuda Pública">Deuda Pública</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Valor ($) *</span></label>
                    <input type="number" step="0.01" name="valor" required class="input input-bordered w-full" placeholder="0.00">
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="modal_crear_rubro.close()">Cancelar</button>
                <button type="submit" class="btn btn-primary text-white">Guardar Rubro</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Modal Editar Rubro -->
<dialog id="modal_editar_rubro" class="modal">
    <div class="modal-box bg-white">
        <h3 class="font-bold text-lg text-primary mb-4 border-b pb-2">Editar Rubro Presupuestal</h3>
        <form action="index.php?controller=presupuesto&action=actualizarRubro" method="POST">
            <input type="hidden" name="id_rubro" id="edit_id_rubro" value="">
            <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">

            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text font-bold">Rubro Presupuestal *</span></label>
                <input type="text" name="rubro" id="edit_rubro" required class="input input-bordered w-full">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Vigencia</span></label>
                    <input type="text" name="vigencia" id="edit_vigencia" class="input input-bordered w-full">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Origen del Recurso</span></label>
                    <select name="origen_recurso" id="edit_origen_recurso" class="select select-bordered w-full">
                        <option value="">Seleccione...</option>
                        <option value="Recursos Propios">Recursos Propios</option>
                        <option value="SGP - Salud">SGP - Salud</option>
                        <option value="SGP - Educación">SGP - Educación</option>
                        <option value="SGP - Propósito General">SGP - Propósito General</option>
                        <option value="Sistema General de Regalías">SGR</option>
                        <option value="Cofinanciación">Cofinanciación</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-3">
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Tipo</span></label>
                    <select name="tipo" id="edit_tipo" class="select select-bordered w-full">
                        <option value="">Seleccione...</option>
                        <option value="Funcionamiento">Funcionamiento</option>
                        <option value="Inversión">Inversión</option>
                        <option value="Deuda Pública">Deuda Pública</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold">Valor ($) *</span></label>
                    <input type="number" step="0.01" name="valor" id="edit_valor" required class="input input-bordered w-full">
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="modal_editar_rubro.close()">Cancelar</button>
                <button type="submit" class="btn btn-warning text-white">Actualizar Rubro</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function editarRubro(id, rubro, vigencia, origenRecurso, tipo, valor) {
    document.getElementById('edit_id_rubro').value = id;
    document.getElementById('edit_rubro').value = rubro;
    document.getElementById('edit_vigencia').value = vigencia;
    document.getElementById('edit_origen_recurso').value = origenRecurso;
    document.getElementById('edit_tipo').value = tipo;
    document.getElementById('edit_valor').value = valor;
    modal_editar_rubro.showModal();
}
</script>
