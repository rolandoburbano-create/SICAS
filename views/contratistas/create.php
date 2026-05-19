<div class="max-w-4xl mx-auto">
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4 border-b pb-4">
                <div>
                    <h1 class="text-2xl font-bold text-base-content">Registrar Contratista</h1>
                    <p class="text-sm opacity-60">Complete la ficha técnica del contratista para pagos y notificaciones.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>index.php?controller=contrato&action=create" class="btn btn-ghost btn-sm gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

            <form action="<?php echo BASE_URL; ?>index.php?controller=contratista&action=store" method="POST" class="space-y-4">
                
                <div class="divider text-primary font-bold text-sm uppercase tracking-wider mt-0">I. Identificación</div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Tipo de Persona *</span></label>
                        <select name="tipo_persona" required class="select select-bordered w-full">
                            <option value="Natural">Persona Natural</option>
                            <option value="Jurídica">Persona Jurídica (Empresa)</option>
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Tipo de Documento *</span></label>
                        <select name="tipo_documento" required class="select select-bordered w-full">
                            <option value="CC">Cédula de Ciudadanía (CC)</option>
                            <option value="NIT">NIT</option>
                            <option value="CE">Cédula de Extranjería (CE)</option>
                            <option value="Pasaporte">Pasaporte</option>
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">No. de Documento *</span></label>
                        <input type="text" name="documento" required class="input input-bordered w-full" placeholder="Ej. 1061234567" />
                    </div>

                    <div class="form-control w-full md:col-span-2">
                        <label class="label"><span class="label-text font-semibold">Nombre o Razón Social *</span></label>
                        <input type="text" name="nombre_razon_social" required class="input input-bordered w-full" placeholder="Nombre completo o nombre de la empresa" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Género (SECOP) *</span></label>
                        <select name="genero" required class="select select-bordered w-full">
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                            <option value="No Aplica">No Aplica (Jurídica)</option>
                        </select>
                    </div>
                </div>

                <div class="divider text-primary font-bold text-sm uppercase tracking-wider mt-8">II. Datos de Contacto</div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control w-full md:col-span-1">
                        <label class="label"><span class="label-text font-semibold">Teléfono / Celular</span></label>
                        <input type="text" name="telefono" class="input input-bordered w-full" placeholder="Ej. 310 000 0000" />
                    </div>

                    <div class="form-control w-full md:col-span-2">
                        <label class="label"><span class="label-text font-semibold">Correo Electrónico</span></label>
                        <input type="email" name="correo" class="input input-bordered w-full" placeholder="notificaciones@correo.com" />
                    </div>

                    <div class="form-control w-full md:col-span-3">
                        <label class="label"><span class="label-text font-semibold">Dirección Física</span></label>
                        <input type="text" name="direccion" class="input input-bordered w-full" placeholder="Ej. Calle 1 # 2-3, Silvia, Cauca" />
                    </div>
                </div>

                <div class="divider text-primary font-bold text-sm uppercase tracking-wider mt-8">III. Información Bancaria (Pagos)</div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Entidad Bancaria</span></label>
                        <input type="text" name="entidad_bancaria" class="input input-bordered w-full" placeholder="Ej. Banco Agrario / Bancolombia" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Tipo de Cuenta</span></label>
                        <select name="tipo_cuenta" class="select select-bordered w-full">
                            <option value="">-- Seleccione --</option>
                            <option value="Ahorros">Ahorros</option>
                            <option value="Corriente">Corriente</option>
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Número de Cuenta</span></label>
                        <input type="text" name="numero_cuenta" class="input input-bordered w-full" placeholder="Ej. 0000-0000-00" />
                    </div>
                </div>

                <div class="card-actions justify-end mt-8 border-t border-base-200 pt-6">
                    <button type="submit" class="btn btn-primary px-10 shadow-md">
                        <i class="fa-solid fa-save mr-2"></i> Guardar Ficha del Contratista
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>