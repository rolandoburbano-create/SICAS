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
                <div class="card-body space-y-3">
                    <div class="divider divider-start text-primary font-bold uppercase text-xs">IV. Información Financiera</div>
                    
                    <div class="bg-primary/5 p-4 rounded-box border border-primary/10 text-center">
                        <span class="text-xs font-bold opacity-60 uppercase block mb-1">Valor Total</span>
                        <span class="text-2xl font-black text-primary">$<?= number_format($contrato['valor_total'], 2, ',', '.') ?></span>
                    </div>

                    <div class="space-y-2 pt-2 text-sm">
                        <div class="flex justify-between"><span class="text-xs opacity-70">CDP:</span> <span class="font-semibold text-neutral"><?= htmlspecialchars($contrato['cdp'] ?: 'N/A') ?></span></div>
                        <div class="flex justify-between"><span class="text-xs opacity-70">RP:</span> <span class="font-semibold text-neutral"><?= htmlspecialchars($contrato['rp'] ?: 'N/A') ?></span></div>
                        <div class="flex justify-between"><span class="text-xs opacity-70">Rubro:</span> <span class="font-mono text-xs font-bold bg-base-200 px-1.5 py-0.5 rounded"><?= htmlspecialchars($contrato['rubro_presupuestal'] ?: 'N/A') ?></span></div>
                        <div class="flex justify-between items-center pt-2"><span class="text-xs opacity-70 block w-1/3">Fuente:</span> <span class="font-semibold text-right text-xs leading-tight"><?= htmlspecialchars($contrato['fuente_recursos'] ?: 'N/A') ?></span></div>
                    </div>
                </div>
            </div>

            <?php if(!empty($contrato['link_secop'])): ?>
                <a href="<?= htmlspecialchars($contrato['link_secop']) ?>" target="_blank" class="btn btn-outline btn-primary w-full gap-2 shadow-sm bg-white">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver expediente en SECOP
                </a>
            <?php endif; ?>

        </div>
    </div>
</div>