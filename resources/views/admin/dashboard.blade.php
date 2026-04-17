@extends('layouts.admin')

@section('title', 'Dashboard - Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Resumen general de la plataforma ArrendaOco')

@section('content')

    {{-- TARJETAS DE ESTADÍSTICAS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        {{-- Card: Total Usuarios --}}
        <div class="admin-stat-card bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 bg-[#003049]/5 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Usuarios</span>
            </div>
            <p class="text-3xl font-black text-[#003049]">{{ $totalUsuarios }}</p>
            <div class="flex items-center gap-3 mt-3 text-xs text-slate-400">
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                    {{ $usuariosActivos }} activos
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                    {{ $usuariosInactivos }} inactivos
                </span>
            </div>
        </div>

        {{-- Card: Total Propiedades --}}
        <div class="admin-stat-card bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 bg-[#669BBC]/10 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Propiedades</span>
            </div>
            <p class="text-3xl font-black text-[#003049]">{{ $totalInmuebles }}</p>
            <div class="flex items-center gap-3 mt-3 text-xs text-slate-400">
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-[#669BBC]"></span>
                    {{ $inmueblesDisponibles }} disponibles
                </span>
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    {{ $inmueblesRentados }} rentados
                </span>
            </div>
        </div>

        {{-- Card: Contratos Activos --}}
        <div class="admin-stat-card bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Contratos</span>
            </div>
            <p class="text-3xl font-black text-[#003049]">{{ $contratosActivos }}</p>
            <div class="flex items-center gap-3 mt-3 text-xs text-slate-400">
                <span class="flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                    {{ $contratosPendientes }} pendientes
                </span>
            </div>
        </div>

        {{-- Card: Reseñas --}}
        <div class="admin-stat-card bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <div class="flex items-start justify-between mb-4">
                <div class="p-3 bg-amber-50 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Reseñas</span>
            </div>
            <p class="text-3xl font-black text-[#003049]">{{ $totalResenas }}</p>
            <div class="flex items-center gap-3 mt-3 text-xs text-slate-400">
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    {{ number_format($promedioCalificacion, 1) }} promedio
                </span>
            </div>
        </div>
    </div>

    {{-- SECCIÓN DE ACCESO RÁPIDO & ACTIVIDAD --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Acciones Rápidas --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-sm font-black text-[#003049] uppercase tracking-widest">Acciones Rápidas</h2>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('admin.usuarios.create') }}"
                        class="flex items-center gap-3 p-4 rounded-xl hover:bg-slate-50 transition-all group">
                        <div class="p-2 bg-[#003049]/5 rounded-lg group-hover:bg-[#003049]/10 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#003049]">Crear Usuario</p>
                            <p class="text-xs text-slate-400">Registrar nuevo miembro</p>
                        </div>
                        <svg class="h-4 w-4 text-slate-300 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.usuarios.index') }}"
                        class="flex items-center gap-3 p-4 rounded-xl hover:bg-slate-50 transition-all group">
                        <div class="p-2 bg-[#669BBC]/10 rounded-lg group-hover:bg-[#669BBC]/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#003049]">Gestionar Usuarios</p>
                            <p class="text-xs text-slate-400">Editar roles y estatus</p>
                        </div>
                        <svg class="h-4 w-4 text-slate-300 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.resenas.index') }}"
                        class="flex items-center gap-3 p-4 rounded-xl hover:bg-slate-50 transition-all group">
                        <div class="p-2 bg-amber-50 rounded-lg group-hover:bg-amber-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#003049]">Moderar Reseñas</p>
                            <p class="text-xs text-slate-400">Supervisar comentarios</p>
                        </div>
                        <svg class="h-4 w-4 text-slate-300 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.usuarios.reporte') }}"
                        class="flex items-center gap-3 p-4 rounded-xl hover:bg-slate-50 transition-all group">
                        <div class="p-2 bg-red-50 rounded-lg group-hover:bg-red-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C1121F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#003049]">Generar Reporte PDF</p>
                            <p class="text-xs text-slate-400">Descargar informe de usuarios</p>
                        </div>
                        <svg class="h-4 w-4 text-slate-300 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Últimos Usuarios --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-black text-[#003049] uppercase tracking-widest">Últimos Usuarios Registrados</h2>
                    <a href="{{ route('admin.usuarios.index') }}" class="text-xs font-bold text-[#669BBC] hover:underline">Ver todos →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Usuario</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Roles</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Estatus</th>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($ultimosUsuarios as $usuario)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($usuario->foto_perfil)
                                                <img src="{{ str_starts_with($usuario->foto_perfil, 'http') ? $usuario->foto_perfil : asset('storage/' . $usuario->foto_perfil) }}"
                                                    class="h-8 w-8 rounded-full object-cover">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-[#003049]/5 flex items-center justify-center text-xs font-bold text-[#003049]">
                                                    {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-bold text-[#003049]">{{ $usuario->nombre }}</p>
                                                <p class="text-xs text-slate-400">{{ $usuario->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @foreach($usuario->roles as $role)
                                            <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full mr-1
                                                {{ $role->nombre === 'admin' ? 'bg-amber-100 text-amber-700' : ($role->nombre === 'propietario' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700') }}">
                                                {{ $role->etiqueta ?? ucfirst($role->nombre) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold
                                            {{ $usuario->estatus === 'activo' ? 'text-green-600' : 'text-red-500' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $usuario->estatus === 'activo' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                            {{ ucfirst($usuario->estatus) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400 font-medium">
                                        {{ $usuario->created_at ? $usuario->created_at->diffForHumans() : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- DISTRIBUCIÓN DE ROLES --}}
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Distribución de Roles --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="text-sm font-black text-[#003049] uppercase tracking-widest mb-6">Distribución de Roles</h2>
            <div class="space-y-4">
                @foreach($rolesDistribucion as $rol)
                    @php
                        $porcentaje = $totalUsuarios > 0 ? ($rol->users_count / $totalUsuarios) * 100 : 0;
                        $colors = [
                            'admin' => ['bg-amber-500', 'bg-amber-100'],
                            'propietario' => ['bg-emerald-500', 'bg-emerald-100'],
                            'inquilino' => ['bg-blue-500', 'bg-blue-100'],
                        ];
                        $barColor = $colors[$rol->nombre][0] ?? 'bg-slate-500';
                        $bgColor = $colors[$rol->nombre][1] ?? 'bg-slate-100';
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-bold text-[#003049]">{{ $rol->etiqueta ?? ucfirst($rol->nombre) }}</span>
                            <span class="text-xs font-bold text-slate-400">{{ $rol->users_count }} ({{ number_format($porcentaje, 0) }}%)</span>
                        </div>
                        <div class="h-2.5 rounded-full {{ $bgColor }} overflow-hidden">
                            <div class="h-full rounded-full {{ $barColor }} transition-all duration-500"
                                style="width: {{ $porcentaje }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Info de la Plataforma --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="text-sm font-black text-[#003049] uppercase tracking-widest mb-6">Información del Sistema</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-slate-50">
                    <span class="text-sm text-slate-500">Plataforma</span>
                    <span class="text-sm font-bold text-[#003049]">ArrendaOco v1.0</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-slate-50">
                    <span class="text-sm text-slate-500">Framework</span>
                    <span class="text-sm font-bold text-[#003049]">Laravel {{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-slate-50">
                    <span class="text-sm text-slate-500">PHP</span>
                    <span class="text-sm font-bold text-[#003049]">{{ phpversion() }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-slate-50">
                    <span class="text-sm text-slate-500">Región</span>
                    <span class="text-sm font-bold text-[#003049]">Ocosingo, Chiapas</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-slate-500">Administrador</span>
                    <span class="text-sm font-bold text-[#003049]">{{ Auth::user()->nombre }}</span>
                </div>
            </div>
        </div>
    </div>

@endsection
