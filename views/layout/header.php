<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información Contractual - Alcaldía de Silvia</title>
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [data-theme="light"] {
            /* --p (Primary): Tu Verde Institucional Puro (#008000) */
            --p: 52% 0.177 142.5; 
            /* --pf (Primary Focus): Verde ligeramente más oscuro para el efecto 'Hover' al pasar el mouse */
            --pf: 46% 0.177 142.5; 
            /* --pc (Primary Content): Texto blanco sobre los botones verdes */
            --pc: 100% 0 0; 
            
            /* --n (Neutral): Verde MUY oscuro casi negro para el menú lateral */
            --n: 28% 0.88 142.5; 
            /* --nc (Neutral Content): Texto blanco sobre el menú lateral */
            --nc: 100% 0 0; 
        }
    </style>
</head>
<body class="bg-base-200 flex h-screen overflow-hidden text-base-content">

    <?php include_once 'sidebar.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <div class="navbar bg-primary text-primary-content shadow-md z-10 px-4">
            <div class="flex-1">
                <label for="my-drawer" class="btn btn-square btn-ghost md:hidden">
                    <i class="fa-solid fa-bars text-xl"></i>
                </label>
                <a class="text-lg font-bold ml-2 hidden sm:block">Sistema de Información Contractual - Alcaldía de Silvia</a>
            </div>
            <div class="flex-none gap-4">
                <div class="flex items-center gap-2">
                    <div class="avatar placeholder">
                        <div class="bg-neutral-focus text-neutral-content rounded-full w-8">
                            <i class="fa-solid fa-user text-xs"></i>
                        </div>
                    </div>
                    <span class="text-sm font-medium hidden md:inline">
                        <?php echo $_SESSION['usuario_nombre']; ?>
                    </span>
                </div>
                <a href="<?php echo BASE_URL; ?>index.php?controller=auth&action=logout" class="btn btn-sm btn-ghost border-white/20">
                    Salir
                </a>
            </div>
        </div>

        <main class="flex-1 overflow-y-auto p-4 md:p-6">