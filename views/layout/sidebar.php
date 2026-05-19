<aside class="w-64 bg-gradient-to-b from-[#1B5E20] to-[#2EA32E] text-white flex-none flex flex-col h-full shadow-2xl z-20">
    
    <div class="p-6 flex flex-col items-center border-b border-white/10">
        <img src="<?php echo BASE_URL; ?>assets/img/escudo.png" alt="Escudo" class="h-20 w-auto">
        <div class="text-center">
            <p class="text-[4px] text-green-100 font-bold ">.</p>
            <h1 class="text-s font-bold tracking-normal text-white drop-shadow-md">Alcaldía de Silvia</h1>
            <p class="text-[24px] text-green-400 font-bold drop-shadow-md">SICAS</p>
        </div>
    </div>

    <ul class="menu p-4 gap-2 text-white">
        <li>
            <a href="<?php echo BASE_URL; ?>index.php" class="hover:bg-white/10 transition-colors">
                <i class="fa-solid fa-house w-5 opacity-80"></i> Inicio
            </a>
        </li>

        <li class="menu-title text-green-400 font-bold text-xs mt-4 drop-shadow-sm">GESTIÓN</li>
        
        <li>
            <a href="<?php echo BASE_URL; ?>index.php?controller=contrato&action=index" class="hover:bg-white/10 transition-colors">
                <i class="fa-solid fa-file-contract w-5"></i> Contratos
            </a>
        </li>
        <li>
            <a href="<?php echo BASE_URL; ?>index.php?controller=contratista&action=create" class="hover:bg-white/10 transition-colors">
                <i class="fa-solid fa-user-group w-5 opacity-80"></i> Contratistas
            </a>
        </li>
        <li>
            <a class="hover:bg-white/10 transition-colors"><i class="fa-solid fa-upload w-5 opacity-80"></i> Carga SECOP/SIA</a>
        </li>

        <li class="menu-title text-green-400 font-bold text-xs mt-4 drop-shadow-sm">FINANZAS</li>

        <li>
            <a class="hover:bg-white/10 transition-colors"><i class="fa-solid fa-money-bill-trend-up w-5 opacity-80"></i> Presupuesto</a>
        </li>
        
        <li>
            <a class="hover:bg-white/10 transition-colors"><i class="fa-solid fa-upload w-5 opacity-80"></i> Carga SECOP/SIA</a>
        </li>
        
        <?php if(AuthHelper::esAdmin()): ?>
            <li class="menu-title text-green-400 font-bold text-xs mt-4 drop-shadow-sm">ADMINISTRACIÓN</li>
            
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?controller=contratista&action=create" class="hover:bg-white/10 transition-colors">
                    <i class="fa-solid fa-user-group w-5 opacity-80"></i> Crear Contratistas
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>index.php?controller=usuario&action=create" class="hover:bg-white/10 transition-colors">
                    <i class="fa-solid fa-user-shield w-5 opacity-80"></i> Supervisores / Usuarios
                </a>
            </li>
        <?php endif; ?>
    </ul>
    <div class="mt-auto p-4 bg-black/20 text-[12px] text-center text-white/80 border-t border-white/10 backdrop-blur-sm">
        Sistema de Información Contractual <br> v1.0
    </div>
</aside>