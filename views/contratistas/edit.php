<div class="max-w-4xl mx-auto">
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4 border-b pb-4">
                <div>
                    <h1 class="text-2xl font-bold text-base-content">Editar Contratista</h1>
                    <p class="text-sm opacity-60">Actualice la ficha tecnica del contratista.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>index.php?controller=contratista&action=index" class="btn btn-ghost btn-sm gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

            <form action="<?php echo BASE_URL; ?>index.php?controller=contratista&action=update" method="POST" class="space-y-4">
                <input type="hidden" name="id_contratista" value="<?= $contratista['id_contratista'] ?>">

                <div class="divider text-primary font-bold text-sm uppercase tracking-wider mt-0">I. Identificacion</div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Tipo de Persona *</span></label>
                        <select name="tipo_persona" required class="select select-bordered w-full">
                            <option value="Natural" <?= $contratista['tipo_persona'] == 'Natural' ? 'selected' : '' ?>>Persona Natural</option>
                            <option value="Juridica" <?= $contratista['tipo_persona'] == 'Jurídica' || $contratista['tipo_persona'] == 'Juridica' ? 'selected' : '' ?>>Persona Juridica (Empresa)</option>
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Tipo de Documento *</span></label>
                        <select name="tipo_documento" required class="select select-bordered w-full">
                            <option value="CC" <?= $contratista['tipo_documento'] == 'CC' ? 'selected' : '' ?>>Cedula de Ciudadania (CC)</option>
                            <option value="NIT" <?= $contratista['tipo_documento'] == 'NIT' ? 'selected' : '' ?>>NIT</option>
                            <option value="CE" <?= $contratista['tipo_documento'] == 'CE' ? 'selected' : '' ?>>Cedula de Extranjeria (CE)</option>
                            <option value="Pasaporte" <?= $contratista['tipo_documento'] == 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">No. de Documento *</span></label>
                        <input type="text" name="documento" required value="<?= htmlspecialchars($contratista['documento']) ?>" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control w-full md:col-span-2">
                        <label class="label"><span class="label-text font-semibold">Nombre o Razon Social *</span></label>
                        <input type="text" name="nombre_razon_social" required value="<?= htmlspecialchars($contratista['nombre_razon_social']) ?>" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Genero (SECOP) *</span></label>
                        <select name="genero" required class="select select-bordered w-full">
                            <option value="Masculino" <?= $contratista['genero'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= $contratista['genero'] == 'Femenino' ? 'selected' : '' ?>>Femenino</option>
                            <option value="Otro" <?= $contratista['genero'] == 'Otro' ? 'selected' : '' ?>>Otro</option>
                            <option value="No Aplica" <?= $contratista['genero'] == 'No Aplica' ? 'selected' : '' ?>>No Aplica (Juridica)</option>
                        </select>
                    </div>
                </div>

                <div class="divider text-primary font-bold text-sm uppercase tracking-wider mt-8">II. Datos de Contacto</div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control w-full md:col-span-1">
                        <label class="label"><span class="label-text font-semibold">Telefono / Celular</span></label>
                        <input type="text" name="telefono" value="<?= htmlspecialchars($contratista['telefono'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control w-full md:col-span-2">
                        <label class="label"><span class="label-text font-semibold">Correo Electronico</span></label>
                        <input type="email" name="correo" value="<?= htmlspecialchars($contratista['correo'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control w-full md:col-span-3">
                        <label class="label"><span class="label-text font-semibold">Direccion Fisica</span></label>
                        <input type="text" name="direccion" value="<?= htmlspecialchars($contratista['direccion'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>
                </div>

                <div class="divider text-primary font-bold text-sm uppercase tracking-wider mt-8">III. Informacion Bancaria (Pagos)</div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Entidad Bancaria</span></label>
                        <input type="text" name="entidad_bancaria" value="<?= htmlspecialchars($contratista['entidad_bancaria'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Tipo de Cuenta</span></label>
                        <select name="tipo_cuenta" class="select select-bordered w-full">
                            <option value="">-- Seleccione --</option>
                            <option value="Ahorros" <?= ($contratista['tipo_cuenta'] ?? '') == 'Ahorros' ? 'selected' : '' ?>>Ahorros</option>
                            <option value="Corriente" <?= ($contratista['tipo_cuenta'] ?? '') == 'Corriente' ? 'selected' : '' ?>>Corriente</option>
                        </select>
                    </div>

                    <div class="form-control w-full">
                        <label class="label"><span class="label-text font-semibold">Numero de Cuenta</span></label>
                        <input type="text" name="numero_cuenta" value="<?= htmlspecialchars($contratista['numero_cuenta'] ?? '') ?>" class="input input-bordered w-full" />
                    </div>
                </div>

                <div class="card-actions justify-end mt-8 border-t border-base-200 pt-6">
                    <button type="submit" class="btn btn-warning px-10 shadow-md">
                        <i class="fa-solid fa-save mr-2"></i> Actualizar Contratista
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
