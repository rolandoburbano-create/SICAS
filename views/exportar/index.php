<div class="max-w-5xl mx-auto space-y-6 pb-12">
    <div class="flex justify-between items-center bg-white p-6 rounded-box shadow-sm border border-base-300">
        <div>
            <h1 class="text-2xl font-bold text-primary">Exportación de Datos</h1>
            <p class="text-xs opacity-70 uppercase tracking-wider mt-1">Seleccione los datos a exportar y el formato deseado</p>
        </div>
    </div>

    <form action="index.php?controller=exportar&action=exportar" method="POST" id="formExportar">
        <div class="card bg-base-100 shadow-sm border border-base-300">
            <div class="card-body space-y-6">

                <!-- Entidad -->
                <div>
                    <label class="label-text font-bold text-base">1. ¿Qué desea exportar?</label>
                    <div class="flex gap-4 mt-2">
                        <label class="btn btn-outline btn-sm px-6 cursor-pointer has-[:checked]:btn-primary has-[:checked]:text-white" id="lbl_contratos">
                            <input type="radio" name="entidad" value="contratos" checked class="hidden" onchange="toggleCampos()">
                            <i class="fa-solid fa-file-contract mr-2"></i> Contratos
                        </label>
                        <label class="btn btn-outline btn-sm px-6 cursor-pointer has-[:checked]:btn-primary has-[:checked]:text-white" id="lbl_contratistas">
                            <input type="radio" name="entidad" value="contratistas" class="hidden" onchange="toggleCampos()">
                            <i class="fa-solid fa-user-group mr-2"></i> Contratistas
                        </label>
                    </div>
                </div>

                <!-- Campos -->
                <div>
                    <div class="flex items-center justify-between">
                        <label class="label-text font-bold text-base">2. Seleccione los campos a incluir</label>
                        <div class="flex gap-2">
                            <button type="button" class="btn btn-ghost btn-xs text-primary" onclick="seleccionarTodos(true)">Seleccionar todos</button>
                            <button type="button" class="btn btn-ghost btn-xs" onclick="seleccionarTodos(false)">Deseleccionar</button>
                        </div>
                    </div>
                    <div id="campos_contratos" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mt-2">
                        <?php
                        $labels = (new ExportarController())->fieldLabels;
                        $defaults = (new ExportarController())->defaultFields;
                        foreach ($labels['contratos'] as $key => $label):
                            $checked = in_array($key, $defaults['contratos']) ? 'checked' : '';
                        ?>
                        <label class="flex items-center gap-2 p-2 rounded hover:bg-base-200 cursor-pointer text-sm">
                            <input type="checkbox" name="campos[]" value="<?= $key ?>" <?= $checked ?> class="checkbox checkbox-primary checkbox-xs campo-chk" data-entidad="contratos">
                            <?= htmlspecialchars($label) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <div id="campos_contratistas" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mt-2 hidden">
                        <?php foreach ($labels['contratistas'] as $key => $label):
                            $checked = in_array($key, $defaults['contratistas']) ? 'checked' : '';
                        ?>
                        <label class="flex items-center gap-2 p-2 rounded hover:bg-base-200 cursor-pointer text-sm">
                            <input type="checkbox" name="campos[]" value="<?= $key ?>" <?= $checked ?> class="checkbox checkbox-primary checkbox-xs campo-chk" data-entidad="contratistas">
                            <?= htmlspecialchars($label) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Formato -->
                <div>
                    <label class="label-text font-bold text-base">3. Elija el formato de exportación</label>
                    <div class="flex flex-wrap gap-4 mt-2">
                        <label class="btn btn-outline btn-sm px-6 cursor-pointer has-[:checked]:btn-primary has-[:checked]:text-white">
                            <input type="radio" name="formato" value="csv" checked class="hidden">
                            <i class="fa-solid fa-file-csv mr-2"></i> CSV
                        </label>
                        <label class="btn btn-outline btn-sm px-6 cursor-pointer has-[:checked]:btn-primary has-[:checked]:text-white">
                            <input type="radio" name="formato" value="xls" class="hidden">
                            <i class="fa-solid fa-file-excel mr-2"></i> Excel (XLS)
                        </label>
                        <label class="btn btn-outline btn-sm px-6 cursor-pointer has-[:checked]:btn-primary has-[:checked]:text-white">
                            <input type="radio" name="formato" value="pdf" class="hidden">
                            <i class="fa-solid fa-file-pdf mr-2"></i> PDF (vista imprimible)
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-primary btn-lg shadow-xl px-12">
                <i class="fa-solid fa-download mr-2"></i> Exportar
            </button>
        </div>
    </form>
</div>

<script>
function toggleCampos() {
    var esContratos = document.querySelector('input[name="entidad"]:checked').value === 'contratos';
    document.getElementById('campos_contratos').classList.toggle('hidden', !esContratos);
    document.getElementById('campos_contratistas').classList.toggle('hidden', esContratos);
}

function seleccionarTodos(seleccionar) {
    var entidad = document.querySelector('input[name="entidad"]:checked').value;
    document.querySelectorAll('.campo-chk[data-entidad="' + entidad + '"]').forEach(function(cb) {
        cb.checked = seleccionar;
    });
}
</script>

<style>
.has-\[\:checked\]\:btn-primary:has(input:checked) { --tw-bg-opacity: 1; background-color: var(--p); color: white; }
.has-\[\:checked\]\:text-white:has(input:checked) { color: white; }
</style>
