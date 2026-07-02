<aside id="sidebar" class="w-64 bg-gradient-to-b from-[#1B5E20] to-[#2EA32E] text-white flex-none flex flex-col h-full shadow-2xl z-20 overflow-y-auto">

    <div class="p-6 flex flex-col items-center border-b border-white/10 min-h-[140px] justify-center">
        <img src="<?php echo BASE_URL; ?>assets/img/escudo.png" alt="Escudo" id="sidebar-logo" class="h-20 w-auto mb-2">
        <div class="text-center sidebar-text">
            <h1 class="text-s font-bold tracking-normal text-white drop-shadow-md">Alcaldía de Silvia</h1>
            <p class="text-[24px] text-green-400 font-bold drop-shadow-md">SICAS</p>
        </div>
    </div>

    <ul class="menu p-4 gap-2 text-white">
        <li>
            <a href="<?php echo BASE_URL; ?>index.php" class="hover:bg-white/10 transition-colors" title="Inicio">
                <i class="fa-solid fa-house w-6 text-center opacity-80"></i>
                <span class="sidebar-text">Inicio</span>
            </a>
        </li>

        <li class="menu-title text-green-400 font-bold text-xs mt-4 drop-shadow-sm sidebar-text">GESTIÓN</li>

        <li>
            <a href="<?php echo BASE_URL; ?>index.php?controller=contrato&action=index" class="hover:bg-white/10 transition-colors" title="Contratos">
                <i class="fa-solid fa-file-contract w-6 text-center"></i>
                <span class="sidebar-text">Contratos</span>
            </a>
        </li>
        <?php if(AuthHelper::esAdmin()): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?controller=contratista&action=index" class="hover:bg-white/10 transition-colors" title="Contratistas">
                    <i class="fa-solid fa-user-group w-6 text-center opacity-80"></i>
                    <span class="sidebar-text">Contratistas</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="menu-title text-green-400 font-bold text-xs mt-4 drop-shadow-sm sidebar-text">FINANZAS</li>

        <?php if(AuthHelper::esAdmin() || AuthHelper::esFinanciero()): ?>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?controller=contrato&action=index&from=pagos" class="hover:bg-white/10 transition-colors" title="Pagos">
                    <i class="fa-solid fa-money-bill-wave w-6 text-center opacity-80"></i>
                    <span class="sidebar-text">Pagos</span>
                </a>
            </li>
        <?php endif; ?>

        <li>
            <a href="<?php echo BASE_URL; ?>index.php?controller=presupuesto&action=index" class="hover:bg-white/10 transition-colors" title="Presupuesto">
                <i class="fa-solid fa-money-bill-trend-up w-6 text-center opacity-80"></i>
                <span class="sidebar-text">Presupuesto</span>
            </a>
        </li>

        <?php if(AuthHelper::esAdmin()): ?>
            <li class="menu-title text-green-400 font-bold text-xs mt-4 drop-shadow-sm sidebar-text">ADMINISTRACIÓN</li>

            <li>
                <a href="<?php echo BASE_URL; ?>index.php?controller=contratista&action=create" class="hover:bg-white/10 transition-colors" title="Crear Contratistas">
                    <i class="fa-solid fa-user-plus w-6 text-center opacity-80"></i>
                    <span class="sidebar-text">Crear Contratistas</span>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?controller=usuario&action=index" class="hover:bg-white/10 transition-colors" title="Supervisores / Usuarios">
                    <i class="fa-solid fa-user-shield w-6 text-center opacity-80"></i>
                    <span class="sidebar-text">Supervisores / Usuarios</span>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?controller=exportar&action=index" class="hover:bg-white/10 transition-colors" title="Exportar Datos">
                    <i class="fa-solid fa-download w-6 text-center opacity-80"></i>
                    <span class="sidebar-text">Exportar Datos</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="sidebar-text sidebar-footer mt-auto p-4 bg-black/20 text-[12px] text-center text-white/80 border-t border-white/10 backdrop-blur-sm">
        Sistema de Información Contractual <br> v1.0
    </div>
</aside>
