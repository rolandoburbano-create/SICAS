<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-white p-4 rounded-box shadow-sm border border-base-300">
        <div>
            <h1 class="text-2xl font-bold text-primary">Radicación Integral de Contrato</h1>
            <p class="text-xs opacity-60 uppercase tracking-widest">Alcaldía de Silvia - Gestión Contractual</p>
        </div>
        <a href="index.php?controller=contrato&action=index" class="btn btn-ghost btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Volver al Listado
        </a>
    </div>
    
    <form action="index.php?controller=contrato&action=store" method="POST" class="space-y-4 pb-10">
        
        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-s">I. Identificación y Localización</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Número de Contrato *</span></label>
                        <input type="text" name="numero_contrato" required class="input input-bordered border-primary" placeholder="Ej. 001-2026" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Código BPIN</span></label>
                        <input type="text" name="bpin" class="input input-bordered" placeholder="Código de inversión" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Estado del Proceso</span></label>
                        <select name="estado" class="select select-bordered text-primary font-bold">
                            <option value="Celebrado" selected>Celebrado</option>
                            <option value="Activo">Activo / En ejecución</option>
                        </select>
                    </div>
                    <div class="form-control md:col-span-3">
                        <label class="label"><span class="label-text font-bold">Línea Estratégica (Plan de Desarrollo) *</span></label>
                        <select name="linea_estrategica" required class="select select-bordered">
                            <option value="1. Silvia un referente de turismo">1. Silvia un referente de turismo</option>
                            <option value="2. Minga para un desarrollo armónico posible">2. Minga para un desarrollo armónico posible</option>
                            <option value="3. Bienestar posible para la vida">3. Bienestar posible para la vida</option>
                            <option value="4. Equidad y armonía territorial posible">4. Equidad y armonía territorial posible</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-s">II. Información General</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control md:col-span-4">
                        <label class="label"><span class="label-text font-bold">Objeto Contractual *</span></label>
                        <textarea name="objeto_contrato" required class="textarea textarea-bordered h-24" placeholder="Descripción detallada del contrato..."></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Contratista *</span></label>
                        <div class="relative" id="contratista-autocomplete">
                            <input type="text" id="contratista-search" class="input input-bordered w-full" placeholder="Busque por nombre, apellido o documento..." autocomplete="off">
                            <input type="hidden" name="id_contratista" id="contratista-id">
                            <div id="contratista-results" class="absolute z-50 w-full mt-1 bg-white shadow-lg border border-base-300 rounded-box hidden max-h-60 overflow-y-auto"></div>
                        </div>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Supervisor *</span></label>
                        <select name="id_supervisor" required class="select select-bordered w-full">
                            <option value="">Seleccione al Supervisor...</option>
                            <?php foreach($supervisores as $sup): ?>
                                <option value="<?= $sup['id_usuario'] ?>">
                                    <?= $sup['nombres'] ?> <?= $sup['apellidos'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Secretaría / Oficina *</span></label>
                            <select name="secretaria" class="select select-bordered w-full">
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
                        <label class="label"><span class="label-text font-bold">Fuente de Recursos</span></label>
                        <select name="fuente_recursos" class="select select-bordered w-full">
                            <option value="">Seleccione una fuente...</option>
                            <option value="Recursos Propios">Recursos Propios (Libre Destinación)</option>
                            <option value="SGP - Salud">SGP - Salud</option>
                            <option value="SGP - Educación">SGP - Educación</option>
                            <option value="SGP - Propósito General">SGP - Propósito General</option>
                            <option value="Sistema General de Regalías">Sistema General de Regalías (SGR)</option>
                            <option value="Cofinanciación">Cofinanciación (Nación/Departamento)</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Modalidad *</span></label>
                        <select name="modalidad_seleccion" required class="select select-bordered">
                            <option value="Concurso de méritos">Concurso de méritos</option>
                            <option value="Contratación directa" selected>Contratación directa</option>
                            <option value="Licitación">Licitación</option>
                            <option value="Mínima cuantía">Mínima cuantía</option>
                            <option value="Selección abreviada">Selección abreviada</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Tipo de Contrato *</span></label>
                        <select name="tipo_contrato" required class="select select-bordered">
                            <option value="Compraventa">Compraventa</option>
                            <option value="Consultoría">Consultoría</option>
                            <option value="Contrato interadministrativo">Contrato interadministrativo</option>
                            <option value="Convenio interadministrativo">Convenio interadministrativo</option>
                            <option value="Convenio interadministrativo">Convenio interadministrativo - Cabildos</option>
                            <option value="Obra pública">Obra pública</option>
                            <option value="Prestación de Servicios" selected>Prestación de Servicios</option>
                            <option value="Suministro">Suministro</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-s">III. Información Presupuestal y Financiera</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control md:col-span-1.5">
                        <label class="label"><span class="label-text font-bold text-md text-primary">Valor Total del Contrato ($) *</span></label>
                        <input type="text" inputmode="numeric" name="valor_total" required class="currency-input input input-bordered border-primary text-xl font-bold" placeholder="0" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Forma de Pago</span></label>
                        <select name="forma_pago" required class="select select-bordered">
                            <option value="Actas mensuales">Actas mensuales</option>
                            <option value="Actas parciales">Actas parciales</option>
                            <option value="Único pago" selected>Único pago</option>
                        </select>
                    </div>
                    <!-- NUEVOS CAMPOS PRESUPUESTALES -->
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 border-t pt-4 border-base-300">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Número CDP</span></label>
                        <input type="text" name="cdp" placeholder="Ej: 2026-001" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha CDP</span></label>
                        <input type="date" name="fecha_cdp" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Valor CDP ($)</span></label>
                        <input type="text" inputmode="numeric" name="valor_cdp" placeholder="0" class="currency-input input input-bordered w-full" />
                    </div>
                    <?php if(AuthHelper::esAdmin()): ?>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Número RP</span></label>
                        <input type="text" name="rp" placeholder="Ej: 2026-045" class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Valor RP ($)</span></label>
                        <input type="text" inputmode="numeric" name="valor_rp" placeholder="0" class="currency-input input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Rubro Presupuestal</span></label>
                        <input type="text" name="rubro_presupuestal" placeholder="Ej: 2.3.1.01" class="input input-bordered w-full" />
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if(AuthHelper::esAdmin() || AuthHelper::esRadicacion()): ?>
        <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-s">IV. Cronología</div>
                <div class="grid grid-cols-1 <?= AuthHelper::esAdmin() ? 'md:grid-cols-4' : 'md:grid-cols-3' ?> gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Firma *</span></label>
                        <input type="date" name="fecha_firma" id="fecha_firma_calc" class="input input-bordered" />
                    </div>
                    <?php if(AuthHelper::esAdmin()): ?>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Inicio *</span></label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio_calc" class="input input-bordered" />
                    </div>
                    <?php endif; ?>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Terminación Pactada*</span></label>
                        <input type="date" name="fecha_terminacion" id="fecha_terminacion_calc" class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Plazo</span></label>
                        <input type="text" name="plazo_ejecucion" id="plazo_ejecucion_calc" class="input input-bordered font-bold text-primary" placeholder="0 Días" />
                    </div>
                </div>
                
                <?php if(AuthHelper::esAdmin()): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 border-t pt-4 border-base-300">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Terminación Real</span></label>
                        <input type="date" name="fecha_terminacion_real" id="fecha_terminacion_real_calc" class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Fecha Liquidación</span></label>
                        <input type="date" name="fecha_liquidacion" class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold">Plazo Final Ejecutado</span></label>
                        <input type="text" name="plazo_ejecucion_real" id="plazo_ejecucion_real_calc" class="input input-bordered font-bold text-success" placeholder="0 Días" />
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if(AuthHelper::esAdmin()): ?>
        <!-- ENLACES A PLATAFORMAS -->
        <div class="card bg-base-100 shadow-md border border-base-300 mb-6">
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-primary"><i class="fa-solid fa-link"></i> Enlace SECOP</span>
                            <span class="label-text-alt opacity-70">Opcional</span>
                        </label>
                        <input type="url" name="link_secop" placeholder="https://www.secop.gov.co/..." class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-primary"><i class="fa-solid fa-link"></i> Enlace SIA OBSERVA</span>
                            <span class="label-text-alt opacity-70">Opcional</span>
                        </label>
                        <input type="url" name="link_sia" placeholder="https://..." class="input input-bordered w-full" />
                    </div>
                </div>
            </div>
        </div>
        
       <div class="card bg-base-100 shadow-md border border-base-300">
            <div class="card-body">
                <div class="divider divider-start text-primary font-bold uppercase text-s">V. Novedades</div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-primary">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="tiene_prorroga" class="toggle toggle-primary toggle-sm" />
                                <span class="label-text font-bold">Prórroga</span>
                            </label>
                        </div>
                        <div class="space-y-2 mt-2">
                            <input type="number" name="numero_prorroga" placeholder="No. Prórroga" class="input input-xs input-bordered w-full" />
                            <input type="text" name="tiempo_prorroga" placeholder="Tiempo (Ej. 2 meses)" class="input input-xs input-bordered w-full" />
                        </div>
                    </div>
                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-warning">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="tiene_suspension" class="toggle toggle-warning toggle-sm" />
                                <span class="label-text font-bold">Suspensión</span>
                            </label>
                        </div>
                        <div class="space-y-2 mt-2">
                            <input type="number" name="numero_suspension" placeholder="No. Suspensión" class="input input-xs input-bordered w-full" />
                            <input type="text" name="duracion_suspension" placeholder="Días de duración" class="input input-xs input-bordered w-full" />
                        </div>
                    </div>
                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-info">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="tiene_reinicio" class="toggle toggle-info toggle-sm" />
                                <span class="label-text font-bold">Reinicio</span>
                            </label>
                        </div>
                        <div class="space-y-2 mt-2">
                            <input type="number" name="numero_reinicio" placeholder="No. Reinicio" class="input input-xs input-bordered w-full" />
                            <input type="date" name="fecha_reinicio" class="input input-xs input-bordered w-full" />
                        </div>
                    </div>
                    <div class="p-4 bg-base-200 rounded-box border-l-4 border-error">
                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="tiene_cesion" class="toggle toggle-error toggle-sm" />
                                <span class="label-text font-bold">Cesión</span>
                            </label>
                        </div>
                        <div class="space-y-2 mt-2">
                            <input type="date" name="fecha_cesion" class="input input-xs input-bordered w-full" />
                            <div class="relative" id="nuevo-contratista-autocomplete">
                                <input type="text" id="nuevo-contratista-search" class="input input-xs input-bordered w-full" placeholder="Buscar contratista..." autocomplete="off">
                                <input type="hidden" name="id_nuevo_contratista" id="nuevo-contratista-id">
                                <div id="nuevo-contratista-results" class="absolute z-50 w-full mt-1 bg-white shadow-lg border border-base-300 rounded-box hidden max-h-40 overflow-y-auto text-xs"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div id="error-valor-cdp" class="alert alert-error shadow-lg mb-4" style="display:none;">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>El valor del contrato (<strong>Valor Total</strong>) no puede ser mayor al <strong>Valor del CDP</strong>.</span>
        </div>

        <div class="flex justify-end pt-6">
            <button type="submit" class="btn btn-primary btn-lg shadow-xl px-12">
                <i class="fa-solid fa-save mr-2"></i> Radicar Contrato en el Sistema
            </button>
        </div>

        <script>
        function initAutocomplete(config) {
            const searchInput = document.getElementById(config.searchId);
            const hiddenInput = document.getElementById(config.hiddenId);
            const resultsDiv = document.getElementById(config.resultsId);
            let timeout = null;
            let selected = false;

            if (!searchInput) return;

            function fetchResults(termino) {
                const url = config.url + '&q=' + encodeURIComponent(termino);
                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        if (data.length === 0) {
                            resultsDiv.classList.add('hidden');
                            return;
                        }
                        resultsDiv.classList.remove('hidden');
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'px-3 py-2 cursor-pointer hover:bg-primary hover:text-primary-content border-b border-base-200';
                            div.textContent = item.documento + ' - ' + item.nombre;
                            div.addEventListener('click', function() {
                                searchInput.value = item.documento + ' - ' + item.nombre;
                                hiddenInput.value = item.id;
                                resultsDiv.classList.add('hidden');
                                selected = true;
                            });
                            resultsDiv.appendChild(div);
                        });
                    });
            }

            searchInput.addEventListener('input', function() {
                const termino = this.value.trim();
                hiddenInput.value = '';
                selected = false;
                if (termino.length < 1) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    fetchResults(termino);
                }, 300);
            });

            searchInput.addEventListener('blur', function() {
                setTimeout(function() {
                    resultsDiv.classList.add('hidden');
                }, 200);
            });

            searchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 1 && resultsDiv.children.length > 0) {
                    resultsDiv.classList.remove('hidden');
                }
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    resultsDiv.classList.add('hidden');
                }
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (!resultsDiv.classList.contains('hidden') && resultsDiv.children.length > 0) {
                        resultsDiv.children[0].click();
                    }
                }
            });

            if (config.initialText) {
                searchInput.value = config.initialText;
                hiddenInput.value = config.initialValue;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const inputFirma = document.getElementById('fecha_firma_calc');
            const inputFinEst = document.getElementById('fecha_terminacion_calc');
            const inputPlazoEst = document.getElementById('plazo_ejecucion_calc');

            const inputInicio = document.getElementById('fecha_inicio_calc');
            const inputFinReal = document.getElementById('fecha_terminacion_real_calc');
            const inputPlazoReal = document.getElementById('plazo_ejecucion_real_calc');

            function obtenerDiferenciaDias(fechaInicioVal, fechaFinVal) {
                if (!fechaInicioVal || !fechaFinVal) return "";
                
                const f1 = new Date(fechaInicioVal);
                const f2 = new Date(fechaFinVal);
                
                f1.setMinutes(f1.getMinutes() + f1.getTimezoneOffset());
                f2.setMinutes(f2.getMinutes() + f2.getTimezoneOffset());

                if (f2 >= f1) {
                    const diferenciaMs = f2 - f1;
                    const diasTotales = Math.floor(diferenciaMs / (1000 * 60 * 60 * 24));
                    return diasTotales === 0 ? "Mismo día" : diasTotales + (diasTotales === 1 ? " día" : " días");
                } else {
                    return "Fechas inválidas";
                }
            }

            function calcularPlazoEstimado() {
                if (inputFirma && inputFinEst && inputPlazoEst) {
                    inputPlazoEst.value = obtenerDiferenciaDias(inputFirma.value, inputFinEst.value);
                }
            }

            function calcularPlazoReal() {
                if (inputInicio && inputFinReal && inputPlazoReal) {
                    inputPlazoReal.value = obtenerDiferenciaDias(inputInicio.value, inputFinReal.value);
                }
            }

            if (inputFirma) {
                inputFirma.addEventListener('change', calcularPlazoEstimado);
            }
            if (inputFinEst) {
                inputFinEst.addEventListener('change', calcularPlazoEstimado);
            }
            if (inputInicio) {
                inputInicio.addEventListener('change', function() {
                    calcularPlazoEstimado();
                    calcularPlazoReal();
                });
            }
            if (inputFinReal) {
                inputFinReal.addEventListener('change', calcularPlazoReal);
            }

            initAutocomplete({
                searchId: 'contratista-search',
                hiddenId: 'contratista-id',
                resultsId: 'contratista-results',
                url: 'index.php?controller=contratista&action=buscarJson'
            });

            initAutocomplete({
                searchId: 'nuevo-contratista-search',
                hiddenId: 'nuevo-contratista-id',
                resultsId: 'nuevo-contratista-results',
                url: 'index.php?controller=contratista&action=buscarJson'
            });
        });
        </script>

        <script>
        function obtenerValorNumerico(input) {
            return parseFloat(input.value.replace(/\./g, '')) || 0;
        }

        function validarValores() {
            var total = document.querySelector('[name="valor_total"]');
            var cdp = document.querySelector('[name="valor_cdp"]');
            var errorDiv = document.getElementById('error-valor-cdp');
            var btn = document.querySelector('button[type="submit"]');
            if (!total || !cdp || !errorDiv || !btn) return;
            var vTotal = obtenerValorNumerico(total);
            var vCdp = obtenerValorNumerico(cdp);
            if (vTotal > 0 && vCdp > 0 && vTotal > vCdp) {
                errorDiv.style.display = 'block';
                btn.disabled = true;
            } else {
                errorDiv.style.display = 'none';
                btn.disabled = false;
            }
        }

        function formatearMoneda(input) {
            var valor = input.value.replace(/[^0-9]/g, '');
            if (valor) {
                input.value = new Intl.NumberFormat('es-CO').format(valor);
            }
        }
        document.querySelectorAll('.currency-input').forEach(function(input) {
            formatearMoneda(input);
            input.addEventListener('input', function() {
                formatearMoneda(this);
                validarValores();
            });
        });
        document.querySelector('form').addEventListener('submit', function() {
            document.querySelectorAll('.currency-input').forEach(function(input) {
                input.value = input.value.replace(/\./g, '');
            });
            var vTotal = obtenerValorNumerico(this.querySelector('[name="valor_total"]'));
            var vCdp = obtenerValorNumerico(this.querySelector('[name="valor_cdp"]'));
            if (vTotal > 0 && vCdp > 0 && vTotal > vCdp) {
                alert('El valor del contrato no puede ser mayor al valor del CDP.');
                return false;
            }
        });
        </script>
    </form>
</div>