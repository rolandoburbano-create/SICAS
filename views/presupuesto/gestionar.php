<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ejecución Presupuestal</h1>
            <p class="text-sm text-gray-500">Contrato No. <span class="font-bold text-emerald-700"><?php echo htmlspecialchars($contrato['numero_contrato']); ?></span></p>
        </div>
        <a href="<?php echo BASE_URL; ?>index.php?controller=contrato&action=index" class="text-gray-500 hover:text-emerald-600 font-medium">
            <i class="fa-solid fa-arrow-left mr-1"></i> Volver a Contratos
        </a>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
            <p class="text-sm text-green-700"><i class="fa-solid fa-circle-check mr-2"></i> Pago registrado y saldos actualizados correctamente.</p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 col-span-1 h-fit">
            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Estado Financiero</h3>
            
            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-500">Contratista</p>
                    <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($contrato['nombre_razon_social']); ?></p>
                </div>
                <div>
                    <p class="text-gray-500">Valor Total del Contrato</p>
                    <p class="font-bold text-gray-800 text-lg">$<?php echo number_format($contrato['valor_total'], 2, ',', '.'); ?></p>
                </div>
                
                <?php if($presupuesto): ?>
                    <div class="pt-2 border-t border-gray-200">
                        <p class="text-gray-500">Pagos Realizados</p>
                        <p class="font-semibold text-blue-600"><?php echo $presupuesto['numero_pagos_realizados']; ?> de <?php echo $presupuesto['numero_pagos_proyectados']; ?></p>
                    </div>
                    <div class="bg-emerald-100 p-3 rounded-md mt-2">
                        <p class="text-emerald-800 font-semibold text-xs uppercase">Saldo Pendiente por Pagar</p>
                        <p class="font-bold text-emerald-900 text-xl">$<?php echo number_format($presupuesto['saldo_pendiente'], 2, ',', '.'); ?></p>
                    </div>
                <?php else: ?>
                    <div class="bg-yellow-100 p-3 rounded-md mt-2">
                        <p class="text-yellow-800 text-xs font-semibold"><i class="fa-solid fa-triangle-exclamation"></i> Presupuesto sin iniciar.</p>
                        <p class="text-yellow-700 text-xs mt-1">El saldo actual es igual al valor total del contrato.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-span-1 md:col-span-2">
            
            <?php 
            // Determinar si ya está pagado en su totalidad
            $ya_pagado = ($presupuesto && $presupuesto['saldo_pendiente'] <= 0);
            if($ya_pagado): 
            ?>
                <div class="bg-blue-50 border border-blue-200 p-8 rounded-lg text-center h-full flex flex-col justify-center">
                    <i class="fa-solid fa-check-double text-5xl text-blue-400 mb-4"></i>
                    <h3 class="text-xl font-bold text-blue-800">Contrato Ejecutado al 100%</h3>
                    <p class="text-blue-600 mt-2">No hay saldo pendiente por pagar en este contrato.</p>
                </div>
            <?php else: ?>
                <h3 class="text-lg font-bold text-gray-700 mb-4">Registrar Nuevo Pago (Acta)</h3>
                
                <form action="<?php echo BASE_URL; ?>index.php?controller=presupuesto&action=registrarPago" method="POST" class="space-y-4 bg-white p-5 border border-gray-200 rounded-lg">
                    
                    <input type="hidden" name="id_contrato" value="<?php echo $contrato['id_contrato']; ?>">
                    <input type="hidden" name="valor_asignado" value="<?php echo $contrato['valor_total']; ?>">

                    <?php if(!$presupuesto): // Solo pedir esto en el primer pago ?>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rubros Presupuestales</label>
                                <input type="text" name="rubros_presupuestales" required class="w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500" placeholder="Ej. 2.3.2.02.02.08">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Pagos Proyectados</label>
                                <input type="number" name="numero_pagos_proyectados" required min="1" class="w-full border border-gray-300 rounded-md p-2 focus:ring-emerald-500" placeholder="Ej. 6">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monto a Pagar ($) *</label>
                        <input type="number" step="0.01" name="monto_pago" required max="<?php echo $presupuesto ? $presupuesto['saldo_pendiente'] : $contrato['valor_total']; ?>" class="w-full border border-gray-300 rounded-md p-3 text-lg font-bold focus:ring-emerald-500 focus:border-emerald-500" placeholder="Valor del acta actual">
                        <p class="text-xs text-gray-500 mt-1">El monto no puede superar el saldo pendiente.</p>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-lg shadow transition">
                            <i class="fa-solid fa-file-invoice-dollar mr-2"></i> Procesar Pago y Descontar Saldo
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

    </div>
</div>