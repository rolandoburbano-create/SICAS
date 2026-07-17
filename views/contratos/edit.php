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
                <div class="divider divider-start text-primary font-bold uppercase text-xs">I. Identificación y Localización</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Número de Contrato</span></label>
                        <input type="text" name="numero_contrato" value="<?= htmlspecialchars($contrato['numero_contrato']) ?>" <?= $bloquearTecnico ?> />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Código BPIN</span></label>
                        <input type="text" name="bpin" value="<?= htmlspecialchars($contrato['bpin'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Estado del Proceso</span></label>
                        <select name="estado" class="select select-bordered w-full">
                            <option value="Celebrado" <?= $contrato['estado'] == 'Celebrado' ? 'selected' : '' ?>>Celebrado</option>
                            <option value="Activo" <?= $contrato['estado'] == 'Activo' ? 'selected' : '' ?>>Activo / En ejecución</option>
                        </select>
                    </div>
                    <div class="form-control md:col-span-3">
                        <label class="label"><span class="label-text font-bold">Línea Estratégica (Plan de Desarrollo)</span></label>
                        <select name="linea_estrategica" class="select select-bordered">
                            <option value="1. Silvia un referente de turismo" <?= $contrato['linea_estrategica'] == '1. Silvia un referente de turismo' ? 'selected' : '' ?>>1. Silvia un referente de turismo</option>
                            <option value="2. Minga para un desarrollo armónico posible" <?= $contrato['linea_estrategica'] == '2. Minga para un desarrollo armónico posible' ? 'selected' : '' ?>>2. Minga para un desarrollo armónico posible</option>
                            <option value="3. Bienestar posible para la vida" <?= $contrato['linea_estrategica'] == '3. Bienestar posible para la vida' ? 'selected' : '' ?>>3. Bienestar posible para la vida</option>
                            <option value="4. Equidad y armonía territorial posible" <?= $contrato['linea_estrategica'] == '4. Equidad y armonía territorial posible' ? 'selected' : '' ?>>4. Equidad y armonía territorial posible</option>
                        </select>
                    </div>
                    <div class="form-control md:col-span-3">
                        <label class="label"><span class="label-text font-bold">Objeto Contractual</span></label>
                        <textarea name="objeto_contrato" class="textarea textarea-bordered h-20 w-full" <?= $bloquearTecnico ?>><?= htmlspecialchars($contrato['objeto_contrato']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-xs">II. Información General</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Contratista</span></label>
                        <select name="id_contratista" class="select select-bordered w-full">
                            <?php foreach($contratistas as $con): ?>
                                <option value="<?= $con['id_contratista'] ?>" <?= $contrato['id_contratista'] == $con['id_contratista'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($con['documento'] . ' - ' . $con['nombre_razon_social']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Supervisor</span></label>
                        <select name="id_supervisor" class="select select-bordered w-full">
                            <?php foreach($supervisores as $sup): ?>
                                <option value="<?= $sup['id_usuario'] ?>" <?= $contrato['id_supervisor'] == $sup['id_usuario'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sup['nombres'] . ' ' . $sup['apellidos']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Secretaría / Oficina</span></label>
                        <select name="secretaria" class="select select-bordered w-full">
                            <option value="">-- Seleccione --</option>
                            <option value="Despacho del Alcalde" <?= ($contrato['secretaria'] ?? '') == 'Despacho del Alcalde' ? 'selected' : '' ?>>Despacho del Alcalde</option>
                            <option value="Secretaría de Gobierno y Participación Ciudadana" <?= ($contrato['secretaria'] ?? '') == 'Secretaría de Gobierno y Participación Ciudadana' ? 'selected' : '' ?>>Secretaría de Gobierno y Participación Ciudadana</option>
                            <option value="Secretaría Administrativa y Financiera" <?= ($contrato['secretaria'] ?? '') == 'Secretaría Administrativa y Financiera' ? 'selected' : '' ?>>Secretaría Administrativa y Financiera</option>
                            <option value="Oficina Asesora de Planeación" <?= ($contrato['secretaria'] ?? '') == 'Oficina Asesora de Planeación' ? 'selected' : '' ?>>Oficina Asesora de Planeación</option>
                            <option value="Secretaría de Infraestructura" <?= ($contrato['secretaria'] ?? '') == 'Secretaría de Infraestructura' ? 'selected' : '' ?>>Secretaría de Infraestructura</option>
                            <option value="Secretaría de Bienestar y Desarrollo Social" <?= ($contrato['secretaria'] ?? '') == 'Secretaría de Bienestar y Desarrollo Social' ? 'selected' : '' ?>>Secretaría de Bienestar y Desarrollo Social</option>
                            <option value="Secretaría de Desarrollo Productivo y Ambiental" <?= ($contrato['secretaria'] ?? '') == 'Secretaría de Desarrollo Productivo y Ambiental' ? 'selected' : '' ?>>Secretaría de Desarrollo Productivo y Ambiental</option>
                            <option value="Oficina Asesora Jurídica" <?= ($contrato['secretaria'] ?? '') == 'Oficina Asesora Jurídica' ? 'selected' : '' ?>>Oficina Asesora Jurídica</option>
                            <option value="Oficina Asesora de Control Interno" <?= ($contrato['secretaria'] ?? '') == 'Oficina Asesora de Control Interno' ? 'selected' : '' ?>>Oficina Asesora de Control Interno</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fuente de Recursos</span></label>
                        <select name="fuente_recursos" class="select select-bordered w-full">
                            <option value="">Seleccione...</option>
                            <option value="Recursos Propios" <?= ($contrato['fuente_recursos'] ?? '') == 'Recursos Propios' ? 'selected' : '' ?>>Recursos Propios (Libre Destinación)</option>
                            <option value="SGP - Salud" <?= ($contrato['fuente_recursos'] ?? '') == 'SGP - Salud' ? 'selected' : '' ?>>SGP - Salud</option>
                            <option value="SGP - Educación" <?= ($contrato['fuente_recursos'] ?? '') == 'SGP - Educación' ? 'selected' : '' ?>>SGP - Educación</option>
                            <option value="SGP - Propósito General" <?= ($contrato['fuente_recursos'] ?? '') == 'SGP - Propósito General' ? 'selected' : '' ?>>SGP - Propósito General</option>
                            <option value="Sistema General de Regalías" <?= ($contrato['fuente_recursos'] ?? '') == 'Sistema General de Regalías' ? 'selected' : '' ?>>Sistema General de Regalías (SGR)</option>
                            <option value="Cofinanciación" <?= ($contrato['fuente_recursos'] ?? '') == 'Cofinanciación' ? 'selected' : '' ?>>Cofinanciación (Nación/Departamento)</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Modalidad</span></label>
                        <select name="modalidad_seleccion" class="select select-bordered">
                            <option value="Concurso de méritos" <?= $contrato['modalidad_seleccion'] == 'Concurso de méritos' ? 'selected' : '' ?>>Concurso de méritos</option>
                            <option value="Contratación directa" <?= $contrato['modalidad_seleccion'] == 'Contratación directa' ? 'selected' : '' ?>>Contratación directa</option>
                            <option value="Licitación" <?= $contrato['modalidad_seleccion'] == 'Licitación' ? 'selected' : '' ?>>Licitación</option>
                            <option value="Mínima cuantía" <?= $contrato['modalidad_seleccion'] == 'Mínima cuantía' ? 'selected' : '' ?>>Mínima cuantía</option>
                            <option value="Selección abreviada" <?= $contrato['modalidad_seleccion'] == 'Selección abreviada' ? 'selected' : '' ?>>Selección abreviada</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Tipo de Contrato</span></label>
                        <select name="tipo_contrato" class="select select-bordered">
                            <option value="Compraventa" <?= $contrato['tipo_contrato'] == 'Compraventa' ? 'selected' : '' ?>>Compraventa</option>
                            <option value="Consultoría" <?= $contrato['tipo_contrato'] == 'Consultoría' ? 'selected' : '' ?>>Consultoría</option>
                            <option value="Contrato interadministrativo" <?= $contrato['tipo_contrato'] == 'Contrato interadministrativo' ? 'selected' : '' ?>>Contrato interadministrativo</option>
                            <option value="Convenio interadministrativo" <?= $contrato['tipo_contrato'] == 'Convenio interadministrativo' ? 'selected' : '' ?>>Convenio interadministrativo</option>
                            <option value="Obra pública" <?= $contrato['tipo_contrato'] == 'Obra pública' ? 'selected' : '' ?>>Obra pública</option>
                            <option value="Prestación de Servicios" <?= $contrato['tipo_contrato'] == 'Prestación de Servicios' ? 'selected' : '' ?>>Prestación de Servicios</option>
                            <option value="Suministro" <?= $contrato['tipo_contrato'] == 'Suministro' ? 'selected' : '' ?>>Suministro</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="flex justify-between items-center">
                    <div class="divider divider-start text-primary font-bold uppercase text-xs">III. Presupuesto</div>
                    <?php if(AuthHelper::esSupervisor()): ?>
                        <span class="badge badge-error badge-sm gap-1"><i class="fa-solid fa-lock text-[10px]"></i> Solo lectura</span>
                    <?php endif; ?>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-primary">Valor Total ($)</span></label>
                        <input type="text" inputmode="numeric" name="valor_total" value="<?= isset($contrato['valor_total']) ? (int)$contrato['valor_total'] : '' ?>" class="currency-input input input-bordered border-primary w-full" <?= AuthHelper::esSupervisor() ? 'readonly' : '' ?> />
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
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-6 border-t pt-4 border-base-300">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Número CDP</span></label>
                        <input type="text" name="cdp" value="<?= htmlspecialchars($contrato['cdp'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha CDP</span></label>
                        <input type="date" name="fecha_cdp" value="<?= $contrato['fecha_cdp'] ?? '' ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Valor CDP ($)</span></label>
                        <input type="text" inputmode="numeric" name="valor_cdp" value="<?= isset($contrato['valor_cdp']) ? (int)$contrato['valor_cdp'] : '' ?>" placeholder="0" class="currency-input input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Número RP</span></label>
                        <input type="text" name="rp" value="<?= htmlspecialchars($contrato['rp'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Valor RP ($)</span></label>
                        <input type="text" inputmode="numeric" name="valor_rp" value="<?= isset($contrato['valor_rp']) ? (int)$contrato['valor_rp'] : '' ?>" placeholder="0" class="currency-input input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Rubro Presupuestal</span></label>
                        <input type="text" name="rubro_presupuestal" value="<?= htmlspecialchars($contrato['rubro_presupuestal'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-xs">IV. Cronología</div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Firma</span></label>
                        <input type="date" name="fecha_firma" value="<?= $contrato['fecha_firma'] ?? '' ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Inicio</span></label>
                        <input type="date" name="fecha_inicio" value="<?= $contrato['fecha_inicio'] ?? '' ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Terminación Pactada</span></label>
                        <input type="date" name="fecha_terminacion" value="<?= $contrato['fecha_terminacion'] ?? '' ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Plazo</span></label>
                        <input type="text" name="plazo_ejecucion" value="<?= htmlspecialchars($contrato['plazo_ejecucion'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Terminación Real</span></label>
                        <input type="date" name="fecha_terminacion_real" value="<?= $contrato['fecha_terminacion_real'] ?? '' ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Liquidación</span></label>
                        <input type="date" name="fecha_liquidacion" value="<?= $contrato['fecha_liquidacion'] ?? '' ?>" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Plazo Final Ejecutado</span></label>
                        <input type="text" name="plazo_ejecucion_real" value="<?= htmlspecialchars($contrato['plazo_ejecucion_real'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-xs">V. Novedades Contractuales</div>
                <?php if(AuthHelper::esFinanciero()): ?>
                    <span class="badge badge-error badge-sm gap-1 mb-2"><i class="fa-solid fa-lock text-[10px]"></i> Solo lectura</span>
                <?php endif; ?>
                
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
                            <?php
                                $nuevoContratistaText = '';
                                if ($contrato['id_nuevo_contratista']) {
                                    foreach ($contratistas as $con) {
                                        if ($con['id_contratista'] == $contrato['id_nuevo_contratista']) {
                                            $nuevoContratistaText = $con['documento'] . ' - ' . $con['nombre_razon_social'];
                                            break;
                                        }
                                    }
                                }
                            ?>
                            <div class="relative" id="nuevo-contratista-autocomplete">
                                <input type="text" id="nuevo-contratista-search" class="input input-xs input-bordered w-full" placeholder="Buscar contratista..." autocomplete="off" value="<?= htmlspecialchars($nuevoContratistaText) ?>" <?= AuthHelper::esFinanciero() ? 'disabled' : '' ?>>
                                <input type="hidden" name="id_nuevo_contratista" id="nuevo-contratista-id" value="<?= $contrato['id_nuevo_contratista'] ?>">
                                <div id="nuevo-contratista-results" class="absolute z-50 w-full mt-1 bg-white shadow-lg border border-base-300 rounded-box hidden max-h-40 overflow-y-auto text-xs"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-xs">VI. Enlace SECOP</div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-bold"><i class="fa-solid fa-link"></i> Enlace SECOP / SIA OBSERVA</span></label>
                    <input type="url" name="link_secop" value="<?= htmlspecialchars($contrato['link_secop'] ?? '') ?>" placeholder="https://www.secop.gov.co/..." class="input input-bordered w-full" />
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

<script>
function initAutocomplete(config) {
    const searchInput = document.getElementById(config.searchId);
    const hiddenInput = document.getElementById(config.hiddenId);
    const resultsDiv = document.getElementById(config.resultsId);
    let timeout = null;

    if (!searchInput) return;

    function fetchResults(termino) {
        const url = config.url + '&q=' + encodeURIComponent(termino);
        fetch(url)
            .then(res => res.json())
            .then(data => {
                resultsDiv.innerHTML = '';
                if (data.length === 0) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                resultsDiv.classList.remove('hidden');
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-2 cursor-pointer hover:bg-primary hover:text-primary-content border-b border-base-200';
                    div.textContent = item.documento + ' - ' + item.nombre;
                    div.addEventListener('click', function() {
                        searchInput.value = item.documento + ' - ' + item.nombre;
                        hiddenInput.value = item.id;
                        resultsDiv.classList.add('hidden');
                    });
                    resultsDiv.appendChild(div);
                });
            });
    }

    searchInput.addEventListener('input', function() {
        const termino = this.value.trim();
        if (termino.length < 1) {
            resultsDiv.classList.add('hidden');
            return;
        }
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            fetchResults(termino);
        }, 300);
    });

    searchInput.addEventListener('blur', function() {
        setTimeout(function() {
            resultsDiv.classList.add('hidden');
        }, 200);
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 1 && resultsDiv.children.length > 0) {
            resultsDiv.classList.remove('hidden');
        }
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            resultsDiv.classList.add('hidden');
        }
        if (e.key === 'Enter') {
            e.preventDefault();
            if (!resultsDiv.classList.contains('hidden') && resultsDiv.children.length > 0) {
                resultsDiv.children[0].click();
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initAutocomplete({
        searchId: 'nuevo-contratista-search',
        hiddenId: 'nuevo-contratista-id',
        resultsId: 'nuevo-contratista-results',
        url: 'index.php?controller=contratista&action=buscarJson'
    });
});
</script>

<script>
function formatearMoneda(input) {
    var valor = input.value.replace(/[^0-9]/g, '');
    if (valor) {
        input.value = new Intl.NumberFormat('es-CO').format(valor);
    }
}

document.querySelectorAll('.currency-input').forEach(function(input) {
    formatearMoneda(input);
    input.addEventListener('input', function() {
        formatearMoneda(this);
    });
});
document.querySelector('form').addEventListener('submit', function() {
    document.querySelectorAll('.currency-input').forEach(function(input) {
        input.value = input.value.replace(/\./g, '');
    });
});
</script>