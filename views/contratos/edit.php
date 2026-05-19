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
                        <label class="label"><span class="label-text font-bold text-lg text-primary">Valor Total ($)</span></label>
                        <input type="number" step="0.01" name="valor_total" value="<?= $contrato['valor_total'] ?>" <?= $bloquearFinanzas ?> />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Forma de Pago</span></label>
                        <input type="text" name="forma_pago" value="<?= htmlspecialchars($contrato['forma_pago']) ?>" <?= $bloquearFinanzas ?> />
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
                            <input type="text" name="tiempo_prorroga" value="<?= htmlspecialchars($contrato['tiempo_prorroga']) ?>" placeholder="Tiempo" <?= $bloquearInputsNovedades ?> />
                        </div>
                    </div>

                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-warning">
                        <div class="form-control flex-row items-center justify-between mb-2">
                            <span class="label-text font-bold">¿Tiene Suspensión?</span>
                            <input type="checkbox" name="tiene_suspension" <?= $contrato['tiene_suspension'] ? 'checked' : '' ?> <?= $bloquearNovedades ?> />
                        </div>
                        <div class="flex gap-2">
                            <input type="number" name="numero_suspension" value="<?= $contrato['numero_suspension'] ?>" placeholder="No." <?= $bloquearInputsNovedades ?> />
                            <input type="text" name="duracion_suspension" value="<?= htmlspecialchars($contrato['duracion_suspension']) ?>" placeholder="Duración" <?= $bloquearInputsNovedades ?> />
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