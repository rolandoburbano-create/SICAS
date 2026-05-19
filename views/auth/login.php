<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SICAS</title>
    
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
            --n: 28% 0.08 142.5; 
            /* --nc (Neutral Content): Texto blanco sobre el menú lateral */
            --nc: 100% 0 0; 
        }
    </style>
</head>
<body class="bg-base-200">
    <?php
    // Supongamos que el estado del producto es "alerta"
    $nombre = "SICAS";
    $color = ($nombre == "SICAS") ? "#018001" : "#ffffff";
    ?>
   
    <div class="min-h-screen w-full flex items-center justify-center p-4">
        
        <div class="card w-full max-w-md bg-base-100 shadow-2xl border-t-8 border-primary">
            <div class="card-body">
                
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold">Alcaldía de Silvia</h1>
                    <img src="<?php echo BASE_URL; ?>assets/img/escudo.png" 
                         alt="Escudo Alcaldía de Silvia" 
                         class="h-28 w-auto mx-auto mb-4 drop-shadow-md">
                    <span class="badge" style="background-color: <?php echo $color; ?>; color: white;">
                        <?php echo ucfirst($nombre); ?>
                    </span>

                    <div class="badge badge-primary badge-outline font-semibold mt-2 p-3">
                        Sistema de Información Contractual - Alcaldia de Silvia
                    </div>
                </div>

                <form action="<?php echo BASE_URL; ?>index.php?controller=auth&action=login" method="POST" class="space-y-4">
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Correo Institucional</span>
                        </label>
                        <label class="input input-bordered flex items-center gap-2">
                            <i class="fa-solid fa-envelope opacity-50"></i>
                            <input type="email" name="correo" required class="grow" placeholder="usuario@silvia-cauca.gov.co" />
                        </label>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Contraseña</span>
                        </label>
                        <label class="input input-bordered flex items-center gap-2">
                            <i class="fa-solid fa-lock opacity-50"></i>
                            <input type="password" name="password" required class="grow" placeholder="••••••••" />
                        </label>
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary text-lg shadow-lg">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> Ingresar
                        </button>
                    </div>
                </form>

                <div class="divider text-xs opacity-50 uppercase tracking-widest">Seguridad</div>
                <div class="text-center text-[10px] opacity-60">
                    &copy; <?php echo date('Y'); ?> Municipio de Silvia, Cauca. <br>
                    Leyes 80 de 1993 y 1474 de 2011.
                </div>
            </div>
        </div>

    </div>

</body>
</html>