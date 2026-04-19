@extends('layouts.admin')

@section('title', 'Editar Usuario - Admin')
@section('page-title', 'Editar Usuario')
@section('page-subtitle', 'Modifica la información del usuario seleccionado')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Navegación de migas de pan --}}
        <nav class="flex mb-8 text-sm font-medium text-muted-foreground" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Inicio</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('admin.usuarios.index') }}" class="hover:text-primary transition-colors">Usuarios</a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-foreground">Editar</li>
            </ol>
        </nav>

        {{-- Alerta si se está editando a sí mismo --}}
        @if($usuario->id == auth()->id())
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <p class="text-sm font-bold text-amber-800">Estás editando tu propia cuenta</p>
                    <p class="text-xs text-amber-600">No podrás desactivarte ni quitarte el rol de administrador. Pide a otro administrador si necesitas realizar esos cambios.</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-border overflow-hidden">
            {{-- Encabezado del Formulario --}}
            <div class="bg-slate-50 border-b border-border p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <p class="text-muted-foreground italic text-lg font-medium">Modifica la información de: <span
                                class="text-[#669BBC] font-bold">{{ $usuario->nombre }}</span></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-400">ID: #{{ $usuario->id }}</span>
                        <span class="text-xs px-2 py-1 rounded-full font-bold {{ $usuario->estatus === 'activo' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($usuario->estatus) }}
                        </span>
                    </div>
                </div>
                {{-- Info de creación --}}
                <div class="mt-4 flex flex-wrap gap-4 text-xs text-slate-400">
                    <span>📅 Creado: {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    <span>🔄 Última actualización: {{ $usuario->updated_at ? $usuario->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    @if($usuario->google_id)
                        <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-bold">🔗 Vinculado con Google</span>
                    @endif
                </div>
            </div>

            <div class="p-8">
                <form action="{{ route('admin.usuarios.update', $usuario->id) }}" method="POST" class="space-y-6" id="editUserForm" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Campo: Nombre (Solo lectura) --}}
                        <div class="space-y-2">
                            <label for="nombre"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Nombre
                                Completo</label>
                            <div class="relative">
                                <input type="text" name="nombre" id="nombre"
                                    class="w-full bg-slate-100 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-500 cursor-not-allowed outline-none"
                                    value="{{ $usuario->nombre }}" readonly disabled>
                            </div>
                            <p class="text-[10px] text-slate-400 ml-1">El nombre no puede ser modificado.</p>
                        </div>

                        {{-- Campo: Email (Solo lectura) --}}
                        <div class="space-y-2">
                            <label for="email"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Correo
                                Electrónico</label>
                            <div class="relative">
                                <input type="email" name="email" id="email"
                                    class="w-full bg-slate-100 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-500 cursor-not-allowed outline-none"
                                    value="{{ $usuario->email }}" readonly disabled>
                            </div>
                            <p class="text-[10px] text-slate-400 ml-1">El correo no puede ser modificado.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Sección de Contraseña Eliminada por Privacidad --}}

                        {{-- Campo: Estatus --}}
                        <div class="space-y-2" id="estatus-container">
                            <label for="estatus"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Estatus de la
                                Cuenta <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="estatus" id="estatus"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none appearance-none cursor-pointer"
                                    data-original="{{ $usuario->estatus }}"
                                    @if($usuario->id == auth()->id()) title="No puedes desactivarte a ti mismo" @endif>
                                    <option value="activo" {{ old('estatus', $usuario->estatus) == 'activo' ? 'selected' : '' }}>🟢 Activo</option>
                                    <option value="inactivo" {{ old('estatus', $usuario->estatus) == 'inactivo' ? 'selected' : '' }}
                                        @if($usuario->id == auth()->id() || !$puedeDesactivar) disabled @endif>🔴 Inactivo @if($usuario->id == auth()->id()) (no disponible para tu cuenta) @elseif(!$puedeDesactivar) (tiene contratos/propiedades) @endif</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @if($usuario->id == auth()->id())
                                <p class="text-[10px] text-amber-500 ml-1 font-bold">⚠️ No puedes desactivar tu propia cuenta.</p>
                            @elseif(!$puedeDesactivar)
                                <p class="text-[10px] text-amber-500 ml-1 font-bold">⚠️ Este usuario no puede ser desactivado (tiene propiedades o contratos vigentes).</p>
                            @endif
                        </div>
                    </div>

                    {{-- Campo: Roles --}}
                    <div class="space-y-2">
                        <label class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Roles Asignados <span class="text-red-500">*</span></label>
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($roles as $role)
                                    @php
                                        $isCurrentUserAdmin = $usuario->id == auth()->id() && $role->nombre === 'admin';
                                        $isChecked = in_array($role->id, old('roles', $usuario->roles->pluck('id')->toArray()));
                                    @endphp
                                    <label class="flex items-center gap-3 cursor-pointer group hover:bg-white rounded-xl p-3 transition-all border border-transparent hover:border-slate-200 {{ $isCurrentUserAdmin ? 'opacity-75' : '' }}">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                            class="w-5 h-5 rounded border-slate-300 text-[#003049] focus:ring-[#669BBC] transition-all"
                                            {{ $isChecked ? 'checked' : '' }}
                                            {{ $isCurrentUserAdmin ? 'disabled checked' : '' }}>
                                        {{-- Si disabled, enviar como hidden para que se incluya en el POST --}}
                                        @if($isCurrentUserAdmin)
                                            <input type="hidden" name="roles[]" value="{{ $role->id }}">
                                        @endif
                                        <div>
                                            <span class="text-sm font-bold text-slate-700 group-hover:text-[#003049] transition-colors">
                                                {{ $role->etiqueta ?? ucfirst($role->nombre) }}
                                            </span>
                                            @if($role->nombre === 'admin')
                                                <span class="ml-1 text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-bold">⚠️ Admin</span>
                                            @endif
                                            @if($isCurrentUserAdmin)
                                                <p class="text-[9px] text-amber-500 font-bold mt-0.5">🔒 No puedes quitarte este rol</p>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <p id="roles-error" class="text-red-500 text-xs mt-1 ml-1 hidden">Debes asignar al menos un rol.</p>
                        @error('roles')
                            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Panel de cambios detectados --}}
                    <div id="changes-panel" class="bg-blue-50 border border-blue-100 rounded-2xl p-5 hidden">
                        <h3 class="text-sm font-black text-[#003049] uppercase tracking-widest mb-3">📝 Cambios detectados</h3>
                        <ul id="changes-list" class="space-y-1 text-sm text-slate-700"></ul>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-slate-100">
                        <a href="{{ route('admin.usuarios.index') }}"
                            class="px-8 py-3.5 text-sm font-bold text-slate-600 bg-slate-100 rounded-2xl hover:bg-slate-200 transition-all text-center">
                            Cerrar sin guardar
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-8 py-3.5 text-sm font-bold text-white bg-[#003049] rounded-2xl hover:bg-[#669BBC] shadow-xl shadow-blue-900/10 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            Actualizar Información
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editUserForm');
        const nombreInput = document.getElementById('nombre');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');

        // Valores originales para detectar cambios
        const originals = {
            estatus: document.getElementById('estatus').dataset.original || '',
            roles: @json($usuario->roles->pluck('id')->toArray())
        };

            // Validación eliminada

        // Detectar cambios en estatus
        document.getElementById('estatus').addEventListener('change', function() {
            detectChanges();
        });

        // Detectar cambios en roles
        document.querySelectorAll('input[name="roles[]"]').forEach(function(cb) {
            cb.addEventListener('change', function() {
                const checked = document.querySelectorAll('input[name="roles[]"]:checked');
                const errorEl = document.getElementById('roles-error');
                if (checked.length === 0) {
                    showError(errorEl, 'Debes asignar al menos un rol.');
                } else {
                    hideError(errorEl);
                }
                detectChanges();
            });
        });

        // Validación al enviar
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let hasErrors = false;

            // Validar contraseña si se proporcionó
            if (passwordInput.value.length > 0) {
                if (passwordInput.value.length < 8) {
                    showError(document.getElementById('password-error'), 'La contraseña debe tener al menos 8 caracteres.');
                    hasErrors = true;
                }
                if (passwordInput.value !== confirmInput.value) {
                    showError(document.getElementById('confirm-error'), 'Las contraseñas no coinciden.');
                    hasErrors = true;
                }
            }

            // Validar roles
            const checkedRoles = document.querySelectorAll('input[name="roles[]"]:checked');
            if (checkedRoles.length === 0) {
                showError(document.getElementById('roles-error'), 'Debes asignar al menos un rol.');
                hasErrors = true;
            }

            if (hasErrors) {
                Swal.fire({
                    title: 'Formulario incompleto',
                    text: 'Por favor corrige los errores marcados en rojo.',
                    icon: 'warning',
                    confirmButtonColor: '#003049',
                });
                return;
            }

            // Generar resumen de cambios para confirmación
            const changes = getChanges();
            
            if (changes.length === 0) {
                Swal.fire({
                    title: 'Sin cambios',
                    text: 'No has modificado ningún dato del usuario.',
                    icon: 'info',
                    confirmButtonColor: '#003049',
                });
                return;
            }

            const changesHtml = changes.map(c => `<li style="text-align: left;">• ${c}</li>`).join('');
            
            Swal.fire({
                title: '¿Guardar cambios?',
                html: `
                    <p style="margin-bottom: 0.5rem; font-weight: bold;">Se modificará el usuario: ${originals.nombre}</p>
                    <ul style="font-size: 0.875rem; list-style: none; padding: 0;">
                        ${changesHtml}
                    </ul>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#003049',
                cancelButtonColor: '#C1121F',
                confirmButtonText: 'Sí, guardar cambios',
                cancelButtonText: 'Revisar de nuevo',
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Guardando...';
                    form.submit();
                }
            });
        });

        function getChanges() {
            const changes = [];
            
            if (document.getElementById('estatus').value !== originals.estatus) {
                changes.push(`<strong>Estatus:</strong> "${originals.estatus}" → "${document.getElementById('estatus').value}"`);
            }
            
            const currentRoles = Array.from(document.querySelectorAll('input[name="roles[]"]:checked')).map(cb => parseInt(cb.value)).sort();
            const originalRoles = originals.roles.sort();
            if (JSON.stringify(currentRoles) !== JSON.stringify(originalRoles)) {
                const roleNames = Array.from(document.querySelectorAll('input[name="roles[]"]:checked'))
                    .map(cb => cb.closest('label').querySelector('span').textContent.trim());
                changes.push(`<strong>Roles:</strong> Se actualizarán a: ${roleNames.join(', ')}`);
            }
            
            return changes;
        }

        function detectChanges() {
            const changes = getChanges();
            const panel = document.getElementById('changes-panel');
            const list = document.getElementById('changes-list');
            
            if (changes.length > 0) {
                list.innerHTML = changes.map(c => `<li class="flex items-start gap-2"><span class="text-blue-500">→</span> ${c}</li>`).join('');
                panel.classList.remove('hidden');
            } else {
                panel.classList.add('hidden');
            }
        }
    });

    function checkPasswordStrength(password) {
        let score = 0;
        const checks = {
            length: password.length >= 8,
            upper: /[A-Z]/.test(password),
            lower: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
        };

        updateReq('req-length', checks.length);
        updateReq('req-upper', checks.upper);
        updateReq('req-lower', checks.lower);
        updateReq('req-number', checks.number);
        updateReq('req-special', checks.special);

        score = Object.values(checks).filter(Boolean).length;

        const colors = ['', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-lime-500', 'bg-green-500'];
        const texts = ['', '🔴 Muy débil', '🟠 Débil', '🟡 Regular', '🟢 Buena', '💚 Excelente'];

        for (let i = 1; i <= 5; i++) {
            const bar = document.getElementById('str-' + i);
            bar.className = 'h-1.5 flex-1 rounded-full transition-all duration-300 ' + 
                (i <= score ? colors[score] : 'bg-slate-200');
        }
        document.getElementById('password-strength-text').textContent = password.length > 0 ? texts[score] : '';
    }

    function updateReq(id, passed) {
        const el = document.getElementById(id);
        if (!el) return;
        const icon = el.querySelector('.req-icon');
        if (passed) {
            el.classList.remove('text-slate-400');
            el.classList.add('text-green-600');
            icon.textContent = '✓';
        } else {
            el.classList.remove('text-green-600');
            el.classList.add('text-slate-400');
            icon.textContent = '○';
        }
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const errorEl = document.getElementById('confirm-error');
        const matchEl = document.getElementById('confirm-match');

        if (confirm.length === 0) {
            hideError(errorEl);
            matchEl.classList.add('hidden');
            setFieldState(document.getElementById('password_confirmation'), 'neutral');
            return;
        }

        if (password !== confirm) {
            showError(errorEl, 'Las contraseñas no coinciden.');
            matchEl.classList.add('hidden');
            setFieldState(document.getElementById('password_confirmation'), 'error');
        } else {
            hideError(errorEl);
            matchEl.classList.remove('hidden');
            setFieldState(document.getElementById('password_confirmation'), 'success');
        }
    }

    function togglePassword(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        
        if (field.type === 'password') {
            field.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            field.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    function showError(el, message) {
        el.textContent = message;
        el.classList.remove('hidden');
    }

    function hideError(el) {
        el.classList.add('hidden');
    }

    function setFieldState(input, state) {
        input.classList.remove('border-red-400', 'border-green-400', 'border-blue-400', 'border-slate-200', 'bg-red-50', 'bg-green-50', 'bg-blue-50');
        if (state === 'error') {
            input.classList.add('border-red-400', 'bg-red-50');
        } else if (state === 'success') {
            input.classList.add('border-green-400', 'bg-green-50');
        } else if (state === 'changed') {
            input.classList.add('border-blue-400', 'bg-blue-50');
        } else {
            input.classList.add('border-slate-200');
        }
    }
    </script>
    @endpush
@endsection
