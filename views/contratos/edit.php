<?php
// LÓGICA DEL CHOQUE DE ROLES
// 1. ¿Quién puede editar la parte Técnica/Actores? (Solo Admin)
$bloquearTecnico = (!AuthHelper::esAdmin()) ? 'readonly class="input input-bordered bg-base-200 opacity-70 cursor-not-allowed"' : 'class="input input-bordered border-primary"';
$bloquearSelectTecnico = (!AuthHelper::esAdmin()) ? 'disabled class="select select-bordered bg-base-200 opacity-70 cursor-not-allowed"' : 'class="select select-bordered"';

// 2. ¿Quién puede editar el Presupuesto? (Admin y Financiero)
$bloquearFinanzas = (AuthHelper::esSupervisor()) ? 'readonly class="input input-bordered bg-base-200 opacity-70 cursor-not-allowed"' : 'class="input input-bordered border-primary"';

// 3. ¿Quién puede editar las Novedades y Tiempos? (Admin y Supervisor)
$bloquearNovedades = (AuthHelper::esFinanciero()) ? 'disabled class="toggle toggle-disabled"' : 'class="toggle toggle-primary"';
$bloquearInputsNovedades = (AuthHelper::esFinanciero()) ? 'readonly class="input input-xs input-bordered bg-base-200 cursor-not-allowed"' : 'class="input input-xs input-bordered w-full"';
$bloquearSelectNovedades = (AuthHelper::esFinanciero()) ? 'disabled class="select select-xs select-bordered bg-base-200 cursor-not-allowed"' : 'class="select select-xs select-bordered w-full"';
?>

<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-white p-4 rounded-box shadow-sm border border-base-300">
        <div>
            <h1 class="text-2xl font-bold text-primary">Edición de Contrato: <?= htmlspecialchars($contrato['numero_contrato']) ?></h1>
            <p class="text-xs opacity-60 uppercase tracking-widest">Modo de edición según perfil de usuario</p>
        </div>
        <a href="index.php?controller=contrato&action=index" class="btn btn-ghost btn-sm"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>

    <form action="index.php?controller=contrato&action=update" method="POST" class="space-y-4 pb-10">
        <input type="hidden" name="id_contrato" value="<?= $contrato['id_contrato'] ?>">

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-xs">I. Identificación Técnica</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Número de Contrato</span></label>
                        <input type="text" name="numero_contrato" value="<?= htmlspecialchars($contrato['numero_contrato']) ?>" <?= $bloquearTecnico ?> />
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-bold">Objeto Contractual</span></label>
                        <input type="text" name="objeto_contrato" value="<?= htmlspecialchars($contrato['objeto_contrato']) ?>" <?= $bloquearTecnico ?> />
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="flex justify-between items-center">
                    <div class="divider divider-start text-primary font-bold uppercase text-xs">II. Presupuesto</div>
                    <?php if(AuthHelper::esSupervisor()): ?>
                        <span class="badge badge-error badge-sm gap-1"><i class="fa-solid fa-lock text-[10px]"></i> Solo lectura</span>
                    <?php endif; ?>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-primary">Valor Total ($)</span></label>
                        <input type="number" step="0.01" name="valor_total" value="<?= $contrato['valor_total'] ?>" class="input input-bordered border-primary w-full" <?= AuthHelper::esSupervisor() ? 'readonly' : '' ?> />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Forma de Pago</span></label>
                        <?php if(AuthHelper::esSupervisor()): ?>
                            <select name="forma_pago" disabled class="select select-bordered bg-base-200 opacity-70 cursor-not-allowed w-full">
                        <?php else: ?>
                            <select name="forma_pago" class="select select-bordered w-full">
                        <?php endif; ?>
                            <option value="Actas mensuales" <?= $contrato['forma_pago'] == 'Actas mensuales' ? 'selected' : '' ?>>Actas mensuales</option>
                            <option value="Actas parciales" <?= $contrato['forma_pago'] == 'Actas parciales' ? 'selected' : '' ?>>Actas parciales</option>
                            <option value="Único pago" <?= $contrato['forma_pago'] == 'Único pago' ? 'selected' : '' ?>>Único pago</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="flex justify-between items-center">
                    <div class="divider divider-start text-primary font-bold uppercase text-xs">III. Novedades Contractuales</div>
                    <?php if(AuthHelper::esFinanciero()): ?>
                        <span class="badge badge-error badge-sm gap-1"><i class="fa-solid fa-lock text-[10px]"></i> Solo lectura</span>
                    <?php endif; ?>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-primary">
                        <div class="form-control flex-row items-center justify-between mb-2">
                            <span class="label-text font-bold">¿Tiene Prórroga?</span>
                            <input type="checkbox" name="tiene_prorroga" <?= $contrato['tiene_prorroga'] ? 'checked' : '' ?> <?= $bloquearNovedades ?> />
                        </div>
                        <div class="flex gap-2">
                            <input type="number" name="numero_prorroga" value="<?= $contrato['numero_prorroga'] ?>" placeholder="No." <?= $bloquearInputsNovedades ?> />
                            <input type="text" name="tiempo_prorroga" value="<?= htmlspecialchars($contrato['tiempo_prorroga'] ?? '') ?>" placeholder="Tiempo" <?= $bloquearInputsNovedades ?> />
                        </div>
                    </div>

                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-warning">
                        <div class="form-control flex-row items-center justify-between mb-2">
                            <span class="label-text font-bold">¿Tiene Suspensión?</span>
                            <input type="checkbox" name="tiene_suspension" <?= $contrato['tiene_suspension'] ? 'checked' : '' ?> <?= $bloquearNovedades ?> />
                        </div>
                        <div class="flex gap-2">
                            <input type="number" name="numero_suspension" value="<?= $contrato['numero_suspension'] ?>" placeholder="No." <?= $bloquearInputsNovedades ?> />
                            <input type="text" name="duracion_suspension" value="<?= htmlspecialchars($contrato['duracion_suspension'] ?? '') ?>" placeholder="Duración" <?= $bloquearInputsNovedades ?> />
                        </div>
                    </div>

                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-info">
                        <div class="form-control flex-row items-center justify-between mb-2">
                            <span class="label-text font-bold">¿Tiene Reinicio?</span>
                            <input type="checkbox" name="tiene_reinicio" <?= $contrato['tiene_reinicio'] ? 'checked' : '' ?> <?= $bloquearNovedades ?> />
                        </div>
                        <div class="flex gap-2">
                            <input type="number" name="numero_reinicio" value="<?= $contrato['numero_reinicio'] ?>" placeholder="No." <?= $bloquearInputsNovedades ?> />
                            <input type="date" name="fecha_reinicio" value="<?= $contrato['fecha_reinicio'] ?>" <?= $bloquearInputsNovedades ?> />
                        </div>
                    </div>

                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-error">
                        <div class="form-control flex-row items-center justify-between mb-2">
                            <span class="label-text font-bold">¿Tiene Cesión?</span>
                            <input type="checkbox" name="tiene_cesion" <?= $contrato['tiene_cesion'] ? 'checked' : '' ?> <?= $bloquearNovedades ?> />
                        </div>
                        <div class="flex gap-2">
                            <input type="date" name="fecha_cesion" value="<?= $contrato['fecha_cesion'] ?? '' ?>" <?= $bloquearInputsNovedades ?> />
                            <select name="id_nuevo_contratista" <?= $bloquearSelectNovedades ?>>
                                <option value="">Nuevo Contratista...</option>
                                <?php foreach($contratistas as $con): ?>
                                    <option value="<?= $con['id_contratista'] ?>" <?= $contrato['id_nuevo_contratista'] == $con['id_contratista'] ? 'selected' : '' ?>><?= $con['nombre_razon_social'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="btn btn-primary btn-lg shadow-xl px-12">
                <i class="fa-solid fa-save mr-2"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>