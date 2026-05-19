<div class="max-w-4xl mx-auto">
    <div class="card bg-base-100 shadow-xl border border-base-300">
        <div class="card-body">
            <div class="flex justify-between items-center mb-4 border-b pb-4">
                <div>
                    <h1 class="text-2xl font-bold">Registrar Nuevo Supervisor</h1>
                    <p class="text-sm opacity-60">Personal administrativo o contratistas de apoyo a la supervisión.</p>
                </div>
                <i class="fa-solid fa-user-tie text-4xl text-primary opacity-20"></i>
            </div>

            <form action="index.php?controller=usuario&action=store" method="POST" class="space-y-6">
                <input type="hidden" name="id_rol" value="4">

                <div class="divider text-primary font-bold text-sm uppercase tracking-wider">Identificación</div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Tipo de Persona</span></label>
                        <select name="tipo_persona" class="select select-bordered">
                            <option value="Natural">Persona Natural</option>
                            <option value="Jurídica">Persona Jurídica</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Tipo de Documento</span></label>
                        <select name="tipo_documento" class="select select-bordered">
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="NIT">NIT</option>
                            <option value="CE">Cédula de Extranjería</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Número de Documento</span></label>
                        <input type="text" name="documento" required class="input input-bordered" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Nombres</span></label>
                        <input type="text" name="nombres" required class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Apellidos</span></label>
                        <input type="text" name="apellidos" required class="input input-bordered" />
                    </div>
                </div>

                <div class="divider text-primary font-bold text-sm uppercase tracking-wider">Vinculación Institucional</div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Tipo de Vinculación</span></label>
                        <select name="tipo_vinculacion" class="select select-bordered">
                            <option value="Carrera Administrativa">Carrera Administrativa</option>
                            <option value="Libre Nombramiento">Libre Nombramiento y Remoción</option>
                            <option value="Provisionalidad">Provisionalidad</option>
                            <option value="Contratista de Prestación de Servicios">Contratista de Apoyo</option>
                        </select>
                    </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Secretaría / Oficina *</span></label>
                    <select name="secretaria" required class="select select-bordered w-full">
                        <option value="">-- Seleccione la dependencia --</option>
                        <option value="Despacho del Alcalde">Despacho del Alcalde</option>
                        <option value="Secretaría de Gobierno y Participación Ciudadana">Secretaría de Gobierno y Participación Ciudadana</option>
                        <option value="Secretaría Administrativa y Financiera">Secretaría Administrativa y Financiera</option>
                        <option value="Oficina Asesora de Planeación">Oficina Asesora de Planeación</option>
                        <option value="Secretaría de Infraestructura">Secretaría de Infraestructura</option>
                        <option value="Secretaría de Bienestar y Desarrollo Social">Secretaría de Bienestar y Desarrollo Social</option>
                        <option value="Secretaría de Desarrollo Productivo y Ambiental">Secretaría de Desarrollo Productivo y Ambiental</option>
                        <option value="Oficina Asesora Jurídica">Oficina Asesora Jurídica</option>
                        <option value="Oficina Asesora de Control Interno">Oficina Asesora de Control Interno</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Correo Electrónico Institucional</span></label>
                    <input type="email" name="correo" required class="input input-bordered" placeholder="supervisor@silvia-cauca.gov.co" />
                </div>

                <div class="card-actions justify-end mt-6">
                    <button type="submit" class="btn btn-primary px-10">
                        <i class="fa-solid fa-save mr-2"></i> Guardar Supervisor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>