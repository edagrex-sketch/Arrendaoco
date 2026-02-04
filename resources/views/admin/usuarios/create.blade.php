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
                        class="text-[#669BBC] font-bold">ArrendaOco</span>.</p>
            </div>

            <div class="p-8">
                <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="nombre"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Nombre
                                Completo</label>
                            <input type="text" name="nombre" id="nombre"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none"
                                value="{{ old('nombre') }}" placeholder="Ej: Juan Pérez" required>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Correo
                                Electrónico</label>
                            <input type="email" name="email" id="email"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none"
                                value="{{ old('email') }}" placeholder="ejemplo@arrendaoco.com" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="password"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Contraseña</label>
                            <input type="password" name="password" id="password"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none"
                                placeholder="Mínimo 8 caracteres" required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Confirmar
                                Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 placeholder:text-slate-400 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none"
                                placeholder="Repite la contraseña" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="status"
                                class="text-sm font-black text-[#003049] uppercase tracking-widest ml-1">Estatus
                                Inicial</label>
                            <div class="relative">
                                <select name="status" id="status"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-slate-700 focus:ring-4 focus:ring-blue-100 focus:border-[#669BBC] focus:bg-white transition-all outline-none appearance-none cursor-pointer">
                                    <option value="activo">🟢 Activo</option>
                                    <option value="inactivo">🔴 Inactivo</option>
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
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-slate-100">
                        <a href="{{ route('admin.usuarios.index') }}"
                            class="px-8 py-3.5 text-sm font-bold text-slate-600 bg-slate-100 rounded-2xl hover:bg-slate-200 transition-all text-center">
                            Cancelar registro
                        </a>
                        <button type="submit"
                            class="px-8 py-3.5 text-sm font-bold text-white bg-[#003049] rounded-2xl hover:bg-[#669BBC] shadow-xl shadow-blue-900/10 transition-all active:scale-95">
                            Crear Cuenta de Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
