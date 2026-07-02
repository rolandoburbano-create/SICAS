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
                    <div class="flex flex-wrap gap-3 mt-2" id="entidad-group">
                        <?php $first = true; foreach ($entityInfo as $key => $info): ?>
                        <button type="button" class="btn btn-outline btn-sm px-5 entidad-btn <?= $first ? 'btn-primary text-white' : '' ?>" data-value="<?= $key ?>">
                            <i class="fa-solid <?= $info['icon'] ?> mr-2"></i> <?= $info['label'] ?>
                        </button>
                        <input type="radio" name="entidad" value="<?= $key ?>" <?= $first ? 'checked' : '' ?> class="hidden entidad-radio">
                        <?php $first = false; endforeach; ?>
                    </div>
                </div>

                <!-- Rango de fechas -->
                <div>
                    <label class="label-text font-bold text-base">2. Filtrar por rango de fechas <span class="text-xs opacity-60 font-normal">(opcional)</span></label>
                    <div class="flex flex-wrap gap-4 mt-2 items-end">
                        <div class="form-control">
                            <label class="label-text text-xs opacity-70 mb-1">Desde</label>
                            <input type="date" name="fecha_desde" class="input input-bordered input-sm w-44">
                        </div>
                        <div class="form-control">
                            <label class="label-text text-xs opacity-70 mb-1">Hasta</label>
                            <input type="date" name="fecha_hasta" class="input input-bordered input-sm w-44">
                        </div>
                        <span class="text-xs opacity-50 italic">Aplica según la entidad seleccionada</span>
                    </div>
                </div>

                <!-- Campos -->
                <div>
                    <div class="flex items-center justify-between">
                        <label class="label-text font-bold text-base">3. Seleccione los campos a incluir</label>
                        <div class="flex gap-2">
                            <button type="button" class="btn btn-ghost btn-xs text-primary font-bold" onclick="seleccionarTodos(true)">Seleccionar todos</button>
                            <button type="button" class="btn btn-ghost btn-xs font-bold" onclick="seleccionarTodos(false)">Deseleccionar</button>
                        </div>
                    </div>
                    <?php foreach ($fieldLabels as $entity => $fields): ?>
                    <div id="campos_<?= $entity ?>" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mt-2 <?= $entity !== 'contratos' ? 'hidden' : '' ?>">
                        <?php foreach ($fields as $key => $label):
                            $checked = in_array($key, $defaultFields[$entity]) ? 'checked' : '';
                        ?>
                        <label class="flex items-center gap-2 p-2 rounded hover:bg-base-200 cursor-pointer text-sm">
                            <input type="checkbox" name="campos[]" value="<?= $key ?>" <?= $checked ?> class="checkbox checkbox-primary checkbox-xs campo-chk" data-entidad="<?= $entity ?>">
                            <?= htmlspecialchars($label) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Formato -->
                <div>
                    <label class="label-text font-bold text-base">4. Elija el formato de exportación</label>
                    <div class="flex flex-wrap gap-3 mt-2" id="formato-group">
                        <button type="button" class="btn btn-outline btn-sm px-6 formato-btn btn-primary text-white" data-value="csv">
                            <i class="fa-solid fa-file-csv mr-2"></i> CSV
                        </button>
                        <input type="radio" name="formato" value="csv" checked class="hidden formato-radio">
                        <button type="button" class="btn btn-outline btn-sm px-6 formato-btn" data-value="xls">
                            <i class="fa-solid fa-file-excel mr-2"></i> Excel (XLS)
                        </button>
                        <input type="radio" name="formato" value="xls" class="hidden formato-radio">
                        <button type="button" class="btn btn-outline btn-sm px-6 formato-btn" data-value="pdf">
                            <i class="fa-solid fa-file-pdf mr-2"></i> PDF (vista imprimible)
                        </button>
                        <input type="radio" name="formato" value="pdf" class="hidden formato-radio">
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
document.addEventListener('DOMContentLoaded', function() {
    // Entity buttons
    document.querySelectorAll('.entidad-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var val = this.getAttribute('data-value');
            document.querySelectorAll('.entidad-btn').forEach(function(b) {
                b.classList.remove('btn-primary', 'text-white');
            });
            this.classList.add('btn-primary', 'text-white');
            document.querySelector('.entidad-radio[value="' + val + '"]').checked = true;
            toggleCampos(val);
        });
    });

    // Format buttons
    document.querySelectorAll('.formato-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var val = this.getAttribute('data-value');
            document.querySelectorAll('.formato-btn').forEach(function(b) {
                b.classList.remove('btn-primary', 'text-white');
            });
            this.classList.add('btn-primary', 'text-white');
            document.querySelector('.formato-radio[value="' + val + '"]').checked = true;
        });
    });
});

function toggleCampos(entidad) {
    document.querySelectorAll('[id^="campos_"]').forEach(function(el) {
        el.classList.add('hidden');
    });
    var target = document.getElementById('campos_' + entidad);
    if (target) target.classList.remove('hidden');
}

function seleccionarTodos(seleccionar) {
    var entidad = document.querySelector('.entidad-radio:checked').value;
    document.querySelectorAll('.campo-chk[data-entidad="' + entidad + '"]').forEach(function(cb) {
        cb.checked = seleccionar;
    });
}
</script>

<style>
.btn-outline.btn-primary { border-color: var(--p); color: var(--p); background: transparent; }
.btn-outline.btn-primary.text-white { background: var(--p); color: white; border-color: var(--p); }
</style>
