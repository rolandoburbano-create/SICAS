<div class="max-w-6xl mx-auto space-y-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-box shadow-sm border border-base-300 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-primary">Contrato No. <?= htmlspecialchars($contrato['numero_contrato']) ?></h1>
                <span class="badge badge-success text-white font-semibold uppercase px-3 py-2 text-xs"><?= htmlspecialchars($contrato['estado']) ?></span>
            </div>
            <p class="text-xs opacity-70 uppercase tracking-wider mt-1">Expediente Contractual • SICAS</p>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <a href="index.php?controller=contrato&action=index" class="btn btn-ghost btn-sm flex-1 md:flex-none">
                <i class="fa-solid fa-arrow-left"></i> Volver al listado
            </a>
            <?php if(AuthHelper::esAdmin() || AuthHelper::esFinanciero() || AuthHelper::esSupervisor()): ?>
                <a href="index.php?controller=contrato&action=edit&id=<?= $contrato['id_contrato'] ?>" class="btn btn-warning btn-sm text-white flex-1 md:flex-none shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body space-y-4">
                    <div class="divider divider-start text-primary font-bold uppercase text-xs">I. Información Técnica e Institucional</div>
                    
                    <div class="form-control">
                        <label class="label-text font-bold opacity-60 text-xs uppercase">Objeto del Contrato</label>
                        <div class="bg-base-200 p-4 rounded-box mt-1 text-sm text-justify">
                            <?= nl2br(htmlspecialchars($contrato['objeto_contrato'])) ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="label-text font-bold opacity-60 text-xs uppercase">Secretaría / Dependencia</label>
                            <p class="font-medium text-sm text-base-content mt-0.5"><?= htmlspecialchars($contrato['secretaria'] ?: 'No asignada') ?></p>
                        </div>
                        <div>
                            <label class="label-text font-bold opacity-60 text-xs uppercase">Línea Estratégica</label>
                            <p class="font-medium text-sm text-base-content mt-0.5"><?= htmlspecialchars($contrato['linea_estrategica'] ?: 'No definida') ?></p>
                        </div>
                        <div>
                            <label class="label-text font-bold opacity-60 text-xs uppercase">Tipo de Contrato</label>
                            <p class="font-medium text-sm text-base-content mt-0.5"><?= htmlspecialchars($contrato['tipo_contrato'] ?: 'No especificado') ?></p>
                        </div>
                        <div>
                            <label class="label-text font-bold opacity-60 text-xs uppercase">Modalidad de Selección</label>
                            <p class="font-medium text-sm text-base-content mt-0.5"><?= htmlspecialchars($contrato['modalidad_seleccion'] ?: 'No especificada') ?></p>
                        </div>
                        <div>
                            <label class="label-text font-bold opacity-60 text-xs uppercase">Código BPIN</label>
                            <p class="font-mono text-sm font-bold text-base-content mt-0.5"><?= htmlspecialchars($contrato['bpin'] ?: 'N/A') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body">
                    <div class="divider divider-start text-primary font-bold uppercase text-xs">II. Actores Responsables</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div class="p-4 bg-base-200 rounded-box flex gap-3 items-start">
                            <div class="p-2 bg-white rounded-md shadow-xs text-primary"><i class="fa-solid fa-user-tie"></i></div>
                            <div>
                                <h4 class="text-xs font-bold opacity-60 uppercase">Contratista</h4>
                                <p class="font-bold text-sm text-base-content mt-0.5"><?= htmlspecialchars($contrato['contratista_nombre'] ?: 'No asociado') ?></p>
                                <p class="text-xs opacity-70 mt-0.5">ID/NIT: <?= htmlspecialchars($contrato['contratista_documento'] ?: 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="p-4 bg-base-200 rounded-box flex gap-3 items-start">
                            <div class="p-2 bg-white rounded-md shadow-xs text-success"><i class="fa-solid fa-user-shield"></i></div>
                            <div>
                                <h4 class="text-xs font-bold opacity-60 uppercase">Supervisor Designado</h4>
                                <p class="font-bold text-sm text-base-content mt-0.5"><?= htmlspecialchars($contrato['supervisor_nombre'] ?: 'Sin supervisor asignado') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body space-y-3">
                    <div class="divider divider-start text-primary font-bold uppercase text-xs">III. Cronología de Ejecución</div>
                    
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-xs opacity-70"><i class="fa-regular fa-calendar mr-1"></i> Fecha Firma:</span>
                        <span class="font-medium"><?= $contrato['fecha_firma'] ? date('d/m/Y', strtotime($contrato['fecha_firma'])) : 'N/A' ?></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-xs opacity-70"><i class="fa-solid fa-play text-success mr-1"></i> Fecha Inicio:</span>
                        <span class="font-medium"><?= $contrato['fecha_inicio'] ? date('d/m/Y', strtotime($contrato['fecha_inicio'])) : 'Pendiente' ?></span>
                    </div>
                    
                    <div class="divider my-0"></div>
                    
                    <div class="flex justify-between items-center border-b border-base-200 pb-2">
                        <span class="text-xs font-bold opacity-60 uppercase">Plazo Estimado</span>
                        <span class="badge badge-neutral font-medium text-xs"><?= htmlspecialchars($contrato['plazo_ejecucion'] ?: 'No definido') ?></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-xs opacity-70"><i class="fa-solid fa-flag-checkered text-warning mr-1"></i> Terminación Estimada:</span>
                        <span class="font-medium"><?= $contrato['fecha_terminacion'] ? date('d/m/Y', strtotime($contrato['fecha_terminacion'])) : 'N/A' ?></span>
                    </div>

                    <div class="divider my-0"></div>

                    <div class="flex justify-between items-center border-b border-base-200 pb-2">
                        <span class="text-xs font-bold opacity-60 uppercase text-success">Plazo Real</span>
                        <span class="badge badge-success text-white font-medium text-xs"><?= htmlspecialchars($contrato['plazo_ejecucion_real'] ?: 'No definido') ?></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-xs opacity-70"><i class="fa-solid fa-flag-checkered text-success mr-1"></i> Terminación Real:</span>
                        <span class="font-medium"><?= $contrato['fecha_terminacion_real'] ? date('d/m/Y', strtotime($contrato['fecha_terminacion_real'])) : 'Pendiente' ?></span>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-sm border border-base-300">
                            <div class="card-body space-y-4">
                                <div class="flex justify-between items-center">
                                    <div class="divider divider-start text-primary font-bold uppercase text-xs w-full m-0">IV. Ejecución Financiera</div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                    <div class="bg-base-200 p-3 rounded-box border border-base-300">
                                        <span class="text-xs font-bold opacity-60 uppercase block mb-1">Valor Total</span>
                                        <span class="text-lg font-black text-neutral">$<?= number_format($contrato['valor_total'], 2, ',', '.') ?></span>
                                    </div>
                                    <div class="bg-success/10 p-3 rounded-box border border-success/20">
                                        <span class="text-xs font-bold opacity-60 uppercase block mb-1 text-success">Total Pagado</span>
                                        <span class="text-lg font-black text-success">$<?= number_format($total_pagado, 2, ',', '.') ?></span>
                                    </div>
                                    <div class="bg-warning/10 p-3 rounded-box border border-warning/20">
                                        <span class="text-xs font-bold opacity-60 uppercase block mb-1 text-warning">Saldo Pendiente</span>
                                        <span class="text-lg font-black text-warning">$<?= number_format($saldo_pendiente, 2, ',', '.') ?></span>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs mb-1 font-bold opacity-70">
                                        <span>Avance Financiero</span>
                                        <span><?= number_format($porcentaje, 1) ?>%</span>
                                    </div>
                                    <progress class="progress progress-success w-full" value="<?= $porcentaje ?>" max="100"></progress>
                                </div>

                                <div class="space-y-2 pt-2 text-sm border-t border-base-200">
                                    <div class="flex justify-between"><span class="text-xs opacity-70">CDP:</span> <span class="font-semibold text-neutral"><?= htmlspecialchars($contrato['cdp'] ?: 'N/A') ?></span></div>
                                    <div class="flex justify-between"><span class="text-xs opacity-70">RP:</span> <span class="font-semibold text-neutral"><?= htmlspecialchars($contrato['rp'] ?: 'N/A') ?></span></div>
                                    <div class="flex justify-between"><span class="text-xs opacity-70">Rubro:</span> <span class="font-mono text-xs font-bold bg-base-200 px-1.5 py-0.5 rounded"><?= htmlspecialchars($contrato['rubro_presupuestal'] ?: 'N/A') ?></span></div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-base-100 shadow-sm border border-base-300">
                            <div class="card-body">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="divider divider-start text-primary font-bold uppercase text-xs m-0 flex-1">V. Historial de Pagos</div>
                                    <button class="btn btn-sm btn-primary text-white" onclick="modal_pago.showModal()">
                                        <i class="fa-solid fa-plus"></i> Registrar Pago
                                    </button>
                                </div>

                                <?php if(empty($historial_pagos)): ?>
                                    <div class="alert alert-info bg-blue-50 text-blue-800 text-xs mt-2 border-blue-200">
                                        <i class="fa-solid fa-circle-info"></i> No se han registrado pagos para este contrato.
                                    </div>
                                <?php else: ?>
                                    <div class="overflow-x-auto mt-2">
                                        <table class="table table-xs w-full">
                                            <thead>
                                                <tr class="bg-base-200 text-neutral">
                                                    <th>Fecha</th>
                                                    <th>Acta/Concepto</th>
                                                    <th class="text-right">Valor Pagado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($historial_pagos as $pago): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></td>
                                                    <td class="font-medium"><?= htmlspecialchars($pago['numero_acta']) ?></td>
                                                    <td class="text-right font-bold text-success">$<?= number_format($pago['valor_pagado'], 2, ',', '.') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <dialog id="modal_pago" class="modal">
                <div class="modal-box bg-white">
                    <h3 class="font-bold text-lg text-primary mb-4 border-b pb-2">Registrar Nuevo Pago</h3>
                    <form action="index.php?controller=pago&action=store" method="POST">
                        <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">
                        
                        <div class="form-control w-full mb-3">
                            <label class="label"><span class="label-text font-bold">Número de Acta o Concepto *</span></label>
                            <input type="text" name="numero_acta" required placeholder="Ej: Acta Parcial No. 1, Anticipo..." class="input input-bordered w-full" />
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Fecha de Pago *</span></label>
                                <input type="date" name="fecha_pago" required class="input input-bordered w-full" />
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Valor Pagado ($) *</span></label>
                                <input type="number" step="0.01" name="valor_pagado" required placeholder="Ej: 5000000" class="input input-bordered w-full" />
                            </div>
                        </div>

                        <div class="form-control w-full mb-4">
                            <label class="label"><span class="label-text font-bold">Observaciones (Opcional)</span></label>
                            <textarea name="observaciones" class="textarea textarea-bordered h-20" placeholder="Detalles adicionales del pago..."></textarea>
                        </div>

                        <div class="modal-action">
                            <button type="button" class="btn btn-ghost" onclick="modal_pago.close()">Cancelar</button>
                            <button type="submit" class="btn btn-primary text-white">Guardar Pago</button>
                        </div>
                    </form>
                </div>
            </dialog>

            <?php if(!empty($contrato['link_secop'])): ?>
                <a href="<?= htmlspecialchars($contrato['link_secop']) ?>" target="_blank" class="btn btn-outline btn-primary w-full gap-2 shadow-sm bg-white">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver expediente en SECOP
                </a>
            <?php endif; ?>

        </div>
    </div>
</div>