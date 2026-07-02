<div class="max-w-5xl mx-auto space-y-6 pb-12">
    <div class="flex justify-between items-center bg-white p-6 rounded-box shadow-sm border border-base-300">
        <div>
            <h1 class="text-2xl font-bold" style="color:#1B5E20">Exportación de Datos</h1>
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
                        <button type="button" class="btn btn-sm px-5 entidad-btn active" data-value="todo">
                            <i class="fa-solid fa-database mr-2"></i> Exportar Todo
                        </button>
                        <input type="radio" name="entidad" value="todo" checked class="hidden entidad-radio">
                        <?php foreach ($entityInfo as $key => $info): ?>
                        <button type="button" class="btn btn-sm px-5 entidad-btn" data-value="<?= $key ?>">
                            <i class="fa-solid <?= $info['icon'] ?> mr-2"></i> <?= $info['label'] ?>
                        </button>
                        <input type="radio" name="entidad" value="<?= $key ?>" class="hidden entidad-radio">
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Rango de fechas -->
                <div id="fecha-group">
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
                <div id="campos-section">
                    <div class="flex items-center justify-between">
                        <label class="label-text font-bold text-base">3. Seleccione los campos a incluir</label>
                        <div class="flex gap-2">
                            <button type="button" class="btn btn-ghost btn-xs font-bold" style="color:#1B5E20" onclick="seleccionarTodos(true)">Seleccionar todos</button>
                            <button type="button" class="btn btn-ghost btn-xs font-bold" onclick="seleccionarTodos(false)">Deseleccionar</button>
                        </div>
                    </div>
                    <?php foreach ($fieldLabels as $entity => $fields): ?>
                    <div id="campos_<?= $entity ?>" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mt-2 hidden">
                        <?php foreach ($fields as $key => $label):
                            $checked = in_array($key, $defaultFields[$entity]) ? 'checked' : '';
                        ?>
                        <label class="flex items-center gap-2 p-2 rounded hover:bg-base-200 cursor-pointer text-sm">
                            <input type="checkbox" name="campos[]" value="<?= $key ?>" <?= $checked ?> class="checkbox checkbox-xs campo-chk" style="accent-color:#1B5E20" data-entidad="<?= $entity ?>">
                            <?= htmlspecialchars($label) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                    <p id="todo-msg" class="text-sm italic opacity-60 mt-1 hidden">Se exportarán <strong>todas las entidades</strong> con todos sus campos disponibles. El filtro de fechas no aplica en este modo.</p>
                </div>

                <!-- Formato -->
                <div>
                    <label class="label-text font-bold text-base">4. Elija el formato de exportación</label>
                    <div class="flex flex-wrap gap-3 mt-2" id="formato-group">
                        <button type="button" class="btn btn-sm px-6 formato-btn active" data-value="csv">
                            <i class="fa-solid fa-file-csv mr-2"></i> CSV
                        </button>
                        <input type="radio" name="formato" value="csv" checked class="hidden formato-radio">
                        <button type="button" class="btn btn-sm px-6 formato-btn" data-value="xls">
                            <i class="fa-solid fa-file-excel mr-2"></i> Excel (XLS)
                        </button>
                        <input type="radio" name="formato" value="xls" class="hidden formato-radio">
                        <button type="button" class="btn btn-sm px-6 formato-btn" data-value="pdf">
                            <i class="fa-solid fa-file-pdf mr-2"></i> PDF (vista imprimible)
                        </button>
                        <input type="radio" name="formato" value="pdf" class="hidden formato-radio">
                    </div>
                </div>

            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="btn btn-lg shadow-xl px-12" style="background:#1B5E20;color:white;border:none">
                <i class="fa-solid fa-download mr-2"></i> Exportar
            </button>
        </div>
    </form>
</div>

<style>
.entidad-btn, .formato-btn {
    border: 2px solid #1B5E20;
    background: transparent;
    color: #1B5E20;
    transition: all .15s ease;
}
.entidad-btn:hover, .formato-btn:hover {
    background: rgba(27,94,32,0.08);
}
.entidad-btn.active, .formato-btn.active {
    background: #1B5E20;
    color: #fff;
    border-color: #1B5E20;
}
.campo-chk:checked {
    accent-color: #1B5E20;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.entidad-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var val = this.getAttribute('data-value');
            document.querySelectorAll('.entidad-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            document.querySelector('.entidad-radio[value="' + val + '"]').checked = true;
            toggleCampos(val);
        });
    });

    document.querySelectorAll('.formato-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var val = this.getAttribute('data-value');
            document.querySelectorAll('.formato-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            document.querySelector('.formato-radio[value="' + val + '"]').checked = true;
        });
    });
});

function toggleCampos(entidad) {
    if (entidad === 'todo') {
        document.querySelectorAll('[id^="campos_"]').forEach(function(el) {
            el.classList.add('hidden');
        });
        document.getElementById('todo-msg').classList.remove('hidden');
        document.getElementById('campos-section').querySelector('.flex.items-center').classList.add('hidden');
        document.getElementById('fecha-group').classList.add('hidden');
    } else {
        document.getElementById('todo-msg').classList.add('hidden');
        document.getElementById('campos-section').querySelector('.flex.items-center').classList.remove('hidden');
        document.getElementById('fecha-group').classList.remove('hidden');
        document.querySelectorAll('[id^="campos_"]').forEach(function(el) {
            el.classList.add('hidden');
        });
        var target = document.getElementById('campos_' + entidad);
        if (target) target.classList.remove('hidden');
    }
}

function seleccionarTodos(seleccionar) {
    var entidad = document.querySelector('.entidad-radio:checked').value;
    document.querySelectorAll('.campo-chk[data-entidad="' + entidad + '"]').forEach(function(cb) {
        cb.checked = seleccionar;
    });
}
</script>
