<div class="max-w-6xl mx-auto space-y-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-box shadow-sm border border-base-300 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-primary">Gestion de Contratistas</h1>
            </div>
            <p class="text-xs opacity-70 uppercase tracking-wider mt-1">Directorio de contratistas y proveedores</p>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <form method="GET" action="index.php" class="flex gap-2 flex-1 md:flex-none">
                <input type="hidden" name="controller" value="contratista">
                <input type="hidden" name="action" value="index">
                <input type="text" name="q" placeholder="Buscar por documento o nombre..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="input input-bordered input-sm w-full md:w-64">
                <button type="submit" class="btn btn-primary btn-sm text-white"><i class="fa-solid fa-search"></i></button>
                <?php if(isset($_GET['q']) && trim($_GET['q']) !== ''): ?>
                    <a href="index.php?controller=contratista&action=index" class="btn btn-ghost btn-sm"><i class="fa-solid fa-times"></i></a>
                <?php endif; ?>
            </form>
            <a href="index.php?controller=contratista&action=create" class="btn btn-primary text-white shadow-sm gap-2">
                <i class="fa-solid fa-plus"></i> Nuevo Contratista
            </a>
        </div>
    </div>

    <div class="card bg-white shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-sm w-full">
                    <thead class="bg-base-100/50 text-neutral font-bold border-b border-base-200">
                        <tr>
                            <th>Tipo Doc.</th>
                            <th>Documento</th>
                            <th>Nombre / Razon Social</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($contratistas)): ?>
                            <?php foreach($contratistas as $c): ?>
                            <tr class="hover:bg-slate-50 transition-colors border-b border-base-100">
                                <td><span class="badge badge-sm badge-ghost"><?= htmlspecialchars($c['tipo_documento']) ?></span></td>
                                <td class="font-mono text-sm"><?= htmlspecialchars($c['documento']) ?></td>
                                <td class="font-medium text-neutral"><?= htmlspecialchars($c['nombre_razon_social']) ?></td>
                                <td class="text-sm"><?= htmlspecialchars($c['telefono'] ?? '') ?></td>
                                <td class="text-sm opacity-80"><?= htmlspecialchars($c['correo'] ?? '') ?></td>
                                <td class="text-center">
                                    <div class="flex gap-1 justify-center">
                                        <a href="index.php?controller=contratista&action=edit&id=<?= $c['id_contratista'] ?>" class="btn btn-ghost btn-xs text-warning" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="index.php?controller=contratista&action=delete&id=<?= $c['id_contratista'] ?>" class="btn btn-ghost btn-xs text-error" onclick="return confirm('Eliminar este contratista?')" title="Eliminar">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-8 text-neutral opacity-60">
                                    <?= isset($_GET['q']) ? 'No se encontraron contratistas con ese criterio de busqueda.' : 'No hay contratistas registrados.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
