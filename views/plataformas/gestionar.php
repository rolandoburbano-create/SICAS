<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Control SECOP y SIA OBSERVA</h1>
            <p class="text-sm text-gray-500">Contrato No. <span class="font-bold text-emerald-700"><?php echo htmlspecialchars($contrato['numero_contrato']); ?></span></p>
        </div>
        <a href="<?php echo BASE_URL; ?>index.php?controller=contrato&action=index" class="text-gray-500 hover:text-emerald-600 font-medium">
            <i class="fa-solid fa-arrow-left mr-1"></i> Volver a Contratos
        </a>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
            <p class="text-sm text-green-700"><i class="fa-solid fa-cloud-check mr-2"></i> Estado de plataformas actualizado correctamente.</p>
        </div>
    <?php endif; ?>

    <div class="bg-gray-50 p-4 mb-6 rounded-md border border-gray-200">
        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Objeto del Contrato</p>
        <p class="text-sm text-gray-700"><?php echo htmlspecialchars($contrato['objeto_contrato']); ?></p>
    </div>

    <?php if(AuthHelper::esAdmin() || AuthHelper::esFinanciero()): ?>
    <form action="<?php echo BASE_URL; ?>index.php?controller=plataforma&action=store" method="POST">
        <input type="hidden" name="id_contrato" value="<?php echo $_GET['id']; ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white p-5 border border-emerald-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-emerald-100 p-3 rounded-full mr-3 text-emerald-600">
                        <i class="fa-solid fa-globe text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Estado SECOP II</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado de Publicación *</label>
                        <select name="estado_secop" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 bg-white">
                            <option value="Pendiente" <?php echo ($control && $control['estado_secop'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente por cargar</option>
                            <option value="En Borrador" <?php echo ($control && $control['estado_secop'] == 'En Borrador') ? 'selected' : ''; ?>>En Borrador</option>
                            <option value="Publicado" <?php echo ($control && $control['estado_secop'] == 'Publicado') ? 'selected' : ''; ?>>Publicado Oficialmente</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Enlace Público (URL)</label>
                        <input type="url" name="url_secop" value="<?php echo $control ? htmlspecialchars($control['url_secop']) : ''; ?>" class="w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500" placeholder="https://community.secop.gov.co/...">
                        <p class="text-xs text-gray-400 mt-1">Copie y pegue la URL pública del proceso.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 border border-blue-200 rounded-lg shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-full mr-3 text-blue-600">
                        <i class="fa-solid fa-file-shield text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">SIA OBSERVA</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado del Reporte *</label>
                        <select name="estado_sia_observa" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500 bg-white">
                            <option value="Pendiente" <?php echo ($control && $control['estado_sia_observa'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente de Reporte</option>
                            <option value="Cargado" <?php echo ($control && $control['estado_sia_observa'] == 'Cargado') ? 'selected' : ''; ?>>Cargado Exitosamente</option>
                            <option value="Con Observaciones" <?php echo ($control && $control['estado_sia_observa'] == 'Con Observaciones') ? 'selected' : ''; ?>>Rechazado / Con Observaciones</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones o Alertas</label>
                        <textarea name="observaciones" rows="3" class="w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500" placeholder="Detalle aquí si falta la firma del supervisor, pólizas, etc."><?php echo $control ? htmlspecialchars($control['observaciones']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 flex justify-center border-t pt-6">
             <button type="submit" class="btn btn-primary text-lg shadow-lg">
                <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Guardar Estados
            </button>
        </div>
    </form>
    <?php else: ?>
        <div class="bg-gray-100 border border-gray-200 p-8 rounded-lg text-center">
            <i class="fa-solid fa-eye-slash text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-500">Solo lectura</h3>
            <p class="text-gray-400 text-sm mt-1">No tienes permisos para modificar el estado de las plataformas.</p>
        </div>
    <?php endif; ?>
</div>