<div class="max-w-6xl mx-auto space-y-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-box shadow-sm border border-base-200 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-primary">Gestion de Usuarios</h1>
            </div>
            <p class="text-xs opacity-70 uppercase tracking-wider mt-1">Configuracion del Sistema SICAS</p>
        </div>
        <div>
            <button class="btn btn-primary text-white shadow-sm gap-2" onclick="modal_nuevo_usuario.showModal()">
                <i class="fa-solid fa-user-plus"></i> Nuevo Funcionario
            </button>
        </div>
    </div>

    <div class="card bg-white shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-base-100/50 text-neutral font-bold border-b border-base-200">
                        <tr>
                            <th>Documento</th>
                            <th>Nombre Completo</th>
                            <th>Correo Electronico</th>
                            <th>Rol de Acceso</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($usuarios)): ?>
                            <?php foreach($usuarios as $user): ?>
                            <tr class="hover:bg-slate-50 transition-colors border-b border-base-100">
                                <td class="text-sm font-mono"><?= htmlspecialchars($user['documento']) ?></td>
                                <td class="font-medium text-neutral">
                                    <?= htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']) ?>
                                </td>
                                <td class="text-sm opacity-80"><?= htmlspecialchars($user['correo']) ?></td>
                                <td>
                                    <?php 
                                        $rol_nombre = "Desconocido";
                                        $badge_color = "badge-neutral";
                                        if($user['id_rol'] == 1) { $rol_nombre = "Administrador"; $badge_color = "badge-primary"; }
                                        if($user['id_rol'] == 2) { $rol_nombre = "Financiero"; $badge_color = "badge-success"; }
                                        if($user['id_rol'] == 3) { $rol_nombre = "Consulta"; $badge_color = "badge-ghost"; }
                                        if($user['id_rol'] == 4) { $rol_nombre = "Supervisor"; $badge_color = "badge-info"; }
                                        if($user['id_rol'] == 5) { $rol_nombre = "Radicacion"; $badge_color = "badge-warning"; }
                                    ?>
                                    <span class="badge <?= $badge_color ?> badge-sm text-white font-semibold"><?= $rol_nombre ?></span>
                                </td>
                                <td>
                                    <span class="badge badge-outline <?= $user['estado'] == 'Activo' ? 'badge-success' : 'badge-error' ?> text-xs">
                                        <?= htmlspecialchars($user['estado']) ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex gap-1 justify-end">
                                        <button onclick="abrirModalEditar(<?= $user['id_usuario'] ?>, '<?= htmlspecialchars(addslashes($user['nombres'])) ?>', '<?= htmlspecialchars(addslashes($user['apellidos'])) ?>', '<?= htmlspecialchars(addslashes($user['correo'])) ?>', <?= $user['id_rol'] ?>, '<?= htmlspecialchars(addslashes($user['estado'])) ?>')" class="btn btn-xs btn-outline btn-warning shadow-sm" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button onclick="abrirModalPassword(<?= $user['id_usuario'] ?>, '<?= htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']) ?>')" class="btn btn-xs btn-outline btn-info shadow-sm" title="Cambiar Contrasena">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                        <a href="index.php?controller=usuario&action=delete&id=<?= $user['id_usuario'] ?>" class="btn btn-xs btn-outline btn-error shadow-sm" onclick="return confirm('Eliminar este usuario?')" title="Eliminar">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-8 text-neutral opacity-60">No hay usuarios registrados en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<dialog id="modal_nuevo_usuario" class="modal">
    <div class="modal-box bg-white border border-base-200 shadow-lg">
        <h3 class="font-bold text-lg text-primary mb-4 border-b pb-2">Registrar Nuevo Funcionario</h3>
        
        <form action="index.php?controller=usuario&action=store" method="POST">
            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text font-bold text-neutral">Documento *</span></label>
                <input type="number" name="documento" required placeholder="Ej: 1061000000" class="input input-bordered bg-white w-full" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-neutral">Nombres *</span></label>
                    <input type="text" name="nombres" required class="input input-bordered bg-white w-full" />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-neutral">Apellidos *</span></label>
                    <input type="text" name="apellidos" required class="input input-bordered bg-white w-full" />
                </div>
            </div>

            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text font-bold text-neutral">Correo *</span></label>
                <input type="email" name="correo" required placeholder="ejemplo@silvia.gov.co" class="input input-bordered bg-white w-full" />
            </div>

            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text font-bold text-neutral">Contrasena *</span></label>
                <input type="password" name="password" required class="input input-bordered bg-white w-full" />
            </div>

            <div class="form-control w-full mb-6">
                <label class="label"><span class="label-text font-bold text-neutral">Rol *</span></label>
                <select name="id_rol" required class="select select-bordered bg-white w-full">
                    <option value="" disabled selected>Seleccione el nivel de acceso</option>
                    <option value="1">Administrador (Control Total)</option>
                    <option value="2">Financiera / Presupuesto</option>
                    <option value="3">Consulta / Control Interno</option>
                    <option value="4">Supervisor de Contrato</option>
                    <option value="5">Radicacion</option>
                </select>
            </div>

            <div class="modal-action border-t border-base-200 pt-4">
                <button type="button" class="btn btn-ghost text-neutral" onclick="modal_nuevo_usuario.close()">Cancelar</button>
                <button type="submit" class="btn btn-primary text-white">Crear Usuario</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="modal_editar_usuario" class="modal">
    <div class="modal-box bg-white border border-base-200 shadow-lg">
        <h3 class="font-bold text-lg text-primary mb-4 border-b pb-2">Editar Funcionario</h3>
        
        <form action="index.php?controller=usuario&action=update" method="POST">
            <input type="hidden" name="id_usuario" id="edit_id_usuario" value="">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-neutral">Nombres *</span></label>
                    <input type="text" name="nombres" id="edit_nombres" required class="input input-bordered bg-white w-full" />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-neutral">Apellidos *</span></label>
                    <input type="text" name="apellidos" id="edit_apellidos" required class="input input-bordered bg-white w-full" />
                </div>
            </div>

            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text font-bold text-neutral">Correo *</span></label>
                <input type="email" name="correo" id="edit_correo" required class="input input-bordered bg-white w-full" />
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-neutral">Rol *</span></label>
                    <select name="id_rol" id="edit_id_rol" required class="select select-bordered bg-white w-full">
                        <option value="1">Administrador</option>
                        <option value="2">Financiera / Presupuesto</option>
                        <option value="3">Consulta / Control Interno</option>
                        <option value="4">Supervisor de Contrato</option>
                        <option value="5">Radicacion</option>
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-bold text-neutral">Estado *</span></label>
                    <select name="estado" id="edit_estado" required class="select select-bordered bg-white w-full">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="modal-action border-t border-base-200 pt-4">
                <button type="button" class="btn btn-ghost text-neutral" onclick="modal_editar_usuario.close()">Cancelar</button>
                <button type="submit" class="btn btn-warning text-white">Actualizar Usuario</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="modal_cambiar_password" class="modal">
    <div class="modal-box bg-white border border-base-200 shadow-lg">
        <h3 class="font-bold text-lg text-primary mb-1">Cambiar Contrasena</h3>
        <p class="text-xs opacity-70 mb-4 border-b pb-2">Asignando nueva credencial a: <span id="nombre_usuario_pwd" class="font-bold text-neutral"></span></p>
        
        <form action="index.php?controller=usuario&action=updatePassword" method="POST">
            <input type="hidden" name="id_usuario" id="id_usuario_pwd" value="">

            <div class="form-control w-full mb-6">
                <label class="label"><span class="label-text font-bold text-neutral">Nueva Contrasena *</span></label>
                <input type="password" name="nueva_password" required class="input input-bordered bg-white w-full" />
            </div>

            <div class="modal-action border-t border-base-200 pt-4">
                <button type="button" class="btn btn-ghost text-neutral" onclick="modal_cambiar_password.close()">Cancelar</button>
                <button type="submit" class="btn btn-warning text-white">Actualizar Contrasena</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    function abrirModalPassword(id, nombre) {
        document.getElementById('id_usuario_pwd').value = id;
        document.getElementById('nombre_usuario_pwd').innerText = nombre;
        document.getElementById('modal_cambiar_password').showModal();
    }

    function abrirModalEditar(id, nombres, apellidos, correo, id_rol, estado) {
        document.getElementById('edit_id_usuario').value = id;
        document.getElementById('edit_nombres').value = nombres;
        document.getElementById('edit_apellidos').value = apellidos;
        document.getElementById('edit_correo').value = correo;
        document.getElementById('edit_id_rol').value = id_rol;
        document.getElementById('edit_estado').value = estado;
        document.getElementById('modal_editar_usuario').showModal();
    }
</script>
