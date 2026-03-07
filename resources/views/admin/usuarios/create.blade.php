@extends('layouts.app')

@section('title', 'Crear Usuario - Admin')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="flex mb-8 text-sm font-medium text-muted-foreground" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('inicio') }}" class="hover:text-primary transition-colors">Inicio</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('admin.usuarios.index') }}" class="hover:text-primary transition-colors">Usuarios</a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-foreground">Nuevo</li>
            </ol>
        </nav>

        <div class="bg-white rounded-3xl shadow-xl shadow-blue-900/5 border border-border overflow-hidden">
            <div class="bg-slate-50 border-b border-border p-8">
                <h1 class="text-3xl font-black text-[#003049] mb-2">Crear Nuevo Usuario</h1>
                <p class="text-muted-foreground italic">Registra un nuevo miembro en la plataforma <span
                        class="text-[#669BBC] font-bold">ArrendaOco</span>. Todos los campos marcados con <span
                        class="text-red-500 font-bold">*</span> son obligatorios.</p>
            </div>

            <div class="p-8">
                <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-6" id="createUserForm"
                    novalidate>
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Campo: Nombre --}}
                        <div class="space-y-2">
                            <label for="nombre"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Nombre
                                Completo <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" id="nombre"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none"
                                value="{{ old('nombre') }}" placeholder="Ej: Juan Pérez López" required minlength="3"
                                maxlength="100" pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$"
                                title="Solo letras y espacios, mínimo 3 caracteres">
                            <p class="text-[10px] text-slate-400 ml-1">Solo letras y espacios. Mínimo 3, máximo 100
                                caracteres.</p>
                            <p id="nombre-error" class="text-red-500 text-xs mt-1 ml-1 hidden"></p>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Email --}}
                        <div class="space-y-2">
                            <label for="email"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Correo
                                Electrónico <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none"
                                value="{{ old('email') }}" placeholder="ejemplo@arrendaoco.com" required maxlength="255">
                            <p class="text-[10px] text-slate-400 ml-1">Debe ser un correo real y no registrado en la
                                plataforma.</p>
                            <p id="email-error" class="text-red-500 text-xs mt-1 ml-1 hidden"></p>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Campo: Contraseña --}}
                        <div class="space-y-2">
                            <label for="password"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Contraseña <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 pr-12 text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none"
                                    placeholder="Mínimo 8 caracteres" required minlength="8" maxlength="64">
                                <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            {{-- Barra de fortaleza de contraseña --}}
                            <div class="mt-2">
                                <div class="flex gap-1 mb-1">
                                    <div id="str-1"
                                        class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300"></div>
                                    <div id="str-2"
                                        class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300"></div>
                                    <div id="str-3"
                                        class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300"></div>
                                    <div id="str-4"
                                        class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300"></div>
                                    <div id="str-5"
                                        class="h-1.5 flex-1 rounded-full bg-slate-200 transition-all duration-300"></div>
                                </div>
                                <p id="password-strength-text" class="text-[10px] text-slate-400 ml-1"></p>
                            </div>
                            {{-- Checklist de requisitos --}}
                            <div class="bg-slate-50 rounded-xl p-3 mt-2 space-y-1">
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Requisitos:
                                </p>
                                <div class="grid grid-cols-2 gap-1">
                                    <p id="req-length" class="text-[10px] text-slate-400 flex items-center gap-1">
                                        <span class="req-icon">○</span> Mínimo 8 caracteres
                                    </p>
                                    <p id="req-upper" class="text-[10px] text-slate-400 flex items-center gap-1">
                                        <span class="req-icon">○</span> Una mayúscula
                                    </p>
                                    <p id="req-lower" class="text-[10px] text-slate-400 flex items-center gap-1">
                                        <span class="req-icon">○</span> Una minúscula
                                    </p>
                                    <p id="req-number" class="text-[10px] text-slate-400 flex items-center gap-1">
                                        <span class="req-icon">○</span> Un número
                                    </p>
                                    <p id="req-special" class="text-[10px] text-slate-400 flex items-center gap-1">
                                        <span class="req-icon">○</span> Un carácter especial
                                    </p>
                                </div>
                            </div>
                            <p id="password-error" class="text-red-500 text-xs mt-1 ml-1 hidden"></p>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Confirmar Contraseña --}}
                        <div class="space-y-2">
                            <label for="password_confirmation"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Confirmar
                                Contraseña <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 pr-12 text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none"
                                    placeholder="Repite la contraseña" required>
                                <button type="button" onclick="togglePassword('password_confirmation', this)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <p id="confirm-error" class="text-red-500 text-xs mt-1 ml-1 hidden"></p>
                            <p id="confirm-match" class="text-green-600 text-xs mt-1 ml-1 hidden">✓ Las contraseñas
                                coinciden</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Campo: Estatus --}}
                        <div class="space-y-2">
                            <label for="estatus"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Estatus
                                Inicial <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="estatus" id="estatus"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                                    <option value="activo" {{ old('estatus') == 'activo' || !old('estatus') ? 'selected' : '' }}>🟢 Activo</option>
                                    <option value="inactivo" {{ old('estatus') == 'inactivo' ? 'selected' : '' }}>🔴 Inactivo
                                    </option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Campo: Roles --}}
                        <div class="space-y-2">
                            <label class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Roles <span
                                    class="text-red-500">*</span></label>
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3">
                                @foreach($roles as $role)
                                    <label
                                        class="flex items-center gap-3 cursor-pointer group hover:bg-white rounded-xl p-2 transition-all">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                            class="w-5 h-5 rounded border-slate-300 text-[#003049] focus:ring-[#669BBC] transition-all"
                                            {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                        <div>
                                            <span
                                                class="text-sm font-bold text-slate-700 group-hover:text-[#003049] transition-colors">
                                                {{ $role->etiqueta ?? ucfirst($role->nombre) }}
                                            </span>
                                            @if($role->nombre === 'admin')
                                                <span
                                                    class="ml-2 text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-bold">⚠️
                                                    Privilegios totales</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p id="roles-error" class="text-red-500 text-xs mt-1 ml-1 hidden">Debes seleccionar al menos un
                                rol.</p>
                            @error('roles')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Resumen antes de enviar --}}
                    <div id="form-summary" class="bg-blue-50 border border-blue-100 rounded-2xl p-5 hidden">
                        <h3 class="text-sm font-black text-[#003049] uppercase tracking-widest mb-3">📋 Resumen del nuevo
                            usuario</h3>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div><span class="text-slate-500">Nombre:</span> <span id="sum-nombre"
                                    class="font-bold text-slate-700"></span></div>
                            <div><span class="text-slate-500">Email:</span> <span id="sum-email"
                                    class="font-bold text-slate-700"></span></div>
                            <div><span class="text-slate-500">Estatus:</span> <span id="sum-estatus"
                                    class="font-bold text-slate-700"></span></div>
                            <div><span class="text-slate-500">Roles:</span> <span id="sum-roles"
                                    class="font-bold text-slate-700"></span></div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-slate-100">
                        <a href="{{ route('admin.usuarios.index') }}"
                            class="px-8 py-3.5 text-sm font-bold text-slate-600 bg-slate-100 rounded-2xl hover:bg-slate-200 transition-all text-center">
                            Cancelar registro
                        </a>
                        <button type="submit" id="submitBtn"
                            class="px-8 py-3.5 text-sm font-bold text-white bg-[#003049] rounded-2xl hover:bg-[#669BBC] shadow-xl shadow-blue-900/10 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            Crear Cuenta de Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('createUserForm');
                const nombreInput = document.getElementById('nombre');
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');
                const confirmInput = document.getElementById('password_confirmation');
                const submitBtn = document.getElementById('submitBtn');

                // Validación de nombre en tiempo real
                nombreInput.addEventListener('input', function () {
                    const value = this.value;
                    const errorEl = document.getElementById('nombre-error');

                    // No permitir números ni caracteres especiales
                    this.value = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');

                    if (this.value.length > 0 && this.value.length < 3) {
                        showError(errorEl, 'El nombre necesita al menos 3 caracteres (' + (3 - this.value.length) + ' más).');
                        setFieldState(this, 'error');
                    } else if (this.value.length >= 3) {
                        hideError(errorEl);
                        setFieldState(this, 'success');
                    } else {
                        hideError(errorEl);
                        setFieldState(this, 'neutral');
                    }
                    updateSummary();
                });

                // Validación de email en tiempo real
                emailInput.addEventListener('input', function () {
                    const errorEl = document.getElementById('email-error');
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                    if (this.value.length > 0 && !emailRegex.test(this.value)) {
                        showError(errorEl, 'Formato de correo inválido.');
                        setFieldState(this, 'error');
                    } else if (this.value.length > 0 && emailRegex.test(this.value)) {
                        hideError(errorEl);
                        setFieldState(this, 'success');
                    } else {
                        hideError(errorEl);
                        setFieldState(this, 'neutral');
                    }
                    updateSummary();
                });

                // Validación de contraseña con fortaleza
                passwordInput.addEventListener('input', function () {
                    const value = this.value;
                    checkPasswordStrength(value);
                    checkPasswordMatch();
                    updateSummary();
                });

                // Validación de confirmación de contraseña
                confirmInput.addEventListener('input', function () {
                    checkPasswordMatch();
                });

                // Validar roles
                document.querySelectorAll('input[name="roles[]"]').forEach(function (cb) {
                    cb.addEventListener('change', function () {
                        const checked = document.querySelectorAll('input[name="roles[]"]:checked');
                        const errorEl = document.getElementById('roles-error');
                        if (checked.length === 0) {
                            showError(errorEl, 'Debes seleccionar al menos un rol.');
                        } else {
                            hideError(errorEl);
                        }
                        updateSummary();
                    });
                });

                // Actualizar resumen en cambio de estatus
                document.getElementById('estatus').addEventListener('change', function () {
                    updateSummary();
                });

                // Validación al enviar
                form.addEventListener('submit', function (e) {
                    let hasErrors = false;

                    // Validar nombre
                    if (nombreInput.value.trim().length < 3) {
                        showError(document.getElementById('nombre-error'), 'El nombre es obligatorio (mínimo 3 caracteres).');
                        setFieldState(nombreInput, 'error');
                        hasErrors = true;
                    }

                    // Validar email
                    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    if (!emailRegex.test(emailInput.value)) {
                        showError(document.getElementById('email-error'), 'Ingresa un correo electrónico válido.');
                        setFieldState(emailInput, 'error');
                        hasErrors = true;
                    }

                    // Validar contraseña
                    if (passwordInput.value.length < 8) {
                        showError(document.getElementById('password-error'), 'La contraseña debe tener al menos 8 caracteres.');
                        hasErrors = true;
                    }

                    // Validar confirmación
                    if (passwordInput.value !== confirmInput.value) {
                        showError(document.getElementById('confirm-error'), 'Las contraseñas no coinciden.');
                        hasErrors = true;
                    }

                    // Validar roles
                    const checkedRoles = document.querySelectorAll('input[name="roles[]"]:checked');
                    if (checkedRoles.length === 0) {
                        showError(document.getElementById('roles-error'), 'Debes seleccionar al menos un rol.');
                        hasErrors = true;
                    }

                    if (hasErrors) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Formulario incompleto',
                            text: 'Por favor corrige los errores marcados en rojo antes de continuar.',
                            icon: 'warning',
                            confirmButtonColor: '#003049',
                        });
                        return;
                    }

                    // Confirmación antes de crear
                    e.preventDefault();
                    const rolesText = Array.from(checkedRoles).map(cb => cb.closest('label').querySelector('span').textContent.trim()).join(', ');

                    Swal.fire({
                        title: '¿Crear este usuario?',
                        html: `
                            <div style="text-align: left; padding: 0.5rem;">
                                <p><strong>Nombre:</strong> ${nombreInput.value}</p>
                                <p><strong>Email:</strong> ${emailInput.value}</p>
                                <p><strong>Estatus:</strong> ${document.getElementById('estatus').value}</p>
                                <p><strong>Roles:</strong> ${rolesText}</p>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#003049',
                        cancelButtonColor: '#C1121F',
                        confirmButtonText: 'Sí, crear usuario',
                        cancelButtonText: 'Revisar datos',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Deshabilitar botón para evitar doble envío
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Creando...';
                            form.submit();
                        }
                    });
                });
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

                // Actualizar checklist visual
                updateReq('req-length', checks.length);
                updateReq('req-upper', checks.upper);
                updateReq('req-lower', checks.lower);
                updateReq('req-number', checks.number);
                updateReq('req-special', checks.special);

                score = Object.values(checks).filter(Boolean).length;

                // Actualizar barra de fortaleza
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
                const icon = el.querySelector('.req-icon');
                if (passed) {
                    el.className = el.className.replace('text-slate-400', 'text-green-600');
                    el.classList.add('text-green-600');
                    el.classList.remove('text-slate-400');
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
                input.classList.remove('border-red-400', 'border-green-400', 'border-slate-200', 'bg-red-50', 'bg-green-50');
                if (state === 'error') {
                    input.classList.add('border-red-400', 'bg-red-50');
                } else if (state === 'success') {
                    input.classList.add('border-green-400', 'bg-green-50');
                } else {
                    input.classList.add('border-slate-200');
                }
            }

            function updateSummary() {
                const nombre = document.getElementById('nombre').value.trim();
                const email = document.getElementById('email').value.trim();
                const estatus = document.getElementById('estatus').value;
                const roles = Array.from(document.querySelectorAll('input[name="roles[]"]:checked'))
                    .map(cb => cb.closest('label').querySelector('span').textContent.trim());

                const summaryDiv = document.getElementById('form-summary');

                if (nombre.length >= 3 && email.length > 0) {
                    document.getElementById('sum-nombre').textContent = nombre;
                    document.getElementById('sum-email').textContent = email;
                    document.getElementById('sum-estatus').textContent = estatus === 'activo' ? '🟢 Activo' : '🔴 Inactivo';
                    document.getElementById('sum-roles').textContent = roles.length > 0 ? roles.join(', ') : 'Sin rol asignado';
                    summaryDiv.classList.remove('hidden');
                } else {
                    summaryDiv.classList.add('hidden');
                }
            }
        </script>
    @endpush
@endsection